<?php
/**
 * Created by PhpStorm.
 * User: flinnt-php-6
 * Date: 20/1/17
 * Time: 6:12 PM
 */

return [
	'title'  => 'Copy Learners',
	'common' => [
		'any' => 'All'
	],
	'index'  => [
		'from'                   => [
			'institute'             => 'From Institute',
			'placeholder_institute' => 'Select From Institute',
			'courses'                => 'From Courses',
			'placeholder_courses'    => 'Select From Courses',
		],
		'to'                     => [
			'institute' => 'To Institute',
			'placeholder_institute' => 'Select To Institute',
			'course'    => 'To Course',
			'placeholder_course'    => 'Select To Course',
		],
	    'learners' => 'Learners',
	    'initialize_copy' => 'Initializing copy learner from course :from_course to :to_course',
	],
    'error' => [
    	'to_course' => [
		    'course_institute_not_match' => 'Invalid "To Course:" :course_name. Course institution does not match with selected at "To Institute"',
		    'invalid_course' => 'Invalid "To Course:" :course_name. It is either disabled, not free, not published or institution plan has been expired.'
	    ],
	    'from_courses' => [
		    'course_institute_not_match' => 'Invalid "From Course:" :course_name. Course institution does not match with selected at "From Institute"',
		    'invalid_course' => 'Invalid "From Course:" :course_name. It is either disabled, not free, not published or institution plan has been expired.'
	    ],
		'executing_job' => 'Error executing job! Please try after some time',
        'executing_job_courses' => 'Error executing job for courses: :courses'
    ],
    'success' => [
    	'job_init' => 'Copy learner job has been initialized with ID #:job_id'
    ]
];