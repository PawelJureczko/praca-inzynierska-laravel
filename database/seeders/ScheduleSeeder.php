<?php

namespace Database\Seeders;

use Carbon\Carbon;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class ScheduleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('schedule')->insert([
            'date_begin' => Carbon::create(2024, 3, 15),
            'date_end' => null,
            'classes_weekday' => 1,
            'classes_time_start' => '08:45',
            'classes_time_end' => '09:30',
            'teacher_id' => 1,
            'student_id' => 11,
            'created_at' => now(),
            'updated_at' => null,
        ]);

        DB::table('schedule')->insert([
            'date_begin' => Carbon::create(2024, 3, 12),
            'date_end' => null,
            'classes_weekday' => 1,
            'classes_time_start' => '11:45',
            'classes_time_end' => '12:15',
            'teacher_id' => 1,
            'student_id' => 13,
            'created_at' => now(),
            'updated_at' => null,
        ]);
        DB::table('schedule')->insert([
            'date_begin' => Carbon::create(2024, 1, 12),
            'date_end' => Carbon::create(2024, 2, 12),
            'classes_weekday' => 1,
            'classes_time_start' => '14:45',
            'classes_time_end' => '15:45',
            'teacher_id' => 1,
            'student_id' => 12,
            'created_at' => now(),
            'updated_at' => null,
        ]);
    }
}
