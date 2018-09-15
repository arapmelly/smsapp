<?php

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
        //

        DB::table('users')->delete();

        DB::table('users')->insert([
            'name' => 'Super Admin',
            'email' => 'admin@smsapp.co.ke',
            'password' => bcrypt('jacqstwo2010'),
            'user_type'=>'admin',
        ]);


        DB::table('users')->insert([
            'name' => 'Super Client',
            'email' => 'client@smsapp.co.ke',
            'password' => bcrypt('jacqstwo2010'),
            'user_type'=>'client',
        ]);
    }
}
