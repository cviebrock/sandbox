<?php

class Data extends Aware {

	public static $timestamps = true;

	public static $table = 'data';

	private static $class = null;

	public static $types = array();

	public static $rules = array(
		'user_id' => 'required|exists:users,id',
		'class'   => 'required',
		'type'    => 'required',
		'string'  => 'required',
	);


	// overload this in the extended class with all the fields we want to serialize
	// added benefit: the Aware bundle won't try and save these attributes into the DB

	public $temporary = array();


	// this is where we'll temporarily store the unserialized data

	protected $unserialized_data = array();


	public function __construct($attributes = array())
	{

		// automagically build the "type" rules based on the "types" attribute

		if (count(static::$types)) {
			static::$rules['type'] = array(
				'required',
				'in:' . join(',', array_keys(static::$types))
			);
		}

		parent::__construct($attributes);

	}



	/**
	 * A factory method that will automatically "cast" the data to the correct type.
	 */

	public static function factory()
	{

		$args = func_get_args();

		if (count($args)==0) {

			// no args?  must just want a copy of itself (not sure why)

			return new static;
		}

		// okay, we're trying to make something.  get the class type

		$classname = 'Data_'.ucfirst($args[0]);

		 if (count($args)==1) {
			// we're just creating a new class
			return new $classname;
		}

		// okay, so we're also passing data ... let's check it
		// if it's empty, return a Data_Null model instead of what they
		// really asked for

		$attributes = $args[1];

		// todo: check if $data isn't an array?

		if (count(array_filter($attributes))) {
			return new $classname($attributes);
		}

		return new Data_Null;

	}




	/**
	 * expand
	 * Converts the serialized data into regular attributes
	 *
	 * @param  string  $key
	 * @return mixed
	 */
	public function expand() {
		$this->unserialized_data = json_decode($this->string);
	}

	/**
	 * compact
	 * Converts the regular attributes into serialized data
	 *
	 * @param  string  $key
	 * @return mixed
	 */
	public function compact() {

		// update the dirty fields first
		// because of Aware, these will be in "ignore"

		foreach ($this->temporary as $key) {
			if( isset($this->ignore[$key]) ) {
				$this->unserialized_data[$key] = $this->ignore[$key];
			}
		}

		$this->string = json_encode($this->unserialized_data);
	}



	/**
	 * __get
	 * Overrides the parent method to look for serialized data first
	 *
	 * @param  string  $key
	 * @return mixed
	 */
	public function __get($key) {

		// expand if we haven't yet

		if ( !is_array($this->unserialized_data) ) {
			$this->expand();
		}

		// are we trying to get a key from one of the temporary attributes,
		// and does it exist?  if so, return it

		if ( in_array($key, $this->temporary) && array_key_exists($key, $this->unserialized_data) ) {
			return $this->unserialized_data[$key];
		}

		// else, default to parent method

		return parent::__get($key);

	}


	/**
	 * Save.
	 * Should automatically serialize the attributes into data.string.
	 *
	 * @param  int  $count
	 * @return array
	 */
	public function save($rules=array(), $messages=array())
	{

		// serialize the data and then save

		$this->compact();

		// force the class

		$this->class = static::$class;

		// force the type if it isn't required

		if (!count(static::$types)) {
			$this->type = '';
		}

		parent::save($rules, $messages);
	}


}
