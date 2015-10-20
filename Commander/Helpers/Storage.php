<?php namespace Commander\Helpers;

use InvalidArgumentException;

class Storage {

	/**
	 * Storage path
	 * @var string
	 */
	protected $storage;

	/**
	 * Separator type
	 * @var string
	 */
	protected $sep = DIRECTORY_SEPARATOR;

	public function __construct()
	{
	}

	/**
	 * Check the storage path
	 * 
	 * @return boolean
	 */
	public function isEnabled()
	{
		if($this->storage) {
			return true;
		}
		if(file_exists('composer.json') === true) {

			$composer = json_decode(file_get_contents('composer.json'), true);

			if(isset($composer['commander'])) {
				$this->storage = $composer['commander'];
				
				return true;
			}
		}

		throw new InvalidArgumentException("Storage path is not defined. Please set into composer.json with \"commander\" key", 1);
	}

	public function path($suffix ='' )
	{
		return $this->isEnabled() ? $this->storage.$this->sep.$suffix : '';
	}

	public function fileExists($filePath)
	{
		return file_exists($filePath);
	}

}