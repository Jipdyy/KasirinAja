<?php

namespace App\Http\Requests\Pos;

use App\Models\Transaction;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreTransactionRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return $this->user()->can('create', Transaction::class);
    }


    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'payment_method' => [
                'required',
                Rule::in([Transaction::PAYMENT_TUNAI, Transaction::PAYMENT_QRIS]),
            ],

            'cash_amount' => [
                'required_if:payment_method,' . Transaction::PAYMENT_TUNAI,
                'nullable',
                'integer',
                'min:0',
            ],
        ];
    }
}
