<?php

namespace App\Http\Requests\User;

use App\Enum\PaymentStatus;
use App\Http\Requests\BaseFormApiRequest;
use Illuminate\Validation\Rule;

class UpdatePaymentStatus extends BaseFormApiRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'status' => ['required',Rule::in(PaymentStatus::getKeyListExcept(PaymentStatus::getPending()))],
            'order_id' => 'required|exists:orders,id,deleted_at,NULL',
        ];
    }
}
