<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class AdvertismentRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'type' => ['required'],
            'crypto_id' => ['required'],
            'gateway_id' => ['required'],
            'fiat_id' => ['required'],
            'price_type' => ['required'],
            'price' => ['required', 'numeric', 'min:0'],
            'payment_window_id' => ['required'],
            'minimum_limit' => ['required', 'numeric', 'min:0'],
            'maximum_limit' => ['required', 'numeric', 'min:0',],
        ];
    }
}
