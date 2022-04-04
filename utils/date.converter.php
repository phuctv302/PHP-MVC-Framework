<?php

namespace utils;

class DateConverter {
    public static function toDate($timestamp_value){
        return date('Y-m-d H:i:s', $timestamp_value);
    }

    public static function toTimestamp($date_value){
        return strtotime($date_value);
    }
}
