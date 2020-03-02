<?php
/**
 * @package    Requests
 * @author     Rupert Brasil Lustosa <rupertlustosa@gmail.com>
 * @date       09/12/2019 10:25:33
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

        return UserRule::rules();
    }

    public function onesignal()
    {

        return [
            'onesignal_user_id' => 'required',
        ];
    }

    public function rulesUpdatePassword()
    {
        $rulesDefault = UserRule::rules();

        //$rulesToUpdate['password'] = 'required|' . str_replace('confirmed', '', $rulesDefault['password']);
        $rulesToUpdate['password'] = str_replace('confirmed', '', $rulesDefault['password']);
        $rulesToUpdate['password_confirmation'] = 'required|same:password';

        return $rulesToUpdate;
    }

    public function rulesForRecoveryPassword()
    {
        $rulesDefault = UserRule::rules();

        $rulesToUpdate['email'] = str_replace('nullable', 'required', $rulesDefault['email']);

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
