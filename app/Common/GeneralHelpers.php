<?php
/**
 * Created by PhpStorm.
 * User: flinnt-php-6
 * Date: 26/12/16
 * Time: 3:57 PM
 */

namespace App\Common;

use App;
use Excel;
use Exception;
use File;
use GuzzleHttp;
use GuzzleHttp\Exception\ClientException;
use GuzzleHttp\Exception\ServerException;
use Helper;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;
use Illuminate\Support\Str;
use JWTAuth;
use JWTFactory;
use Log;
use Psr\Http\Message\ResponseInterface;
use Session;
use Symfony\Component\Process\Process;

/**
 * Class GeneralHelpers
 * @package App\Common
 */
class GeneralHelpers {

	/**
	 * Create Random value based on specified type and length
	 *
	 * @param int    $length Length of the random value
	 * @param string $type   Type of the value i.e mixed, digits, chars
	 *
	 * @return bool|string Random value based on provided configurations
	 */
	public static function createRandomValue( $length, $type = "mixed" ) {

		if ( ($type != 'mixed') && ($type != 'chars') && ($type != 'digits') ) {
			return false;
		}

		$rand_value = '';
		while ( strlen($rand_value) < $length ) {
			if ( $type == 'digits' ) {
				$char = self::randNumber(0, 9);
			} else {
				$char = chr(self::randNumber(0, 255));
			}

			if ( $type == 'mixed' ) {
				if ( preg_match('/^[a-z0-9]$/', $char) ) {
					$rand_value .= $char;
				}
			} elseif ( $type == 'chars' ) {
				if ( preg_match('/^[a-z]$/', $char) ) {
					$rand_value .= $char;
				}
			} elseif ( $type == 'digits' ) {
				if ( preg_match('/^[0-9]$/', $char) ) {
					$rand_value .= $char;
				}
			}
		}

		return $rand_value;
	}

	/**
	 * Get the random number between min and max
	 *
	 * @param int|null $min Minimum number
	 * @param int|null $max Maximum number
	 *
	 * @return int|null Random number between min and max
	 */
	public static function randNumber( $min = null, $max = null ) {
		static $seeded;

		if ( ! $seeded ) {
			mt_srand((double) microtime() * 1000000);
			$seeded = true;
		}

		if ( isset($min) && isset($max) ) {
			if ( $min >= $max ) {
				return $min;
			} else {
				return mt_rand($min, $max);
			}
		} else {
			return mt_rand();
		}
	}

	/**
	 * Call the public site APIs
	 *
	 * @param string $url        Public site url
	 * @param string $type       Type of request
	 * @param array  $parameters Body/Query parameters of request
	 * @param bool   $isBackoffice Is call for backoffice, then add backoffice prefix in url, add app prefix otherwise
	 * @param string $version    Version of public API
	 * @param array  $headers    Custom headers
	 *
	 * @return mixed|\Psr\Http\Message\ResponseInterface Returns the response of API call
	 * @throws \Exception
	 */
	public static function callAPI( $url, $type = 'POST', array $parameters = [], $isBackoffice = true, $version = API_VERSION_1_0,
	                                array $headers = [] ) {
		$methods = ['GET', 'POST', 'PUT', 'DELETE']; // Default request methods

		// Default headers
		$defaultHeader = [
			'User-Agent'   => 'backoffice',
			'Content-Type' => 'application/json',
			'Accept'       => 'application/json'
		];
		$requestUrl = self::getPublicAPIURL($url, $isBackoffice, $version);
		$requestType = Str::upper($type);

		// If request method is not defined in default methods it will throw an error
		if ( ! in_array($requestType, $methods) ) {
			throw new Exception(trans('shared::message.error.invalid_request_type'));
		}

		// If url is not valid url it will throw an error
		if ( ! filter_var($requestUrl, FILTER_VALIDATE_URL) ) {
			throw new Exception(trans('shared::message.error.invalid_url'));
		}

		/**
		 * If request method is get, add parameters to 'query' key of request parameters,
		 * otherwise convert it to json and add to request parameters
		 */
		if ( $requestType == "GET" ) {
			$requestParams = ['query' => $parameters];
		} else {
			$requestParams = ['body' => GuzzleHttp\json_encode($parameters)];
		}

		$defaultHeader = array_merge($defaultHeader, $headers);
		$client = new GuzzleHttp\Client(['headers' => $defaultHeader, 'verify' => false]);

		// Logging the request
		self::logAPICall($requestUrl, $requestParams);
		try {
			// Sending the request
			$response = $client->request($requestType, $requestUrl, $requestParams);
		} catch ( ClientException $e ) {
			Log::error($e->getResponse()->getBody(), $e->getTrace());
			throw $e;
		} catch ( ServerException $e ) {
			Log::error($e->getResponse()->getBody(), $e->getTrace());
			throw $e;
		}

		// Logging the request and response
		self::logAPICall($requestUrl, $requestParams, $response);

		return $response;
	}

	/**
	 * Get the JWT token for the user
	 *
	 * @param int $subId Id of the subject/User
	 *
	 * @return mixed Token for given user id
	 * @throws \Exception
	 */
	public static function getJWTToken( $subId = null ) {
		$payload = null;
		if ( ! is_null($subId) ) {
			// Create the jwt payload for provided subject id
			$payload = JWTFactory::sub($subId)->make();
		} else {
			// If subject/user id is not provided, create the payload of logged in user
			$id = Session::get('user_id');
			if ( $id ) {
				$payload = JWTFactory::sub($id)->make();
			} else {
				throw new Exception(trans('shared::message.error.not_logged_in'));
			}
		}

		// Encode the payload
		return JWTAuth::encode($payload);
	}

	/**
	 * Encrypt the payload
	 *
	 * @param mixed       $payload  Plain text payload
	 * @param string|null $password Password to encode string
	 *
	 * @return string Encrypted payload
	 */
	public static function encode( $payload, $password = null ) {

		if ( is_null($password) ) {
			$password = config('app.id-cipher');
		}

		$encryptedText = \openssl_encrypt($payload, 'rc2-ecb', $password);
		$encryptedText = str_replace('/', '_', $encryptedText);

		return $encryptedText;
	}

	/**
	 * Decrypt the payload
	 *
	 * @param mixed       $payload  Encrypted payload
	 * @param string|null $password Password to encode plain text
	 *
	 * @return string Plain text payload
	 */
	public static function decode( $payload, $password = null ) {

		if ( is_null($password) ) {
			$password = config('app.id-cipher');
		}

		$text = str_replace('_', '/', $payload);

		return \openssl_decrypt($text, 'rc2-ecb', $password);
	}

	/**
	 * Export data to excel format
	 * Currently not support multiple sheet generation
	 *
	 * @param array $columnNames Name of the columns in key-value format,
	 *                           Must contains all the keys present in the data array,
	 *                           value will be the title of column
	 * @param array $data        Associative array of data. Keys of the array must be in the columnNames array
	 * @param null  $filename    Name of the file
	 * @param null  $sheetName   Name of the sheet
	 */
	public static function exportToExcel( array $columnNames, array $data, $filename = null, $sheetName = null ) {
		$excelData = $data;

		// If filename is empty, generate the unique file name other wise attache the timestamp to given file name
		if ( empty($filename) ) {
			$filename = 'Flinnt-' . uniqid() . Helper::datetimeToTimestamp();
		} else {
			$filename = 'Flinnt-' . strtolower($filename) . '-' . Helper::datetimeToTimestamp();
		}

		// Create a data array with the columns specified in the columnNames array
		array_walk($excelData, function ( &$item, $key, $columnNames ) {
			$item = array_values(array_merge($columnNames, array_intersect_key($item, $columnNames)));
		}, $columnNames);

		Excel::create($filename, function ( $excel ) use ( $columnNames, $excelData, $sheetName ) {
			if ( empty($sheetName) ) {
				$sheetName = 'Sheet 1';
			}

			$excel->sheet($sheetName, function ( $sheet ) use ( $columnNames, $excelData ) {
				$sheet->fromArray($excelData, null, 'A1', false, false);
				$sheet->prependRow(array_values($columnNames));
			});
		})->export('xls');
	}

	/**
	 * Get the public api url based on version and route
	 *
	 * @param string $route   URI of the route
	 * @param bool   $isBackoffice Is call for backoffice, then add backoffice prefix in url, add app prefix otherwise
	 * @param string $version API version
	 *
	 * @return string URL of the public API
	 * @throws \Exception
	 */
	private static function getPublicAPIURL( $route, $isBackoffice = true, $version = API_VERSION_1_0 ) {
		$baseURL = 'API_ENDPOINT_' . Str::upper(App::environment());

		if ( strpos(trim($version), '/') === 0 ) {
			$versionPath = substr(trim($version), 1);
		} else {
			$versionPath = trim($version);
		}

		if ( strpos(trim($route), '/') === 0 ) {
			$routePath = substr(trim($route), 1);
		} else {
			$routePath = trim($route);
		}

		$prefix = ($isBackoffice) ? 'backoffice' : 'app';
		// Building the url for public api
		try {
			$url = constant($baseURL) . "/${prefix}/${versionPath}/${routePath}/";
		} catch ( Exception $e ) {
			Log::error($e->getMessage(), $e->getTrace());

			throw $e;
		}

		$url = trim($url, '/') . '/';

		return $url;
	}

	/**
	 * Log the api call
	 *
	 * @param string                              $route         URL of the request
	 * @param array                               $requestParams Request parameter or body of the request
	 * @param \Psr\Http\Message\ResponseInterface $response      Response of the parameter
	 *
	 * @return void
	 */
	private static function logAPICall( $route, $requestParams, ResponseInterface $response = null ) {
		$context['request'] = $requestParams;
		if ( $response ) {
			$context['response'] = $response->getBody();
		}
		Log::info('API CALL LOG: ' . $route, $context);
	}

	/**
	 * @param      $param
	 * @param      $type
	 * @param null $default
	 *
	 * @return int|mixed|null|string
	 */
	public static function clearParam( $param, $type, $default = NULL ) {
		switch ( $type ) {
			case PARAM_RAW:
				// No cleaning at all.
				//$param = syn_fix_utf8($param);
				return $param;

			case PARAM_RAW_TRIMMED:
				// No cleaning, but strip leading and trailing whitespace.
				//$param = syn_fix_utf8($param);
				return trim($param);

			/*case PARAM_ENC_RAW:
				if(empty($param))
					return $default;

				$param = trim(syn_fix_utf8($param));
				if(!is_null(app_clear_param($param, PARAM_BASE64)))
					return _D($param);
				else
					return $default;*/

			/*case PARAM_ENC_INT:
				if(empty($param))
					return $default;

				$param = trim(syn_fix_utf8($param));
				if(!is_null(app_clear_param($param, PARAM_BASE64)))
					return app_clear_param(_D($param), PARAM_INT);
				else
					return $default;*/

			case PARAM_TAG:
				//$param = syn_fix_utf8($param);
				$param = ucwords(strtolower(trim($param)));

				return $param;

			case PARAM_IST_DATE:
				//$param = trim(syn_fix_utf8($param));
				if ( strlen($param) > 10 ) {
					return $default;
				}

				$pieces = explode("/", $param);

				if ( count($pieces) != 3 ) {
					return $default;
				}

				$day = $pieces[0];
				$month = $pieces[1];
				$year = $pieces[2];

				if ( checkdate($month, $day, $year) ) {
					return $param;
				} else {
					return $default;
				}

			case PARAM_CATEGORY:
				//$param = syn_fix_utf8($param);
				$param = trim($param);
				$param = str_replace("-", " ", $param);

				return $param;

			/*case PARAM_CLEAN:
				// General HTML cleaning, try to use more specific type if possible this is deprecated!
				// Please use more specific type instead.
				if (is_numeric($param)) {
					return $param;
				}
				$param = fix_utf8($param);
				// Sweep for scripts, etc.
				return clean_text($param);
			case PARAM_CLEANHTML:
				// Clean html fragment.
				$param = fix_utf8($param);
				// Sweep for scripts, etc.
				$param = clean_text($param, FORMAT_HTML);
				return trim($param);
			*/

			case PARAM_INT:
				// Convert to integer.
				return (int) $param;

			case PARAM_FLOAT:
				// Convert to float.
				return (float) $param;

			case PARAM_ALPHA:
				// Remove everything not `a-z`.
				return preg_replace('/[^a-zA-Z]/i', '', $param);

			case PARAM_ALPHAEXT:
				// Remove everything not `a-zA-Z_-` (originally allowed "/" too).
				return preg_replace('/[^a-zA-Z_-]/i', '', $param);

			case PARAM_ALPHANUM:
				// Remove everything not `a-zA-Z0-9`.
				return preg_replace('/[^A-Za-z0-9]/i', '', $param);

			case PARAM_ALPHANUMEXT:
				// Remove everything not `a-zA-Z0-9_-`.
				return preg_replace('/[^A-Za-z0-9_-]/i', '', $param);

			case PARAM_SEQUENCE:
				// Remove everything not `0-9,`.
				return preg_replace('/[^0-9,]/i', '', $param);

			case PARAM_BOOL:
				// Convert to 1 or 0.
				$tempstr = strtolower($param);

				if ( $tempstr === 'on' or $tempstr === 'yes' or $tempstr === 'true' ) {
					$param = 1;
				} else if ( $tempstr === 'off' or $tempstr === 'no' or $tempstr === 'false' ) {
					$param = 0;
				} else {
					$param = empty($param) ? 0 : 1;
				}

				return $param;

			case PARAM_NOTAGS:
				// Strip all tags.
				//$param = syn_fix_utf8($param);
				return strip_tags($param);

			case PARAM_SAFEDIR:
				// Remove everything not a-zA-Z0-9_- .
				return preg_replace('/[^a-zA-Z0-9_-]/i', '', $param);

			case PARAM_SAFEPATH:
				// Remove everything not a-zA-Z0-9/_- .
				return preg_replace('/[^a-zA-Z0-9\/_-]/i', '', $param);

			case PARAM_FILE:
				// Strip all suspicious characters from filename.
				//$param = syn_fix_utf8($param);
				$param = preg_replace('~[[:cntrl:]]|[&<>"`\|\':\\\\/]~u', '', $param);

				if ( $param === '.' || $param === '..' ) {
					$param = $default;  // previously ''
				}

				return $param;

			/*case PARAM_PATH:
				// Strip all suspicious characters from file path.
				$param = syn_fix_utf8($param);
				$param = str_replace('\\', '/', $param);

				// Explode the path and clean each element using the PARAM_FILE rules.
				$breadcrumb = explode('/', $param);
				foreach ($breadcrumb as $key => $crumb) {
					if ($crumb === '.' && $key === 0) {
						// Special condition to allow for relative current path such as ./currentdirfile.txt.
					} else {
						$crumb = app_clean_param($crumb, PARAM_FILE);
					}
					$breadcrumb[$key] = $crumb;
				}
				$param = implode('/', $breadcrumb);

				// Remove multiple current path (./././) and multiple slashes (///).
				$param = preg_replace('~//+~', '/', $param);
				$param = preg_replace('~/(\./)+~', '/', $param);
				return $param;*/

			case PARAM_HOST:
				// Allow FQDN or IPv4 dotted quad.
				$param = preg_replace('/[^\.\d\w-]/', '', $param);
				// Match ipv4 dotted quad.
				if ( preg_match('/(\d{1,3})\.(\d{1,3})\.(\d{1,3})\.(\d{1,3})/', $param, $match) ) {
					// Confirm values are ok.
					if ( $match[0] > 255 || $match[1] > 255 || $match[3] > 255 || $match[4] > 255 ) {
						// Hmmm, what kind of dotted quad is this?
						$param = '';
					}
				} else if ( preg_match('/^[\w\d\.-]+$/', $param) // Dots, hyphens, numbers.
					&& ! preg_match('/^[\.-]/', $param) // No leading dots/hyphens.
					&& ! preg_match('/[\.-]$/', $param) // No trailing dots/hyphens.
				) {
					// All is ok - $param is respected.
				} else {
					// All is not ok...
					$param = '';
				}

				return $param;

			/*case PARAM_PEM:
				$param = trim($param);
				// PEM formatted strings may contain letters/numbers and the symbols:
				//   forward slash: /
				//   plus sign:     +
				//   equal sign:    =
				//   , surrounded by BEGIN and END CERTIFICATE prefix and suffixes.
				if (preg_match('/^-----BEGIN CERTIFICATE-----([\s\w\/\+=]+)-----END CERTIFICATE-----$/', trim($param), $matches)) {
					list($wholething, $body) = $matches;
					unset($wholething, $matches);
					$b64 = app_clean_param($body, PARAM_BASE64);
					if (!empty($b64)) {
						return "-----BEGIN CERTIFICATE-----\n$b64\n-----END CERTIFICATE-----\n";
					} else {
						return '';
					}
				}
				return '';*/

			case PARAM_BASE64:
				if ( ! empty($param) ) {
					// $param = trim(syn_fix_utf8($param));
					if ( base64_decode($param) === false ) {
						return $default;
					} else {
						return $param;
					}
				} else {
					return $default;
				}
				break;
			default:
				return $default;
		}
	}

	/**
	 *
	 * @param string $inputDate Provide date
	 *
	 * @return string Returns date formatted date to save in database
	 */
	public static function saveFormattedDate( $inputDate ) //TODO: move this function to framework date related helper
	{
		$inputDate = self::clearParam($inputDate, PARAM_RAW_TRIMMED);
		if ( ! self::isNull($inputDate) ) {
			return (string) Helper::getDate(trans('shared::config.mysql_date_format'), $inputDate, trans('shared::config.input_date_format'));
		} else {
			return '0000-00-00';
		}
	}

	/**
	 * @param $inputValue
	 *
	 * @return bool
	 */
	public static function isNull( $inputValue ) {
		if ( is_null($inputValue) ) {
			return true;
		} else if ( is_array($inputValue) && sizeof($inputValue) > 0 ) {
			return false;
		} else if ( (is_string($inputValue) || is_int($inputValue)) && ($inputValue != '') && (strtolower($inputValue) != 'null') && (strlen(trim($inputValue)) > 0) ) {
			return false;
		} else {
			return true;
		}
	}


	/**
	 *  Log Exception in catch block
	 *
	 * @param \Exception $e        Occurred exception
	 * @param array      $data     Extra data to log
	 * @param null       $message  Message to log
	 * @param string     $severity Severity of exception
	 */
	public static function logException( Exception &$e, $data = [], $message = null, $severity = FLINNT_LOG_ERROR ) {

		self::logExceptionHandler($e, $data, $message, $severity);
	}


	/**
	 * Log Exception and throw in catch block
	 *
	 * @param \Exception $e        Occurred exception
	 * @param array      $data     Extra data to log
	 * @param null       $message  Message to log
	 * @param string     $severity Severity of exception
	 */
	public static function logExceptionAndHalt( Exception &$e, $data = [], $message = null,
	                                            $severity = FLINNT_LOG_ERROR ) {

		self::logExceptionHandler($e, $data, $message, $severity, 'halt');
	}

	/**
	 * Log exception and force throw
	 *
	 * @param \Exception $e        Occurred exception
	 * @param array      $data     Extra data to log
	 * @param null       $message  Message to log
	 * @param string     $severity Severity of exception
	 */
	public static function logExceptionAndThrow( Exception &$e, $data = [], $message = null,
	                                             $severity = FLINNT_LOG_ERROR ) {

		self::logExceptionHandler($e, $data, $message, $severity, true);
	}

	/**
	 *  This function will log exception and throw if withHalt is set to true
	 *
	 * @param \Exception|null $e        Occurred exception
	 * @param array           $data     Extra data to log
	 * @param null            $message  Message to log
	 * @param string          $severity Severity of exception
	 * @param bool|string     $throw    True will always throw exception, `halt` will check for local environment and
	 *                                  false will not throw the exception
	 *
	 * @throws \Exception
	 */
	private static function logExceptionHandler( Exception &$e = null, $data = [], $message = null,
	                                             $severity = FLINNT_LOG_ERROR, $throw = false ) {

		/* Additional info will be added by default in data variable */
		$data['code'] = $e->getCode();
		$data['line'] = $e->getLine();
		$data['file'] = $e->getFile();
		$data['trace'] = $e->getTrace();
		$data['ip'] = Helper::getIPAddress(true);
		$data['user_id'] = Session::get('user_id', null);
		$data['member_id'] = Session::get('member_id', null);

		// Log Error
		Log::$severity((self::isNull($message) ? $e->getMessage() : $message), $data);

		/**
		 * If forceThrow is true, it will always throw the exception
		 * Halt execution if true
		 * TODO: IP based throw condition to be added
		 */
		if ( $throw == true || ($throw == 'halt' && App::environment() == APP_ENV_LOCAL) ) {
			throw $e;
		}
	}

	/**
	 * Get the microtime
	 *
	 * @param null $float
	 *
	 * @return mixed
	 */
	public static function getMicroTime( $float = null ) {
		return microtime($float);
	}

	/**
	 * Get the difference between microtime
	 *
	 * @param int $a Later time
	 * @param int $b Recent time
	 *
	 * @return mixed
	 */
	public static function getMicrotimeDiff( $a, $b ) {
		list($a_dec, $a_sec) = explode(' ', $a);
		list($b_dec, $b_sec) = explode(' ', $b);

		return $b_sec - $a_sec + $b_dec - $a_dec;
	}

	/**
	 * Call the command in background
	 *
	 * @param string $commandName Name of the artisan command
	 * @param array  $parameters  Extra parameters of command
	 */
	public static function callCommandInBackground( $commandName, array $parameters = [] ) {
		$command = "php artisan {$commandName} ";

		if ( ! empty($parameters) ) {
			$params = implode(" ", $parameters);
			$command .= " {$params} ";
		}

		$fileName = str_replace(':', '_', $commandName);
		$fileName .= '.log';
		$outputFile = DIR_FS_CRON_LOG . "{$fileName}";

		$command .= " -v >> {$outputFile} 2>&1 &";

		if ( ! File::exists(DIR_FS_CRON_LOG) ) {
			File::makeDirectory(DIR_FS_CRON_LOG, 0777, true, true);
		}

		$process = new Process($command, base_path());
		$process->run();
	}

	/**
	 * Get the parameters from the request
	 *
	 * @param \Illuminate\Http\Request $request    Request object
	 * @param array                    $parameters Parameters you want from request
	 *
	 * @return array
	 */
	public static function getRequestData( Request $request, array $parameters ) {
		$data = [];
		foreach ( $parameters as $parameter ) {
			$data[] = $request->get($parameter);
		}

		return $data;
	}

	/**
	 * Encrypt columns from single dimensional array,
	 * multi-dimensional array, collection, paginate or keys for single dimensional array
	 * <ul>
	 * <li><b>Usage:</b><br/><br/>
	 * <b>Case 1:  encrypt keys of single dimensional array</b><br/><br/>
	 * <p>$test = [0 => 'test', 1 => 'test1']; </p>
	 * <p>$test = GeneralHelpers::encryptColumns($test);</p><br>
	 * <p>output: ['C0YiBh_GKWI=' => 'test', 'yLlvyHyYdwI=' => 'test1']</p>
	 * <br><br></li>
	 * <li><b>Case 2: encrypt column of multi-dimensional array</b><br/><br/>
	 * <p>$test = [['course_id' => 1], ['course_id' => 2]]; </p>
	 * <p>$test = GeneralHelpers::encryptColumns($test,'course_id');</p><br>
	 * <p>output: [['course_id' => 'yLlvyHyYdwI='], ['course_id' => '4gRoSNkX3wU=']]</p>
	 * <br>
	 * <br><br></li>
	 * <li><b>Case 3: encrypt column of collection </b><br/><br/>
	 * <p>$test = new Collection([['course_id' => 1], ['course_id' => 2]]); </p>
	 * <p>$test = GeneralHelpers::encryptColumns($test, 'course_id');</p><br>
	 * <p>output (items in collection): [['course_id' => 'yLlvyHyYdwI='], ['course_id' => '4gRoSNkX3wU=']]</p>
	 *</li></ul>
	 *
	 * @param array|Collection  $data    Data to encrypt
	 * @param array|string|null $columns Column/s to encrypt. provide string for single column and array for multiple columns
	 *
	 * @return mixed Return data with encrypted columns
	 */
	public static function encryptColumns( $data, $columns = null ) {

		/* Check if the array is single dimensional or multi-dimensional */
		if ( is_array($data) && ! is_array(array_first($data)) ) {

			/* if columns is not null, then process via columns */
			if ( ! is_null($columns) ) {

				/* if columns is string, Convert it into array */
				if ( ! is_array($columns) ) {
					$columns = [$columns];
				}

				/* encrypt data on each column */
				foreach ( $columns as $column ) {

					/* if date is present on particular column, encrypt it  */
					if ( isset($data[$column]) ) {
						$data[$column] = self::encode($data[$column]);
					}

				}
			} else {
				/* Encrypt the key of the data and unset old key */
				foreach ( $data as $key => $value ) {
					$encodedKey = self::encode($key);
					unset($data[$key]);
					$data[$encodedKey] = $value;
				}
			}

		} else {
			/* array is not single dimensional, process in else condition */

			foreach ( $data as $key => $value ) {

				/* Encrypt column if columns is not array i.e, single columns */
				if ( ! is_null($columns) && ! is_array($columns) ) {

					/* if value is present then encode it */
					if ( isset($value[$columns]) ) {
						$value[$columns] = self::encode($value[$columns]);
						$data[$key] = $value;
					}
				} elseif ( is_array($columns) ) {
					/* Encrypt column if columns is array i.e, multiple columns */

					foreach ( $columns as $column ) {

						/* if value is present then encode it */
						if ( isset($value[$column]) ) {
							$value[$column] = self::encode($value[$column]);
							$data[$key] = $value;
						}

					}
				} else {

					/* Encrypt the key of the data and unset old key */
					$encodedKey = self::encode($key);
					unset($data[$key]);
					$data[$encodedKey] = $value;
				}
			}
		}

		return $data;
	}
}