<?php

namespace App\Helpers;

class TableHelper
{

	private static $_koef = [
		'100 m' => ['name' => '100 m', 'a' => 25.4347, 'b' => 18, 'c' => 1.81],
		'Long Jump' => ['name' => 'Long Jump', 'a' => 0.14354, 'b' => 220, 'c' => 1.4],
		'Shot put' => ['name' => 'Shot put', 'a' => 51.39, 'b' => 1.5, 'c' => 1.05],
		'High jump' => ['name' => 'High jump', 'a' => 0.8465, 'b' => 75, 'c' => 1.42],
		'400 m' => ['name' => '400 m', 'a' => 1.53775, 'b' => 82, 'c' => 1.81],
		'110 hurdles' => ['name' => '110 hurdles', 'a' => 5.74352, 'b' => 28.5, 'c' => 1.92],
		'Discus throw' => ['name' => 'Discus throw', 'a' => 12.91, 'b' => 4, 'c' => 1.1],
		'Pole vault' => ['name' => 'Pole vault', 'a' => 0.2797, 'b' => 100, 'c' => 1.35],
		'Javelin throw' => ['name' => 'Javelin throw', 'a' => 10.14, 'b' => 7, 'c' => 1.08],
		'1500 m' => ['name' => '1500 m', 'a' => 0.03768, 'b' => 480, 'c' => 1.85],
	];

	public static function getEventKoefTable()
	{
		return array_values(self::$_koef);
	}

	public static function getColumnsList()
	{
		return array_keys(self::$_koef);
	}

}