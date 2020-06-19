<?php
use App\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        User::create(
                [
                    'name'=>'admin',
                    'email'=>'admin@qq.com',
                    'password'=>Hash::make('admin123')
                ]
            );
    }
}
