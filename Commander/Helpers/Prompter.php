<?php namespace Commander\Helpers;

class Prompter {

	public function run(array $inputs, $message)
	{
		$fp = fopen("php://stdin", "r");
		$in = '';
		while($in != "quit") {
		    echo "$message?: ";
		    $in=trim(fgets($fp));
		    //eval ($in);
		    break;
		    echo "\n";
	    }

	    return $in;
	}

}