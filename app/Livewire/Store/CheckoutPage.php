<?php

namespace App\Livewire\Store;

use App\Enums\PaymentGateway;
use App\Services\CartService;
use App\Services\CheckoutService;
use App\Services\PaymentService;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;

class CheckoutPage extends Component
{
    public array $shipping = [];

    public string $paymentMethod = 'cod';

    public bool $useWallet = false;

    public ?string $guestEmail = null;

    public string $notes = '';

    public function mount(): void
    {
        $this->shipping = [
            'first_name' => Auth::user()?->name ? explode(' ', Auth::user()->name)[0] : '',
            'last_name' => '',
            'phone' => Auth::user()?->phone ?? '',
            'address_line_1' => '',
            'city' => '',
            'state' => '',
            'postal_code' => '',
            'country' => 'IN',
        ];
    }

    public function placeOrder(CheckoutService $checkout, PaymentService $payment, CartService $cart): void
    {
        $this->validate([
            'shipping.first_name' => 'required|string|max:100',
            'shipping.last_name' => 'required|string|max:100',
            'shipping.address_line_1' => 'required|string|max:255',
            'shipping.city' => 'required|string|max:100',
            'shipping.state' => 'required|string|max:100',
            'shipping.postal_code' => 'required|string|max:20',
            'guestEmail' => Auth::check() ? 'nullable' : 'required|email',
        ]);

        $order = $checkout->placeOrder([
            'shipping_address' => $this->shipping,
            'billing_address' => $this->shipping,
            'payment_method' => $this->paymentMethod,
            'guest_email' => $this->guestEmail,
            'notes' => $this->notes,
            'use_wallet' => $this->useWallet,
        ]);

        if ($this->paymentMethod === PaymentGateway::Stripe->value) {
            $session = $payment->createStripeSession(
                $order,
                route('checkout.success', $order),
                route('checkout')
            );
            $this->redirect($session->url);

            return;
        }

        if ($this->paymentMethod === PaymentGateway::Razorpay->value) {
            session(['razorpay_order_id' => $order->id]);

            return;
        }

        $this->redirect(route('orders.show', $order), navigate: true);
    }

    public function render(CheckoutService $checkout)
    {
        $totals = $checkout->calculateTotals();

        return view('livewire.store.checkout-page', [
            'totals' => $totals,
        ])->layout('layouts.store');
    }
}
