<?php

if (!function_exists('format_number')) {
	function format_number($number)
	{
		if ($number < 10) {
			return '00' . $number;
		}
		if($number < 100) {
			return '0' . $number;
		}
		return $number;
	}
}
