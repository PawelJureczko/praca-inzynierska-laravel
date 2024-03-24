<?php
namespace App\Repositories;

use App\Models\Schedule;
use Illuminate\Support\Facades\DB;

class ScheduleRepository {
    public function checkIsNull($arr) {
        $errors = [];
        foreach ($arr as $key => $value) {
            if (is_null($value)) {
                $errors[$key] = "Te pole nie może być puste";
            }
        }

        return $errors;
    }
    public function validateTime($beginTime, $endTime) {
        $errors = [];

        $preparedBeginTime = intval(str_replace(':', '', $beginTime));
        $preparedEndTime = intval(str_replace(':', '', $endTime));

        if (($preparedBeginTime > $preparedEndTime) && !is_null($beginTime) && !is_null($endTime) ) {
            $errors['class_time_end'] = 'Godzina rozpoczęcia nie może być późniejsza niż zakończenia';
        }

        return $errors;
    }

    public function saveScheduleElem(array $formData, $teacher_id)
    {
        $dateBegin = $formData['date_begin'];
        $dateEnd = $formData['date_end'];
        $classWeekday = $formData['class_weekday'];
        $classTimeStart = $formData['class_time_start'];
        $classTimeEnd = $formData['class_time_end'];
        $studentId = $formData['student_id'];

        // Wykonanie zapytania SQL w celu wstawienia danych do bazy danych
        DB::table('schedule')->insert([
            'teacher_id' => $teacher_id,
            'date_begin' => $dateBegin,
            'date_end' => $dateEnd,
            'classes_weekday' => $classWeekday,
            'classes_time_start' => $classTimeStart,
            'classes_time_end' => $classTimeEnd,
            'student_id' => $studentId,
            // Dodaj inne pola formularza, które chcesz zapisać w bazie danych
            'created_at' => now(),
        ]);
    }
}
