<?php

namespace App\Http\Controllers;

use App\Models\Order;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\Auth;

class InvoiceController extends Controller
{
    public function download(Order $order)
    {
        if ($order->user_id && $order->user_id !== Auth::id() && ! Auth::user()?->is_admin) {
            abort(403);
        }

        $order->load('items');

        $pdf = Pdf::loadView('invoices.order', compact('order'));

        return $pdf->download("invoice-{$order->order_number}.pdf");
    }
}
