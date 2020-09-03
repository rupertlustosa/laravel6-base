<?php

use App\Models\Type;
use App\Models\User;
use Illuminate\Database\Seeder;

class UsersTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $users = [
            [
                "name" => "Rupert Lustosa",
                "email" => "rupertlustosa@gmail.com",
            ]
        ];

        $password = Hash::make('12345678');

        foreach ($users as $item) {
            $item['password'] = $password;
            $user = User::create($item);
            //$user->types()->attach($user->is_dev ? [Type::ADMIN, Type::CLIENT] : Type::CLIENT);
        }
    }
}
