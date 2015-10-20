<?php namespace Commander\Helpers;

class Json {

	public static function valid($data)
	{
		json_decode($data);

		return json_last_error() == JSON_ERROR_NONE;
        
	}

	public static function decode($data)
	{
		return json_decode($data, true);
	}

}