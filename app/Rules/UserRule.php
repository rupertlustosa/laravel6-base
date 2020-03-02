<?php
/**
 * @package    Rules
 * @author     Rupert Brasil Lustosa <rupertlustosa@gmail.com>
 * @date       09/12/2019 10:25:33
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
        'email' => 'nullable|email',
        'password' => 'required|string|min:8|confirmed',
        'image' => 'required|image|mimes:jpeg,png,jpg,gif,svg|max:3072',
        'imageUpdate' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:3072',

        'document_number' => 'required|formato_cpf|cpf|unique:users,document_number',

        'rg' => 'nullable|min:2|max:18',
        'gender' => 'nullable|in:MALE,FEMALE',
        'birth' => 'nullable|date_format:d/m/Y',
        'phone1' => 'nullable|min:2|max:15',
        'phone2' => 'nullable|min:2|max:15',
        'is_dev' => 'required',
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
            'password' => self::$rules['password'],
            'image' => self::$rules['image'],
            'document_number' => self::$rules['document_number'],
            'rg' => self::$rules['rg'],
            'gender' => self::$rules['gender'],
            'birth' => self::$rules['birth'],
            'phone1' => self::$rules['phone1'],
            'phone2' => self::$rules['phone2'],
        ];
    }

    /**
     * Return default messages
     *
     * @return array
     */
    public static function messages()
    {

        return [
            'name.required' => 'Campo nome é obrigatório',
            'name.min' => 'Campo nome deve conter pelo menos :min caracteres',
            'name.max' => 'Campo nome deve conter no máximo :max caracteres',

            'email.required' => 'Campo email é obrigatório',
            'email.email' => 'Campo email inválido',
            #'email.unique' => '',

            'password.required' => 'Campo senha é obrigatório',
            'password.min' => 'Campo senha deve conter pelo menos :min caracteres',
            'password.confirmed' => 'Campo confirmação de senha é obrigatório',

            'document_number.required' => 'Campo CPF é obrigatório',
            'document_number.formato_cpf' => 'Campo CPF não possui formato válido',
            'document_number.cpf' => 'Campo CPF é inválido',
            'document_number.unique' => 'CPF já cadastrado',

            'rg.required' => 'Campo RG é obrigatório',
            'rg.min' => 'Campo RG deve conter um mínimo de :min caracteres',
            'rg.max' => 'Campo RG deve ter um máximo de :max caracteres',

            'gender.required' => 'Campo gênero é obrigatório',
            'gender.in' => 'Campo gênero selecionado é inválido',

            'birth.required' => 'Campo data nascimento é obrigatório',
            'birth.date_format' => 'Campo data formato inválido',

            'phone1.required' => 'Campo telefone é obrigatório',
            'phone2.required' => 'Campo telefone é obrigatório',
        ];
    }
}
