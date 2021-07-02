<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ConfirmTransferRequest extends FormRequest
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
            'to_phone' => ['required','numeric','regex:/^(09|\+?950?9|\+?95950?9)\d{7,9}$/'],
            'amount' => 'required|integer|min:1000|max:1000000000000000',
        ];
    }

    public function messages()
    {
        return [
            'to_phone.required' => 'ငွေလွှဲလိုသော ဖုန်းနံပါတ်ထည့်ရန်လိုအပ်သည်။',
            'to_phone.regex' => 'ငွေလွှဲလိုသောဖုန်းနံပါတ်သည် ပြည်တွင်းဖုန်းနံပါတ်ဖြစ်ရမည်။',
            'amount.required' => 'ငွေလွှဲလိုသော ပမာဏထည့်ရန်လိုအပ်သည်။',
            'amount.min' => '၁၀၀၀ကျပ် အထက်လွှဲရန်လိုအပ်သည်။',
            'amount.integer' => 'ငွေပမာဏ တန်ဖိုးဖြစ်ရမည်။'
        ];
    }
}
