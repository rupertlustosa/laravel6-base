<?php

namespace App\Http\Requests;

use App\Rules\UserRule;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;

class UserProfileRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return $this->user()->can('profile', Auth::user());
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        $rules = UserRule::rules();
        $user = Auth::user();
        //$rules['image'] = str_replace('required', 'nullable', $rules['image']);
        $rules['password'] = str_replace('required', 'nullable', $rules['password']);
        $rules['email'] = str_replace('unique:users', Rule::unique('users')->ignore($user->id), $rules['email']);
        return $rules;

    }


    public function messages()
    {
        return UserRule::messages();
    }
}
