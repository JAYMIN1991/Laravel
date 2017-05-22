<?php
/**
 * Created by PhpStorm.
 * User: flinnt-php-6
 * Date: 10/10/16
 * Time: 5:36 PM
 */
namespace App\Modules\Admin\Auth;

use Illuminate\Contracts\Auth\Authenticatable;
use Tymon\JWTAuth\Contracts\JWTSubject;

/**
 * Class BackofficeGenericUser
 *
 * @package App\Modules\Login\Helper
 */
class BackofficeGenericUser implements Authenticatable, JWTSubject {


	/**
	 * @var
	 */
	protected $attributes;

	/**
	 * BackofficeGenericUser constructor.
	 * @param $attributes
	 */
	public function __construct($attributes) {
		$this->attributes = $attributes;
	}


	/**
	 * Get the name of the unique identifier for the user.
	 *
	 * @return string
	 */
	public function getAuthIdentifierName() {
		return "user_id";
	}

	/**
	 * Get the unique identifier for the user.
	 *
	 * @return mixed
	 */
	public function getAuthIdentifier() {
		$name = $this->getAuthIdentifierName();

		return $this->attributes[$name];
	}

	/**
	 * Get the password for the user.
	 *
	 * @return string
	 */
	public function getAuthPassword() {
		return $this->attributes['user_password_v1'];
	}

	/**
	 * Get the token value for the "remember me" session.
	 *
	 * @return string
	 */
	public function getRememberToken() {
		return "";
	}

	/**
	 * Set the token value for the "remember me" session.
	 *
	 * @param  string $value
	 * @return void
	 */
	public function setRememberToken($value) {
		// Empty Body
	}

	/**
	 * Get the column name for the "remember me" token.
	 *
	 * @return string
	 */
	public function getRememberTokenName() {
		return "";
	}

	/**
	 * Dynamically access the user's attributes.
	 *
	 * @param  string  $key
	 * @return mixed
	 */
	public function __get($key)
	{
		return $this->attributes[$key];
	}

	/**
	 * Dynamically set an attribute on the user.
	 *
	 * @param  string  $key
	 * @param  mixed  $value
	 * @return void
	 */
	public function __set($key, $value)
	{
		$this->attributes[$key] = $value;
	}

	/**
	 * Dynamically check if a value is set on the user.
	 *
	 * @param  string  $key
	 * @return bool
	 */
	public function __isset($key)
	{
		return isset($this->attributes[$key]);
	}

	/**
	 * Dynamically unset a value on the user.
	 *
	 * @param  string  $key
	 * @return void
	 */
	public function __unset($key)
	{
		unset($this->attributes[$key]);
	}

	/**
	 * Get the identifier that will be stored in the subject claim of the JWT.
	 *
	 * @return mixed
	 */
	public function getJWTIdentifier() {
		$name = $this->getAuthIdentifierName();

		return $this->attributes[$name];
	}

	/**
	 * Return a key value array, containing any custom claims to be added to the JWT.
	 *
	 * @return array
	 */
	public function getJWTCustomClaims() {
		return [];
	}
}