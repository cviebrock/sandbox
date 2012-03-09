<?php


class Data_Phone extends Data {


	// this is what will get saved in data.class

	protected static $class = 'phone';


	// these are the options for data.type

	public static $types = array(
		'work/voice'  => 'Work',
		'home/voice'  => 'Home',
		'cell/voice'  => 'Mobile',
		'work/fax'    => 'Work Fax',
		'home/fax'    => 'Home Fax',
		'pager'       => 'Pager',
		'other/voice' => 'Other',
	);


	// this are attributes that we'd like (for ease of use), but that get
	// serialized/unserialized when saving/loading

	public $temporary = array(
		'number',
		'extension',
	);


	// we can make rules and messages for these attributes too:

	public static $rules = array(
		'number'    => 'required|phone',
		'extension' => 'integer',
	);

	public static $messages = array(
		'number_phone'      => 'Phone numbers can start with a "+", and only contain 0-9, "-", "." or " "',
		'extension_integer' => 'Extensions can only contain numbers',
	);



}