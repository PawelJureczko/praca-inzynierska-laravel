<?php
namespace App\Repositories;

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
}
