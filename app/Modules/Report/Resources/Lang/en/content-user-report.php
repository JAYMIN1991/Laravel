<?php
/**
 * Created by PhpStorm.
 * User: flinnt-php-6
 * Date: 15/2/17
 * Time: 5:55 PM
 */

return [
	'index' => [
		'title'                     => 'Content User Report',
		'deleted_course'            => 'Show Deleted Course?',
		'institute_placeholder'     => 'Select Institute',
		'course_placeholder'        => 'Select Course',
		'import_status'             => 'Course Import Status',
		'import_status_placeholder' => 'Select Status',
		'greater_than_zero'         => 'Show Views Greater Than Zero?',
		'content_copy_in'           => 'Content Copy in (Course Name - Institute Name)',
		'views'                     => 'Views',
		'comments'                  => 'Comments',
		'report_not_found'          => 'Institute report not found',
		'import_status_option'      => [
			'not_started' => 'Not Started',
			'running'     => 'Running',
			'completed'   => 'Completed',
			'failed'      => 'Failed'
		],
		'source'                    => [
			'institute'   => 'Select Source Institute Name',
			'course'      => 'Select Source Course Name',
			'course_name' => 'Source Course Name'
		],
		'target'                    => [
			'institute' => 'Select Target Institute Name',
			'course'    => 'Select Target Course Name',
		],
		'date'                      => [
			'from' => 'Date from',
			'to'   => 'Date to',
		],
	],

    'errors' => [
    	'date_format' => 'Date must be in DD/MM/YYYY format'
    ]
];