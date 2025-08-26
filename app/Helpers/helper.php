<?php

// Helper functions for the application

// format the number of records 1000 to 1k
if (!function_exists('formatNumber')) {
    function formatNumber($number)
    {
        if ($number >= 1000) {
            return number_format($number / 1000, 1) . 'k';
        }
        return $number;
    }
}