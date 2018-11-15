<?php
/**
 * Created by PhpStorm.
 * User: aitspeko
 * Date: 14/11/2018
 * Time: 23:36
 */

class UsersTableSeeder extends \Illuminate\Database\Seeder
{
    public function run()
    {
        // Add Roles.
        \App\User::updateOrCreate([
            'name' => 'testuser',
            'email' => 'test@octopuslabs.com'
        ], ['auth_token' => 'TkpJe8qr9hjbqPwCHi0n']);
    }
}