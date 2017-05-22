<?php
/**
 * Created by PhpStorm.
 * User: flinnt-php-6
 * Date: 9/2/17
 * Time: 3:06 PM
 */

namespace App\Common;


/**
 * Class ErrorCodes
 * @package App\Common
 */
class ErrorCodes {

	/**
	 * Success request
	 */
	const HTTP_OK = 200;

	/**
	 * Bad Request
	 */
	const HTTP_BAD_REQUEST = 400;

	/**
	 * Unauthorized
	 */
	const HTTP_UNAUTHORIZED = 401;

	/**
	 * Forbidden
	 */
	const HTTP_FORBIDDEN = 403;

	/**
	 * Server Error
	 */
	const HTTP_SERVER_ERROR = 557;

	/**
	 * Conflict
	 */
	const HTTP_CONFLICT = 409;
}