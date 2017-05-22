<?php

return [
	'common'      => [
		'visit_by'              => 'Visit By',
		'visit_date'            => 'Visit Date',
		'visit_date_from'       => 'Visit Date From',
		'visit_date_to'         => 'Visit Date To',
		'visit_details'         => 'Visit Details',
		'inquiry_converted'     => 'Inquiry Converted',
		'institute_type'        => 'Institute Type',
		'institute_existing'    => 'Existing Institute',
		'institute_new'         => 'New Institute',
		'institute_details'     => 'Institute Details',
		'inst_inquiry_id'       => 'Institute',
		'institute_name'        => 'Institute Name',
		'contact_person'        => 'Contact Person',
		'phone'                 => 'Phone',
		'category'              => 'Category',
		'designation'           => 'Designation',
		'student_strength'      => 'Student Strength',
		'state'                 => 'State',
		'contact_number'        => 'Contact Number',
		'address'               => 'Address',
		'city'                  => 'City',
		'remarks'               => 'Remarks',
		'additional_details'    => 'Additional Details',
		'email'                 => 'Email ID',
		'visit_by_placeholder'  => 'Select Member',
		'category_placeholder'  => 'Select Category',
		'institute_placeholder' => 'Select Institute',
		'state_placeholder'     => 'Select State',
		'validation'            => [
			'visit_date_before' => 'The :attribute must be a date before Today .'
		],
		'error'                 => [
			'inst_already_acq' => 'Institution has already been acquired.',
			'inst_invalid'     => 'Institute should not be changed',
			'update_fail'      => 'Error updating call visit',
			'delete_fail'      => 'Error deleting call visit'
		]
	],
	'create'      => [
		'title' => 'Institute visit entry',

	],
	'index'       => [
		'title'            => 'Institute Call Visit List',
		'contact_details'  => 'Contact Details',
		'acq_status'       => 'ACQ Status',
		'action'           => 'Action',
		'inst_acquisition' => 'Institution Acquisition',
		'delete_confirm'   => 'Are you sure?'

	],
	'edit'        => [
		'title' => 'Institute visit entry',
		'edit_visit' => 'Edit Visit',
	],
	'destroy'     => [
		'validation'   => [
			'after_visit_exists' => 'After sales visit entry exist.'
		],
		'delete_visit' => 'Delete Visit'
	],
	'acquisition' => [
		'title'                 => 'Institute Visit Acquisition',
		'institute_placeholder' => '--Select Institute name registered on Flinnt which are not acquired yet--',
		'institute_label'       => 'Select registered institute on Flinnt',
		'remove_acquisition'    => 'Remove Acquisition?',
		'error'                 => [
			'acquisition' => 'Error updating visit acquisition'
		]
	]

];