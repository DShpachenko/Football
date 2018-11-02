<?php

class FootballException extends Exception
{
    private $key;

	public function __construct(int $key)
	{
		Exception::__construct('Command not found with specified key:  ' . $key);

		$this->key = $key;
	}

	public function getKey()
	{
		return $this->key;
	}
}