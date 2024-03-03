<?php

namespace App\Http\Middleware;

use Illuminate\Foundation\Http\Middleware\VerifyCsrfToken as Middleware;

class VerifyCsrfToken extends Middleware
{
    /**
     * The URIs that should be excluded from CSRF verification.
     *
     * @var array
     */
    protected $except = [
        '*users-inactive',
        '*users-active',
        'admin/service',
        '*plans-active',
        '*plans-inactive',

        '*sort-payment-methods',
        '*add-fund',
        'success',
        'failed',
        'payment/*',

        '*crypto-active',
        '*crypto-deactive',
        '*fiat-active',
        '*fiat-deactive',

        '*crypto-rate',
        '*fiat-rate',

        '*save-token',
        '*/sell/gateway/select',
        '*/sell/trade-request/fetch-info',
    ];
}
