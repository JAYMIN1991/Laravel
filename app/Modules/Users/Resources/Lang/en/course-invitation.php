<?php
/**
 * Created by PhpStorm.
 * User: flinnt-php-6
 * Date: 3/1/17
 * Time: 2:57 PM
 */

return [
	'page_title' => 'Send Invitation',

	'title' => 'Invite users to join community courses',

	'select_course' => 'Select Course',

	// @TODO :: change errors to error
	'errors' => [
		'login_id'           => 'You must specify at least one valid login id',
		'unexpected'         => 'Unable to send invitation please try again later',
	],

	'validation' => [
		'invite_file'        => ['required_if' => 'Please choose a valid excel file'],
		'invite_manual_text' => ['required_if' => 'Please add comma separated login ids in text area'],
	],

	'success' => [
		'invitation' => 'Invitation send successfully',
	],

	'index' => [
		'course_id'          => 'Course',
		'invite_by'          => 'Invite By',
		'invite_manual_text' => 'Login Ids',
		'invite_file'        => 'Excel File',
		'invite_manually' => 'Invite Manually',
		'upload_excel'    => 'Upload an Excel file',
		'enter_login_id'  => 'Enter comma separated login ids',
	],
];