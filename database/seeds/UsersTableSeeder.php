<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use App\User;

class UsersTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        User::truncate();

        /** @var User $objUser */
        $objUser = User::create([
            'name' => 'admin',
            'email' => 'mr.guillaume.rodrigues@gmail.com',
            'password' => Hash::make('admin'),
        ]);

        $objUser->generateToken();
    }
}
