<?php

namespace utils;

// Convert time type
class DateConverter {

    // Convert timestamp => date
    public static function toDate($timestamp_value){
        return date('Y-m-d H:i:s', $timestamp_value);
    }

    // Convert date => timestamp
    public static function toTimestamp($date_value){
        return strtotime($date_value);
    }
}
