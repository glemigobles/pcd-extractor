<?php

use Illuminate\Database\Seeder;
use App\User;
use Illuminate\Support\Facades\Hash;

class AdminSeed extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $user=User::create([
            'firstname' => 'admin',
            'lastname' => 'admin',
            'email' => 'dev@bitfinity.eu',
            'password' => Hash::make('kuba123'),
        ]);
        $user->assignRole('administrator');
        $user=User::create([
            'firstname' => 'mus',
            'lastname' => 'mus',
            'email' => 'contact@musdesigns.com',
            'password' => Hash::make('mus123'),
        ]);
        $user->assignRole('administrator');

    }
}
