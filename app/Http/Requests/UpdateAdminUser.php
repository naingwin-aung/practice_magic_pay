<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateAdminUser extends FormRequest
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
        $id = $this->route('admin_user');

        return [
            'name' => 'required',
            'email' => 'required|email|unique:admins,email,' . $id,
            'phone' => ['required','numeric','regex:/^(09|\+?950?9|\+?95950?9)\d{7,9}$/','unique:admins,phone,' . $id],
            // 'phone' => 'required|digits_between:10,11|numeric|unique:admins,phone,' . $id,
            'password' => 'max:20',
        ];
    }
}
