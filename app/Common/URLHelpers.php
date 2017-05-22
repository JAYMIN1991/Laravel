<?php
/**
 * Created by PhpStorm.
 * User: flinnt-php-6
 * Date: 26/12/16
 * Time: 3:51 PM
 */

namespace App\Common;

use App;
use Session;

/**
 * Class URLHelpers
 * @package App\Common
 */
class URLHelpers {

	/**
	 * Generate the user verification url from given verification id
	 *
	 * @param  mixed $verificationId Verification ID of the user
	 * @param string $email          Email Id of the user
	 *
	 * @return string
	 */
	public static function generateUserVerificationURL( $verificationId, $email = '' ) {
		// to add some randomization in generated url, this parameter is not used while verifying account
		$randomCode = GeneralHelpers::createRandomValue(15, 'mixed');
		$randomQuery = '&vt=' . self::encodeGetParam($randomCode);

		$emailQuery = '';
		if ( ! empty($email) ) {
			$emailQuery = '&mail=' . self::encodeGetParam($email);
		}

		$deviceQuery = '';
		if ( isset($app) && property_exists($app, 'device-type') ) {
			$deviceQuery = '&src=' . self::encodeGetParam($app->device_type); //TODO:: check how to get device_type
		}

		return HTTP_SERVER_CATALOG . 'app/verify-account/?rq=' . self::encodeGetParam($verificationId) . $randomQuery . $emailQuery . $deviceQuery;
	}

	/**
	 * Encode the get parameters
	 *
	 * @param mixed $value Parameter to be encoded
	 *
	 * @return string Encoded parameter
	 */
	public static function encodeGetParam( $value ) {
		$numbers = range(0, 9);
		shuffle($numbers);
		$num = '';
		$num2 = '';
		for ( $i = 0 ; $i < 4 ; $i++ ) {
			$num .= $numbers[$i] . '';
			shuffle($numbers);
			$num2 .= $numbers[$i] . '';
		}
		$out = $num . (string) $value . $num2;
		$out = strrev(str_rot13($out));
		$out = base64_encode($out);

		return $out;
	}

	/**
	 * Decode the get parameter
	 *
	 * @param string $value Encoded parameter
	 *
	 * @return mixed|string plaintext parameter
	 */
	public static function decodeGetParam( $value ) {
		$out = base64_decode($value);
		$out = str_rot13(strrev($out));
		$out = str_replace(substr($out, 0, 4), '', str_replace(substr($out, -4, 4), '', $out));

		return $out;
	}

	/**
	 * Generate preview link url of given content_id
	 *
	 * @param string $previewURL preview url
	 * @param int    $contentId  Id of the content
	 *
	 * @return string Return url of the preview link
	 */
	public static function getLMSLinkPreviewURL( $previewURL, $contentId ) {
		$paramContentId = $contentId;
		if ( ctype_digit($contentId) || is_numeric($contentId) ) {
			$paramContentId = self::encodeGetParam($contentId);
		}
		$paramURL = $previewURL;
		if ( strpos($previewURL, '%') === false ) {
			$paramURL = self::encodeGetParam($previewURL);
		}

		return HTTP_SERVER_CATALOG . 'app/external_link/?' . 'rl=' . $paramURL . '&vt=' . self::encodeGetParam('CONTENT') . '&vl=' . $paramContentId;
	}

	/**
	 * Generate preview link url of given document
	 *
	 * @param string $filename name of the file
	 *
	 * @return string Return url of the preview link
	 */
	public static function getLMSDocPreviewURL( $filename ) {
		return HTTP_SERVER_CATALOG . 'app/download/?file=' . $filename . '&dsrc=' . self::encodeGetParam('backoffice') . '&tgt=' . self::encodeGetParam('s3') . '&m=' . self::encodeGetParam('content') . '&u=' . self::encodeGetParam(Session::get('user_id'));
	}

	/**
	 * Generate href url of api
	 *
	 * @param string $version       version information
	 * @param string $URL           part of the url
	 * @param string $route         route name
	 * @param array  ...$parameters extra parameters
	 *
	 * @return string Return url of api
	 */
	public static function getAPIURL( $version, $URL, $route = 'app', ...$parameters ) {
		$url = HTTP_API . '/' . $route . '/' . $version . '/' . $URL . '/';

		if ( count($parameters) > 0 ) {
			$url = vsprintf($url, $parameters);
		}

		return $url;
	}

	/**
	 * Get course picture url
	 *
	 * @param string $coursePicture picture of the course
	 * @param string $pictureSize   size of the picture
	 * @param bool   $relativeURL   should return relive url or not. default is false
	 *
	 * @return string Return course picture url
	 */
	public static function getCoursePictureURL( $coursePicture, $pictureSize, $relativeURL = false ) {
		$folder = '';
		switch ( $pictureSize ) {
			case COURSE_IMAGE_LARGE:
				$folder = DIR_WS_RESOURCES_COURSES . DIR_WS_COURSE_LARGE;
				break;
			case COURSE_IMAGE_MEDIUM:
				$folder = DIR_WS_RESOURCES_COURSES . DIR_WS_COURSE_MEDIUM;
				break;
			case COURSE_IMAGE_SMALL:
				$folder = DIR_WS_RESOURCES_COURSES . DIR_WS_COURSE_SMALL;
				break;
			case COURSE_IMAGE_XLARGE:
				$folder = DIR_WS_RESOURCES_COURSES . DIR_WS_COURSE_XLARGE;
				break;
		}

		if ( GeneralHelpers::isNull($coursePicture) ) {

			return (! $relativeURL ? HTTP_RESOURCE . DIR_WS_RESOURCE_CATALOG : '') . $folder . 'default.png';
		} else {

			return (! $relativeURL ? HTTP_RESOURCE . DIR_WS_RESOURCE_CATALOG : '') . $folder . $coursePicture;
		}
	}

	/**
	 * get user picture url
	 *
	 * @param string $userPicture picture of the user
	 * @param string $pictureSize size of the picture
	 * @param bool   $relativeURL should return relive url or not. default is false
	 * @param bool   $sidebar     is the image for sidebar. default is false
	 *
	 * @return string Return user picture url
	 */
	public static function getUserPictureURL( $userPicture, $pictureSize, $relativeURL = false, $sidebar = false ) {
		$folder = '';
		switch ( $pictureSize ) {
			case USER_PICTURE_LARGE:
				$folder = DIR_WS_RESOURCES_PROFILE_IMAGE . DIR_WS_PROFILE_LARGE;
				break;
			case USER_PICTURE_MEDIUM:
				$folder = DIR_WS_RESOURCES_PROFILE_IMAGE . DIR_WS_PROFILE_MEDIUM;
				break;
			case USER_PICTURE_SMALL:
				$folder = DIR_WS_RESOURCES_PROFILE_IMAGE . DIR_WS_PROFILE_SMALL;
				break;
		}

		if ( $sidebar && $pictureSize == USER_PICTURE_LARGE ) {
			$defaultImage = 'blank-headshot-square.png';
		} else {
			$defaultImage = 'blank-headshot.png';
		}

		if ( GeneralHelpers::isNull($userPicture) ) {
			return (! $relativeURL ? HTTP_RESOURCE . DIR_WS_RESOURCE_CATALOG : '') . $folder . ($pictureSize == USER_PICTURE_LARGE ? $defaultImage : 'default.png');
		} else {
			return (! $relativeURL ? HTTP_RESOURCE . DIR_WS_RESOURCE_CATALOG : '') . $folder . $userPicture;
		}
	}

	/**
	 * Check if provided url is valid for youtube
	 *
	 * @param  string $url URL for validation
	 *
	 * @return bool Return true for valid youtube url, false otherwise
	 */
	public static function isYoutubeURL( $url ) {
		$out = false;

		if ( GeneralHelpers::isNull($url) ) {
			return $out;
		} else if ( preg_match('/^(?:http|https)+(?:\:\/\/)(?:www\.)?(?:youtube.com|youtu.be)\/(?:watch\?(?=.*v=([\w\-]+))(?:\S+)?|([\w\-]+))$/i', $url, $matches) ) {
			$matches = array_filter($matches, function ( $var ) {
				return ($var !== '');
			});

			if ( sizeof($matches) == 2 ) {
				$out = true;
			}
		}

		return $out;
	}
}