<?php

/**
 * This is a dummy class that looks like a data item,
 * acts like a data item, but does nothing at all.
 * It's purpose is to make the code a bit cleaner when
 * adding new items that may be entirely empty ... in which
 * case we don't want to clutter the data table with empty
 * stuff.
 */


class Data_Null {

	public function __construct()	{}


	public function valid()
	{
		return true;
	}


	public function __set($key, $value) {}


	public function __get($key)
	{
		return null;
	}


	public function save()
	{
		return true;
	}


	public function force_save()
	{
		return true;
	}

}