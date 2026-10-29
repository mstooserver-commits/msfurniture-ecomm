<?php

namespace App\Enums;

enum PaymentGateway: string
{
    case Stripe = 'stripe';
    case Razorpay = 'razorpay';
    case Cod = 'cod';

    public function label(): string
    {
        return match ($this) {
            self::Stripe => 'Stripe',
            self::Razorpay => 'Razorpay',
            self::Cod => 'Cash on Delivery',
        };
    }
}
