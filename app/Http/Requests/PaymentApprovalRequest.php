<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class PaymentApprovalRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        // Authorization will be handled by policies/permissions in the controller
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
            // No specific rules needed for accept/reject actions, as the payment ID is passed via route.
            // You might add rules here if there were additional parameters for approval/rejection.
        ];
    }
}
