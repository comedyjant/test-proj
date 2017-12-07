<?php

use App\Helpers\Helper;

if ( ! function_exists('duration_format')) {
  function duration_format($duration, $showSeconds = false){
    return Helper::duration_format($duration, $showSeconds);
  }
}

if ( ! function_exists('filter_tags')) { 
    function filter_tags($str) {
        return Helper::filter_tags($str);
    }
}

if ( ! function_exists('auto_p')) { 
    function auto_p($str, $p = TRUE, $br = TRUE) {
        return Helper::auto_p($str, $p, $br);
    }
}

if ( ! function_exists('clear_p')) { 
    function clear_p($str) {
        return Helper::clear_p($str);
    }
}