<?php
/**
 * Created by vishalkariya.
 * User: flinnt-php-8
 * Date: 13/10/16
 * Time: 12:24 PM
 * Description: This file is created for the package m6web/firewall
 */

return [


	/*
	|--------------------------------------------------------------------------------------------------------
	| Whitelist
	|--------------------------------------------------------------------------------------------------------
	|
	| Specify array of whitelist IPs.Entries Format should be as per below:
	|
	| IPV6                  ::1                                     Short notation
	|
	| IPV4                  192.168.0.1
	|
	| Range                 192.168.0.0-192.168.1.60                Includes all IPs from 192.168.0.0 to 192.168.0.255
	|                                                               and from 192.168.1.0 to 198.168.1.60
	|
	| Wild card             192.168.0.*                             IPs starting with 192.168.0
	|                                                               Same as IP Range 192.168.0.0-192.168.0.255
	|
	|Subnet mask            192.168.0.0/255.255.255.0               IPs starting with 192.168.0
	|                                                               Same as 192.168.0.0-192.168.0.255 and 192.168.0.*
	|
	|CIDR Mask              192.168.0.0/24                          IPs starting with 192.168.0
	|                                                               Same as 192.168.0.0-192.168.0.255 and 192.168.0.*
	|                                                               and 192.168.0.0/255.255.255.0
	*/


	'whitelist' =>[

	],


	/*
	|--------------------------------------------------------------------------------------------------------
	| Blacklist
	|--------------------------------------------------------------------------------------------------------
	|
	| Specify array of Blacklist IPs.Entries Format should be as per whitelist IPs Format
	|
	*/

	'blacklist' =>[

	],

	/*
	|--------------------------------------------------------------------------------------------------------
	| setDefaultState
	|--------------------------------------------------------------------------------------------------------
	|
	| defines default firewall response (Optional - Default false)
	|
	*/

	'setDefaultState' => false,

	/*
	|--------------------------------------------------------------------------------------------------------
	| setIpAddress
	|--------------------------------------------------------------------------------------------------------
	|
	| defines default Client IP address (Optional - Default false)
	|
	*/

	'setIpAddress' => false,

];