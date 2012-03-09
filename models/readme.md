# Saving

```php
<?php

$user = new User;
$user->fill(array(
	'fullname' => Input::get('fullname'),
	'email'    => Input::get('email'),
	'password' => Input::get('password'),
));

$phone = Data::factory('phone', Input::get('phone') );


if ($user->valid() && $phone->valid()) {
	$user->save();
	$phone->user_id = $user->id;
	$phone->save();
}

```


# Retrieval


```php
<?php

$user = User::find($id);

$phones = $user->all_phones()->get();

$home_phone = $user->all_phones('home/voice')->first();

```