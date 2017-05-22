<?php

return [
	'date_format_short'       => '%d/%m/%Y',  //this is used for strftime()
	'date_format_long'        => '%A %d %B, %Y', // this is used for strftime()
	'date_format'             => 'd/m/Y', // this is used for date()
	'php_date_time_format'    => 'd/m/Y H:i:s', // this is used for date()
	'date_time_format'        => ' %H:%M:%S',
	'date_format_spiffycal'   => 'dd/MM/yyyy', //Use only 'dd', 'MM' and 'yyyy' here in any order
	'mysql_date_time_format'  => 'Y-m-d H:i:s', // this is used for mysql datetime() use this to store user_dt value
	'mysql_date_format'       => 'Y-m-d', // this is used for mysql datetime() use this to store user_dt value
	'input_date_format'       => 'd/m/Y',
	'input_date_time_format'  => 'd/m/Y h:i:s',
	'output_date_format'      => 'd/m/Y',
	'output_date_time_format' => 'd/m/Y h:i:s',
    'validation_rule_date_format' => 'd/m/Y'
];