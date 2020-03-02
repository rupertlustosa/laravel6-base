<?php
/**
 * @package    Requests
 * @author     Rupert Brasil Lustosa <rupertlustosa@gmail.com>
 * @date       02/03/2020 19:01:44
 */

declare(strict_types=1);

namespace App\Http\Requests;

use App\Rules\UserRule;
use Illuminate\Foundation\Http\FormRequest;

class UserUpdateRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {

        return $this->user()->can('update', $this->user);
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {

        $params = request()->route()->parameters();

        $rules = UserRule::rules();
        $rules['password'] = str_replace('required', 'nullable', $rules['password']);
        $rules['email'] = 'nullable|email|max:255|unique:users,email,' . request('user')->id;

        return $rules;
    }

    public function rulesUpdatePassword()
    {
        $rulesDefault = UserRule::rules();

        $rulesToUpdate['password'] = str_replace('confirmed', '', $rulesDefault['password']);
        $rulesToUpdate['password_confirmation'] = 'required|same:password';

        return $rulesToUpdate;
    }

    /**
     * Get the validation messages that apply to the request.
     *
     * @return array
     */
    public function messages()
    {

        return UserRule::messages();
    }
}
