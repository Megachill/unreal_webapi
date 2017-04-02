<?php
/**
 * @author      Megachill
 * @date        31/03/2017
 * @file        UserTableSeeder.php
 * @copyright   MIT
 */

use Illuminate\Database\Seeder;

class UserTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('users')->insert([
            array(
                'username'  => 'robert',
                'email'     => 'robert@example.com',
                'password'  => app('hash')->make('password'),
            ),
            array(
                'username'  => 'susan',
                'email'     => 'susan@example.com',
                'password'  => app('hash')->make('password'),
            ),
        ]);
    }
}
