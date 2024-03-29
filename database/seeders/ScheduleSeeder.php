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
            'date_begin' => Carbon::create(2024, 3, 17),
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
            'date_begin' => Carbon::create(2024, 3, 10),
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
            'date_begin' => Carbon::create(2024, 1, 14),
            'date_end' => Carbon::create(2024, 2, 11),
            'classes_weekday' => 1,
            'classes_time_start' => '14:45',
            'classes_time_end' => '15:45',
            'teacher_id' => 1,
            'student_id' => 12,
            'created_at' => now(),
            'updated_at' => null,
        ]);
        DB::table('lessons')->insert([
            'date'=> '2024-03-25',
            'schedule_id'=>1,
            'canceled_by_student'=>0,
            'canceled_by_teacher'=>0,
            'topic'=>'Przykładowy temat',
            'notes'=>'Przykładowa notatka',
            'created_at'=>Carbon::create(2024, 3, 25)
        ]);
    }
}
