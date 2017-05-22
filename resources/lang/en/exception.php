<?php
/**
 * Created by PhpStorm.
 * User: flinnt-php-6
 * Date: 7/2/17
 * Time: 3:05 PM
 */

return [
	'invalid_parameters' =>[
		'code' => '2',
	    'message' => 'Invalid parameter value(s) specified for - :parameters'
	],
	'something_wrong' => [
		'code' => '3',
	    'message' => 'Something went wrong while processing request'
	],
	'resource_not_found' => [
		'code' => '9',
	    'message' => 'Resource does not exists :resource'
	],
	'bad_header'      => [
		'code'    => '31',
		'message' => 'Valid header not specified',
	],
    'invalid_argument_array' => [
		'code' => '32',
        'message' => 'Argument array must have following keys :keys'
    ],
	'no_query_result' => [
		'code' => '33',
		'message' => 'No query results for table [:table] ids : :keys'
	],
	'unknown_error' => [
		'code' => '34',
	    'message' => 'Unknown error',
	],
	'invalid_token' => [
		'code' => '35',
	    'message' => "Invalid token"
	],
	'invalid_request' => [
		'code' => '11',
		'message' => "Invalid request"
	],

    'sales' => [
        'test_exception_name' => [
        	'code' => '',
            'message' => ''
        ]
	]
];