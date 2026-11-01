<?php

return [
    'name' => env('SHOP_NAME', 'MS Furniture'),
    'tagline' => env('SHOP_TAGLINE', 'Furniture Made for Living'),
    'currency' => env('SHOP_CURRENCY', 'INR'),
    'currency_symbol' => env('SHOP_CURRENCY_SYMBOL', '₹'),
    'tax_rate' => env('SHOP_TAX_RATE', 18),
    'shipping_fee' => env('SHOP_SHIPPING_FEE', 499),
    'free_shipping_threshold' => env('SHOP_FREE_SHIPPING', 15000),
    'reward_points_per_dollar' => env('SHOP_POINTS_PER_DOLLAR', 1),
    'stripe_key' => env('STRIPE_KEY'),
    'stripe_secret' => env('STRIPE_SECRET'),
    'stripe_webhook_secret' => env('STRIPE_WEBHOOK_SECRET'),
    'razorpay_key' => env('RAZORPAY_KEY'),
    'razorpay_secret' => env('RAZORPAY_SECRET'),
    'google_client_id' => env('GOOGLE_CLIENT_ID'),
    'google_client_secret' => env('GOOGLE_CLIENT_SECRET'),
    'google_analytics_id' => env('GOOGLE_ANALYTICS_ID'),
    'facebook_pixel_id' => env('FACEBOOK_PIXEL_ID'),
    'whatsapp_number' => env('WHATSAPP_NUMBER'),
    'openai_api_key' => env('OPENAI_API_KEY'),
];
