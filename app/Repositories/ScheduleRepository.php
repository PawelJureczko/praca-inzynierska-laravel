<?php

namespace App\Repositories;

use App\Models\Schedule;
use Illuminate\Support\Facades\DB;

class ScheduleRepository
{
    public function checkIsNull($arr)
    {
        $errors = [];
        foreach ($arr as $key => $value) {
            if (is_null($value)) {
                $errors[$key] = "Te pole nie może być puste";
            }
        }

        return $errors;
    }

    public function validateTime($beginTime, $endTime)
    {
        $errors = [];

        $preparedBeginTime = intval(str_replace(':', '', $beginTime));
        $preparedEndTime = intval(str_replace(':', '', $endTime));

        if (($preparedBeginTime > $preparedEndTime) && !is_null($beginTime) && !is_null($endTime)) {
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

    private function getStudentName($studentId)
    {
        return DB::select("
                        SELECT first_name, last_name
                        FROM users WHERE id = $studentId");
    }

    private function getWeekDayName($key)
    {
        $weekdays = [
            '1' => 'poniedziałki',
            '2' => 'wtorki',
            '3' => 'środy',
            '4' => 'czwartki',
            '5' => 'piątki',
            '6' => 'soboty',
            '7' => 'niedziele',
        ];

        return $weekdays[$key];
    }

    public function validateSchedule($request, $teacherId)
    {
        $teacherId = $teacherId;
        $weekday = $request->input('class_weekday');
        $startDate = $request->input('date_begin');
        $endDate = $request->input('date_end');
        $startTime = $request->input('class_time_start');
        $endTime = $request->input('class_time_end');

        // Sprawdzenie, czy istnieją rekordy o podanych teacher_id i weekday
        $existingSchedules = DB::select("
        SELECT *
        FROM schedule
        WHERE teacher_id = :teacherId
        AND classes_weekday = :weekday
    ", [
            'teacherId' => $teacherId,
            'weekday' => $weekday
        ]);
        $errors = [];
//        dd($existingSchedules);
        foreach ($existingSchedules as $schedule) {
//            // Sprawdzenie zakresu dat
            if (($startDate >= $schedule->date_begin && $startDate <= $schedule->date_end) || (($endDate >= $schedule->date_begin && $endDate <= $schedule->date_end))) {
                // Sprawdzenie kolizji godzinowych
                if (($startTime >= $schedule->classes_time_start && $startTime <= $schedule->classes_time_end) ||
                    $endTime >= $schedule->classes_time_start && $endTime <= $schedule->classes_time_end
                ) {
                    $studentId = $schedule->student_id;
                    $userName = $this->getStudentName($studentId);
                    $firstName = $userName[0]->first_name;
                    $lastName = $userName[0]->last_name;
                    $weekday = $this->getWeekDayName($schedule->classes_weekday);
                    $timeRange = substr($schedule->classes_time_start, 0, 5) . ' - ' . substr($schedule->classes_time_end, 0, 5);

                    $errors['general'] = 'Nie można dodać zajęć w tym terminie. Kolidują one z zajęciami z ' . $firstName . ' ' . $lastName . ' odbywającymi się w ' . $weekday . ' ' . 'w godzinach ' . $timeRange . '.';
//                    // Jeśli kolizja występuje, zwracamy komunikat walidacyjny
                    return $errors;
                }
            }
        }
        return $errors;
    }

    public function getLessonsForCurrentDates($request)
    {
        $teacherId = $request->user()->id;
        $dateFrom = $request->input('dateFrom');
        $dateTo = $request->input('dateTo');


        return DB::select("
            SELECT *
            FROM schedule
            WHERE teacher_id = ?
            AND (
                (date_end IS NULL AND date_begin <= ? AND date_begin <= ?)
                OR
                (date_end IS NOT NULL AND date_begin <= ? AND date_begin <= ? AND date_end >= ? AND date_end >= ?)
            )
        ", [
            $teacherId, $dateFrom, $dateTo, $dateFrom, $dateTo, $dateFrom, $dateTo
        ]);
    }
}
