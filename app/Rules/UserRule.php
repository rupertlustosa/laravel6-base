<?php
/**
 * @package    Rules
 * @author     Rupert Brasil Lustosa <rupertlustosa@gmail.com>
 * @date       02/03/2020 19:01:44
 */

declare(strict_types=1);

namespace App\Rules;

class UserRule
{

    /**
     * Validation rules that apply to the request.
     *
     * @var array
     */
	protected static $rules = [
		'id' => 'required',
        'name' => 'required|min:2|max:255',
        'email' => 'required|email',
        'email_verified_at' => 'nullable|date_format:d/m/Y H:i',
        'password' => 'required|min:2|max:255',
        'remember_token' => 'nullable|min:2|max:100',
        'user_creator_id' => 'nullable',
        'user_updater_id' => 'nullable',
        'user_eraser_id' => 'nullable',
	];

    /**
     * Return default rules
     *
     * @return array
     */
    public static function rules()
    {

        return [
            'name' => self::$rules['name'],
            'email' => self::$rules['email'],
            'email_verified_at' => self::$rules['email_verified_at'],
            'password' => self::$rules['password'],
            'remember_token' => self::$rules['remember_token'],
            'user_creator_id' => self::$rules['user_creator_id'],
            'user_updater_id' => self::$rules['user_updater_id'],
            'user_eraser_id' => self::$rules['user_eraser_id'],
        ];
    }

    /**
     * Return default messages
     *
     * @return array
     */
    public static function messages()
    {

        return [];
    }
}
