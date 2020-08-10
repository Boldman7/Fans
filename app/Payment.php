<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Payment extends Model
{
    //
    protected $table = 'payments';

    protected $fillable = [
        'id', 'user_id', 'stripe_id', 'stripe_price_id', 'dashboard_url', 'stripe_customer_id', 'product_id', 'is_active', 'name', 'birthday', 'country', 'address', 'city', 'state', 'zip', 'gender',
        'billing_city', 'billing_address', 'billing_state', 'billing_zip', 'subscribe_content_confirm', 'sell_content_confirm'
    ];

    protected $hidden = [
        'stripe_id', 'price', 'stripe_price_id', 'stripe_customer_id', 'dashboard_url'
    ];
}
