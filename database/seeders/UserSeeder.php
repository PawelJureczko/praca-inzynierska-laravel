<?php

namespace Database\Seeders;

use Carbon\Carbon;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // Seed 10 admin users
        for ($i = 1; $i <= 10; $i++) {
            DB::table('users')->insert([
                'first_name' => 'TeacherName'.$i,
                'last_name' => 'TeacherSurname'.$i,
                'email' => 'teacher'.$i.'@example.com',
                'password' => Hash::make('password'),
                'role' => 'teacher',
                'phone_number' => '123456789',
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }

        // Seed 10 regular users
        for ($i = 1; $i <= 10; $i++) {
            DB::table('users')->insert([
                'first_name' => 'StudentName'.$i,
                'last_name' => 'StudentSurname'.$i,
                'email' => 'student'.$i.'@example.com',
                'password' => Hash::make('password'),
                'role' => 'student',
                'phone_number' => '987654321',
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }
        for ($i = 1; $i <= 10; $i++) {
            DB::table('messages')->insert([
                'sender_id' => 1,
                'receiver_id' => 10+$i,
                'content' => 'test '.$i,
                'read_at' => null,
                'created_at' => Carbon::now()->subMinutes($i),
                'updated_at' => null,
            ]);
            DB::table('messages')->insert([
                'sender_id' => 1,
                'receiver_id' => 10+$i,
                'content' => 'test starej wiadomosci'.$i,
                'read_at' => null,
                'created_at' => Carbon::now()->subHours($i),
                'updated_at' => null,
            ]);
            DB::table('messages')->insert([
                'sender_id' => $i+10,
                'receiver_id' => $i,
                'content' => 'test innej wiadomosci'.$i,
                'read_at' => null,
                'created_at' => Carbon::now()->subMinutes($i+30),
                'updated_at' => null,
            ]);
            DB::table('messages')->insert([
                'sender_id' => $i+10,
                'receiver_id' => $i,
                'content' => 'test innej wiadomosci starej'.$i,
                'read_at' => null,
                'created_at' => Carbon::now()->subMinutes($i+50),
                'updated_at' => null,
            ]);
        }
    }
}
