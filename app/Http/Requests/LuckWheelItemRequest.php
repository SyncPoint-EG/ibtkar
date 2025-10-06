<?php

namespace App\Http\Requests;

use App\Models\LuckWheelItem;
use Illuminate\Foundation\Http\FormRequest;

class LuckWheelItemRequest extends FormRequest
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
        $currentItemId = $this->route('luckWheelItem') ? $this->route('luckWheelItem')->id : null;

        return [
            'key' => 'required|string|max:255',
            'value' => 'nullable|string|max:255',
            'type' => 'required|string|in:points,nothing',
            'appearance_percentage' => [
                'required',
                'integer',
                'min:0',
                'max:100',
                function ($attribute, $value, $fail) use ($currentItemId) {
                    $totalPercentage = LuckWheelItem::when($currentItemId, function ($query) use ($currentItemId) {
                        return $query->where('id', '!=', $currentItemId);
                    })->sum('appearance_percentage');

                    if ($totalPercentage + $value > 100) {
                        $fail('The total appearance percentage cannot exceed 100.');
                    }
                },
            ],
        ];
    }
}
