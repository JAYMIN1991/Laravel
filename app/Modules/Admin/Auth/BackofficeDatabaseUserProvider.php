<?php
/**
 * Created by PhpStorm.
 * User: flinnt-php-6
 * Date: 10/10/16
 * Time: 5:43 PM
 */

namespace App\Modules\Admin\Auth;

use Illuminate\Support\Str;
use Illuminate\Contracts\Auth\Authenticatable;
use Illuminate\Contracts\Auth\UserProvider;
use Illuminate\Database\ConnectionInterface;
use Illuminate\Contracts\Hashing\Hasher as HasherContract;

/**
 * Class BackofficeDatabaseUserProvider
 *
 * @package App\Modules\Login\Providers
 */
class BackofficeDatabaseUserProvider implements UserProvider
{

	/**
	 * The active database connection.
	 *
	 * @var \Illuminate\Database\ConnectionInterface
	 */
	protected $conn;

	/**
	 * The hasher implementation.
	 *
	 * @var \Illuminate\Contracts\Hashing\Hasher
	 */
	protected $hasher;

	/**
	 * The table containing the users.
	 *
	 * @var string
	 */
	protected $table;

	/**
	 * Create a new database user provider.
	 *
	 * @param  \Illuminate\Database\ConnectionInterface $conn
	 * @param  \Illuminate\Contracts\Hashing\Hasher     $hasher
	 * @param  string                                   $table
	 */
	public function __construct( ConnectionInterface $conn, HasherContract $hasher, $table )
	{
		$this->conn = $conn;
		$this->table = $table;
		$this->hasher = $hasher;
	}


	/**
	 * @param mixed $identifier
	 *
	 * @return BackofficeGenericUser|null
	 */
	public function retrieveById( $identifier )
	{
		$user = $this->conn->table($this->table)->where("user_id", $identifier)->first();

		return $this->getGenericUser($user);
	}

	/**
	 * Get the generic user.
	 *
	 * @param  mixed $user
	 *
	 * @return BackofficeGenericUser|null
	 */
	protected function getGenericUser( $user )
	{
		if ( ! is_null($user) ) {
			return new BackofficeGenericUser((array) $user);
		}

		return null;
	}

	/**
	 * Retrieve a user by their unique identifier and "remember me" token.
	 *
	 * @param  mixed  $identifier
	 * @param  string $token
	 *
	 * @return \Illuminate\Contracts\Auth\Authenticatable|null
	 */
	public function retrieveByToken( $identifier, $token )
	{
		$user = $this->conn->table($this->table)->where('user_id', $identifier)//->where('remember_token', $token)
			->first();

		return $this->getGenericUser($user);
	}
	/*$this->conn->table($this->table)
			->where('id', $user->getAuthIdentifier())
			->update(['remember_token' => $token]);*/

	/**
	 * Update the "remember me" token for the given user in storage.
	 *
	 * @param  \Illuminate\Contracts\Auth\Authenticatable $user
	 * @param  string                                     $token
	 *
	 * @return void
	 */
	public function updateRememberToken( Authenticatable $user, $token )
	{
		// Empty body
	}

	/**
	 * Retrieve a user by the given credentials.
	 *
	 * @param  array $credentials
	 *
	 * @return \Illuminate\Contracts\Auth\Authenticatable|null
	 */
	public function retrieveByCredentials( array $credentials )
	{
		// First we will add each credential element to the query as a where clause.
		// Then we can execute the query and, if we found a user, return it in a
		// generic "user" object that will be utilized by the Guard instances.
		$query = $this->conn->table($this->table);

		foreach ( $credentials as $key => $value ) {
			if ( ! Str::contains($key, 'password') ) {
				$query->where($key, $value);
			}
		}

		// Now we are ready to execute the query to see if we have an user matching
		// the given credentials. If not, we will just return nulls and indicate
		// that there are no matching users for these given credential arrays.
		$user = $query->first();

		return $this->getGenericUser($user);
	}

	/**
	 * Validate a user against the given credentials.
	 *
	 * @param  \Illuminate\Contracts\Auth\Authenticatable $user
	 * @param  array                                      $credentials
	 *
	 * @return bool
	 */
	public function validateCredentials( Authenticatable $user, array $credentials )
	{
		$plain = $credentials['password'];

//		$plain = $credentials['user_password_v1'];

		return $this->hasher->check($plain, $user->getAuthPassword());
	}
}