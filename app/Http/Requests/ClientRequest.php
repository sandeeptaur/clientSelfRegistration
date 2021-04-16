<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ClientRequest extends FormRequest
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
            'name' => 'required|string|max:100',
            'address1' => 'required|string|max:255',
            'address2' => 'required|string|max:255',
            'country' => 'required|string|max:255',
            'state' => 'required|string|max:255',
            'city' => 'required|string|max:255',
            'zipCode' => 'required|min:5:max:7',
            'phoneNo1' => 'required|string|max:20',
            'phoneNo2' => 'required|string|max:20',
            'user.firstName' => 'required|string|max:50',
            'user.lastName' => 'required|string|max:50',
            'user.email' => 'required|max:150|unique:users,email',
            'user.phone' => 'required|string|max:20',
            'user.password' => 'required|confirmed|min:8',
        ];
    }
}
