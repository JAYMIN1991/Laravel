<?php
/**
 * Created by PhpStorm.
 * User: flinnt-php-8
 * Date: 16/3/17
 * Time: 7:03 PM
 */

namespace App\Common;

/**
 * Class APIRequestHandlerTrait
 * This trait will be used in request objects for API.
 *
 * @package App\Common
 */
trait  APIRequestHandlerTrait
{

	/**
	 * Return response for the invalid request
	 *
	 * @param array $errors
	 *
	 * @return \Symfony\Component\HttpFoundation\Response
	 */
	public function response( array $errors ) {

		$message = '';

		foreach ( $errors as $key => $value ) {
			foreach ( $value as $v ) {
				$message .= $v . '<br>';
			}
		}

		$responseErrors = [
			'status'  => 1,
			'message' => $message
		];

		return parent::response($responseErrors);
	}
}