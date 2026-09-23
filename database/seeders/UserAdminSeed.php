<?php

namespace Database\Seeders;

use App\Actions\Fortify\CreateNewUser;
use App\Enums\UserType;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class UserAdminSeed extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $input = [
            'uuid' => (string) Str::uuid(),
            'name' => 'Admin User',
            'email' => 'admin@example.com',
            'type' => UserType::ADMIN->value,
            'password' => 'password',
            'password_confirmation' => 'password',
        ];

        (new CreateNewUser)->create($input);
    }
}
