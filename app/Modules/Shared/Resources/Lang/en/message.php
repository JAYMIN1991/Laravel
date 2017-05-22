<?php
return [
	'success'    => [
		'process'                     => 'Your request has been processed successfully.',
		'logout'                      => 'You are logged out successfully.',
		'min_length'                  => ':attribute must be minimum :min characters long.',
		'max_file_size'               => 'File size must be less than 10 MB.',
		'file_ext'                    => 'File extension is not supported.',
		'comment_posted'              => 'Your comment has been posted',
		'learner_sample'              => 'Download sample file, fill details accordingly and upload again.',
		'delete_record'               => 'Record Deleted Successfully.',
		'invitation_already_received' => 'You have already invited to join this course. <a href=":url" class="btn btn-primary btn-xs">Click here</a> to see invitation.</a>',
	],
	'error'      => [
		'something_wrong'          => 'Something went wrong while processing your request!',
		'nothing_to_export'        => 'Nothing to export.',
		'already_exists'           => ':attribute with same :other already exists.',
		'no_multi_selection'       => 'You must select at least one :attribute.',
		'no_selection'             => 'You must select :attribute.',
		'compare'                  => ':attribute must be less than or equal to :other.',
		'blank_value'              => ':attribute could not be blank.',
		'no_match'                 => ':attribute do not match with :other.',
		'blank_upload'             => 'You must upload :attribute',
		'blank_upload_multi'       => 'You must upload at least one :attribute',
		'poll_option'              => 'You must specify at least two quiz Options',
		'invalid_login'            => 'Invalid Username or Password',
		'demo_expired'             => 'Your account has been expired',
		'invalid_value'            => 'Invalid value specified for :attribute',
		'session_expired'          => 'Your session has been expired',
		'invalid_promo_code'       => 'Invalid coupon code',
		'account_suspended'        => 'Your account has been suspended.',
		'test_locked'              => 'Question paper has already been generated for selected test.',
		'test_invalid_id'          => 'You are not authorized to give exam from this location.',
		'test_invalid_group'       => 'You are not authorized to give exam',
		'test_timeout'             => 'Exam has been timed out.',
		'test_already_given'       => 'You have already given ":attribute". Try another.',
		'invalid_test'             => 'Invalid request. The specified test details are invalid. ERROR CODE: :attribute',
		'checkout_failed'          => 'Checkout process has been failed',
		'session_timeout'          => 'It appears that your session has been expired due to inactivity. :attribute',
		'invalid_excel_file'       => 'Invalid file or your file might be corrupted. Pl. check your file.',
		'after_sales_exist'        => 'After sales visit entry exist. Acquisition can not be removed.',
		'apply_commission_limit'   => 'Apply Commission should be between :attribute to {:other}',
		'entry_exist'              => 'Selected Data is already Exist.',
		'apply_commission_out'     => 'Apply Commission Allow 2 Digit after Dot.',
		'lerner_import_limit'      => 'Your file must contain at least one and not more than :attribute learners.',
		'invalid_url'              => 'Invalid URL',
		'invalid_request_type'     => 'Invalid request type',
		'not_logged_in'            => 'User is not logged in',
		'unauthenticated'          => 'Unauthenticated',
		'invalid_request'          => 'Unable to process request. Please login again.',
		'invalid_auth_header'      => 'Invalid authentication header',
		'decrypt_id_invalid_route' => 'Trying to apply decrypt Id on route without id. Route Name: :route_name',
		'insert_data'              => 'Error while inserting the data',
		'not_found'                => 'Not found!',
		'check_already'            => 'Check is already either confirmed, cancelled or returned.'
	],
	'warning'    => [
		'test_unanswered' => 'Unanswered questions',
	],
	'info'       => [
		'no_records' => 'No records found'
	],
	'alert'      => [
		'confirm_delete' => 'Are you sure?',
		''
	],
	'validation' => [
		'unique' => 'Selected Data is already Exist.',
	],


];