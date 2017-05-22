<?php

namespace App\Modules\Content\Transformers;

use App\Common\URLHelpers;
use Helper;
use League\Fractal\TransformerAbstract;

/**
 * Class CourseTransformer
 * @package namespace App\Modules\Content\Transformers;
 */
class CourseTransformer extends TransformerAbstract {

	/**
	 * Transform the \Course entity
	 *
	 * @param array $courseDetails
	 *
	 * @return array
	 */
	public function transform( array $courseDetails ) {
		$transformedCourseDetails = [
			'course_id'          => $courseDetails['course_id'],
			'user_firstname'     => $courseDetails['user_firstname'],
			'user_lastname'      => $courseDetails['user_lastname'],
			'course_name'        => $courseDetails['course_name'],
			'course_subtitle'    => $courseDetails['course_subtitle'],
			'user_school_name'   => $courseDetails['user_school_name'],
			'course_type'        => $courseDetails['course_public_type_id'],
			'course_description' => $courseDetails['course_description'],
			/* place your other model properties here */
		];

		/* set course status */
		$transformedCourseDetails['course_status'] = ($courseDetails['course_is_free'] == 0) ? "<i class='fa fa-rupee'> " . $courseDetails['course_price'] . "</i>" : 'Free';


		/* set course picture url */
		$userPicture = (! empty($courseDetails['user_picture'])) ? $courseDetails['user_picture'] : 'default.png';
		$transformedCourseDetails['user_picture'] = URLHelpers::getUserPictureURL($userPicture, USER_PICTURE_LARGE);

		/* set course picture url */
		if ( ! empty($courseDetails['course_picture']) ) {
			$transformedCourseDetails['course_picture'] = URLHelpers::getCoursePictureURL($courseDetails['course_picture'], USER_PICTURE_LARGE);
		} else {
			$transformedCourseDetails['course_picture'] = URLHelpers::getCoursePictureURL('default.png', USER_PICTURE_SMALL);
		}

		$courseType = $courseDetails['course_public_type_id'];
		$courseStartDate = $courseDetails['course_event_start_date'];
		$courseAddress = $courseDetails['address1'] . ', ' . $courseDetails['address2'] . ', ' . $courseDetails['city'] . '- ' . $courseDetails['pincode'] . ', ' . $courseDetails['state_name'];

		if ( $courseType == '2' ) {
			$transformedCourseDetails['course_start_date'] = (string) Helper::timestempToDatetime($courseStartDate, 'D d-M-y h:m:s');
			$transformedCourseDetails['course_address'] = $courseAddress;
		}else{
			$transformedCourseDetails['course_start_date'] = $transformedCourseDetails['course_address'] = '';
		}

		return $transformedCourseDetails;
	}
}
