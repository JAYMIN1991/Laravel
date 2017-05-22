<?php
/**
 * Created by PhpStorm.
 * User: flinnt-php-6
 * Date: 8/2/17
 * Time: 11:40 AM
 */

namespace App\Common;

use Illuminate\Support\Str;
use Request;
use Response;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;

/**
 * Class APIResponseHandlerTrait
 * This trait will be used to send the response from API.
 * Call the $this->sendResponse() method to send the response from API
 *
 * @package App\Common
 */
trait APIResponseHandlerTrait {

	/**
	 * @param       $data
	 * @param       $status
	 * @param array $headers
	 * @param int   $options
	 *
	 * @return \Illuminate\Http\JsonResponse
	 */
	protected function sendResponse(array $data, $status = 200, $headers = [], $options = 0) {
		if (!array_key_exists('status', $data))
		{
			$data['status'] = 0;
			$status = 400;
		}

		switch ($this->checkAcceptHeader()) {
			case 'json':
				return Response::json($data, $status, $headers, $options);
			case 'xml':
				//@Todo :: Write logic to convert response array to XML
				return null;
			default:
				throw new BadRequestHttpException(trans('exception.bad_header.message'), null, trans('exception.bad_header.code'));
		}
	}

	/**
	 * @return string
	 */
	private function checkAcceptHeader() {
		if (Request::expectsJson()) {
			return 'json';
		} elseif ($this->expectsXML()) {
			return 'xml';
		}

		return null;
	}

	/**
	 * @return bool
	 */
	private function expectsXML() {
		$acceptable = Request::getAcceptableContentTypes();

		return isset($acceptable[0]) && Str::contains($acceptable[0], ['/xml', '+xml']);
	}
}