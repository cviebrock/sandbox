<?php

class User extends Aware {

	public static $timestamps = true;

	public static $rules = array(
		'username'  => 'required|prestoh_username|unique:users|routesafe',
		'email'     => 'required|email',
		'password'  => 'required|min:6',
		'fullname'  => 'required',
		'firstname' => '',
		'lastname'  => '',
	);


	public static $messages = array(
		'email'              => 'A valid email address is required',
		'fullname_required'  => 'Your full name is required',
		'password_min'       => 'Minimum 6 characters',
		'username_routesafe' => 'That username has already been taken',
		'username_valid_username' => 'Usernames can contain letters, numbers, "-", "_" or "."',
	);



	/**
	 *	Define model relationships
	 */

	public function data()
	{
		return $this->has_many('Data');
	}


	/**
	 * Magic method to filter data values
	 * e.g.: $user->all_phones();
	 *       $user->all_addresses();
	 *
	 */
	public function __call($name, $arguments)
	{
		if (substr($name, 0, 4)=='all_') {
			$class = Str::singular(substr($name,4));
			$return = $this->data()->where('class','=',$class);

			if (count($arguments)) {
				$type = $arguments[0];
				$return = $return->where('type','=',$type);
			}

			return $return;
		}
		return parent::__call($name,$arguments);
	}


}
