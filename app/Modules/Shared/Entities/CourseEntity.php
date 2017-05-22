<?php
/**
 * Created by PhpStorm.
 * User: flinnt-php-6
 * Date: 10/3/17
 * Time: 6:20 PM
 */

namespace App\Modules\Shared\Entities;


use DB;
use Flinnt\Repository\Exceptions\RecordNotFoundException;

/**
 * Class CourseEntity
 * @package App\Modules\Shared\Entities
 */
class CourseEntity {

	/**
	 * @var
	 */
	private $course_enrollment_end_date;
	/**
	 * @var
	 */
	private $course_public;
	/**
	 * @var
	 */
	private $course_category;
	/**
	 * @var
	 */
	private $course_price;
	/**
	 * @var
	 */
	private $course_start_date;
	/**
	 * @var
	 */
	private $course_name;
	/**
	 * @var
	 */
	private $course_user_picture;
	/**
	 * @var
	 */
	private $course_event_start_date;
	/**
	 * @var
	 */
	private $course_user_name;
	/**
	 * @var
	 */
	private $course_enabled;
	/**
	 * @var
	 */
	private $course_demo_days;
	/**
	 * @var
	 */
	private $course_subtitle;
	/**
	 * @var
	 */
	private $course_publisher;
	/**
	 * @var
	 */
	private $course_review_remarks;
	/**
	 * @var
	 */
	private $course_review_status;
	/**
	 * @var
	 */
	private $course_publish_date;
	/**
	 * @var
	 */
	private $course_start_time;
	/**
	 * @var
	 */
	private $course_status_name;
	/**
	 * @var
	 */
	private $course_picture;
	/**
	 * @var
	 */
	private $course_user_ip;
	/**
	 * @var
	 */
	private $course_discount;
	/**
	 * @var
	 */
	private $course_comments_reviewer;
	/**
	 * @var
	 */
	private $course_description;
	/**
	 * @var
	 */
	private $course_status;
	/**
	 * @var
	 */
	private $course_owner;
	/**
	 * @var
	 */
	private $course_age_end;
	/**
	 * @var
	 */
	private $course_max_subscription;
	/**
	 * @var
	 */
	private $course_university;
	/**
	 * @var
	 */
	private $course_configured;
	/**
	 * @var
	 */
	private $course_plan_expired;
	/**
	 * @var
	 */
	private $course_id;
	/**
	 * @var
	 */
	private $course_is_free;
	/**
	 * @var
	 */
	private $course_end_date;
	/**
	 * @var
	 */
	private $course_community;
	/**
	 * @var
	 */
	private $course_sortorder;
	/**
	 * @var
	 */
	private $course_slug;
	/**
	 * @var
	 */
	private $course_age_start;
	/**
	 * @var
	 */
	private $course_hash;
	/**
	 * @var
	 */
	private $course_device_type;
	/**
	 * @var
	 */
	private $course_banner;
	/**
	 * @var
	 */
	private $course_public_type_id;
	/**
	 * @var
	 */
	private $course_has_certificaton;
	/**
	 * @var
	 */
	private $course_promotion;
	/**
	 * @var
	 */
	private $course_promotion_rank;
	/**
	 * @var
	 */
	private $course_inserted;
	/**
	 * @var
	 */
	private $course_updated;
	/**
	 * @var
	 */
	private $course_update_user;

	/**
	 * CourseEntity constructor.
	 *
	 * @param $courseId
	 *
	 * @throws \Flinnt\Repository\Exceptions\RecordNotFoundException
	 */
	public function __construct( $courseId ) {
		$result = DB::table(TABLE_COURSES)->where('course_id', $courseId)->first(['*']);

		if ( $result ) {
			foreach ( $result as $key => $value ) {
				$this->{$key} = $value;
			}
		} else {
			throw new RecordNotFoundException(TABLE_COURSES, $courseId);
		}
	}

	/**
	 * @return mixed
	 */
	public function getCourseEnrollmentEndDate() {
		return $this->course_enrollment_end_date;
	}

	/**
	 * @return mixed
	 */
	public function getCoursePublic() {
		return $this->course_public;
	}

	/**
	 * @return mixed
	 */
	public function getCourseCategory() {
		return $this->course_category;
	}

	/**
	 * @return mixed
	 */
	public function getCoursePrice() {
		return $this->course_price;
	}

	/**
	 * @return mixed
	 */
	public function getCourseStartDate() {
		return $this->course_start_date;
	}

	/**
	 * @return mixed
	 */
	public function getCourseName() {
		return $this->course_name;
	}

	/**
	 * @return mixed
	 */
	public function getCourseUserPicture() {
		return $this->course_user_picture;
	}

	/**
	 * @return mixed
	 */
	public function getCourseEventStartDate() {
		return $this->course_event_start_date;
	}

	/**
	 * @return mixed
	 */
	public function getCourseUserName() {
		return $this->course_user_name;
	}

	/**
	 * @return mixed
	 */
	public function getCourseEnabled() {
		return $this->course_enabled;
	}

	/**
	 * @return mixed
	 */
	public function getCourseDemoDays() {
		return $this->course_demo_days;
	}

	/**
	 * @return mixed
	 */
	public function getCourseSubtitle() {
		return $this->course_subtitle;
	}

	/**
	 * @return mixed
	 */
	public function getCoursePublisher() {
		return $this->course_publisher;
	}

	/**
	 * @return mixed
	 */
	public function getCourseReviewRemarks() {
		return $this->course_review_remarks;
	}

	/**
	 * @return mixed
	 */
	public function getCourseReviewStatus() {
		return $this->course_review_status;
	}

	/**
	 * @return mixed
	 */
	public function getCoursePublishDate() {
		return $this->course_publish_date;
	}

	/**
	 * @return mixed
	 */
	public function getCourseStartTime() {
		return $this->course_start_time;
	}

	/**
	 * @return mixed
	 */
	public function getCourseStatusName() {
		return $this->course_status_name;
	}

	/**
	 * @return mixed
	 */
	public function getCoursePicture() {
		return $this->course_picture;
	}

	/**
	 * @return mixed
	 */
	public function getCourseUserIp() {
		return $this->course_user_ip;
	}

	/**
	 * @return mixed
	 */
	public function getCourseDiscount() {
		return $this->course_discount;
	}

	/**
	 * @return mixed
	 */
	public function getCourseCommentsReviewer() {
		return $this->course_comments_reviewer;
	}

	/**
	 * @return mixed
	 */
	public function getCourseDescription() {
		return $this->course_description;
	}

	/**
	 * @return mixed
	 */
	public function getCourseStatus() {
		return $this->course_status;
	}

	/**
	 * @return mixed
	 */
	public function getCourseOwner() {
		return $this->course_owner;
	}

	/**
	 * @return mixed
	 */
	public function getCourseAgeEnd() {
		return $this->course_age_end;
	}

	/**
	 * @return mixed
	 */
	public function getCourseMaxSubscription() {
		return $this->course_max_subscription;
	}

	/**
	 * @return mixed
	 */
	public function getCourseUniversity() {
		return $this->course_university;
	}

	/**
	 * @return mixed
	 */
	public function getCourseConfigured() {
		return $this->course_configured;
	}

	/**
	 * @return mixed
	 */
	public function getCoursePlanExpired() {
		return $this->course_plan_expired;
	}

	/**
	 * @return mixed
	 */
	public function getCourseId() {
		return $this->course_id;
	}

	/**
	 * @return mixed
	 */
	public function getCourseIsFree() {
		return $this->course_is_free;
	}

	/**
	 * @return mixed
	 */
	public function getCourseEndDate() {
		return $this->course_end_date;
	}

	/**
	 * @return mixed
	 */
	public function getCourseCommunity() {
		return $this->course_community;
	}

	/**
	 * @return mixed
	 */
	public function getCourseSortorder() {
		return $this->course_sortorder;
	}

	/**
	 * @return mixed
	 */
	public function getCourseSlug() {
		return $this->course_slug;
	}

	/**
	 * @return mixed
	 */
	public function getCourseAgeStart() {
		return $this->course_age_start;
	}

	/**
	 * @return mixed
	 */
	public function getCourseHash() {
		return $this->course_hash;
	}

	/**
	 * @return mixed
	 */
	public function getCourseDeviceType() {
		return $this->course_device_type;
	}

	/**
	 * @return mixed
	 */
	public function getCourseBanner() {
		return $this->course_banner;
	}

	/**
	 * @return mixed
	 */
	public function getCoursePublicTypeId() {
		return $this->course_public_type_id;
	}

	/**
	 * @return mixed
	 */
	public function getCourseHasCertificaton() {
		return $this->course_has_certificaton;
	}

	/**
	 * @return mixed
	 */
	public function getCoursePromotion() {
		return $this->course_promotion;
	}

	/**
	 * @return mixed
	 */
	public function getCoursePromotionRank() {
		return $this->course_promotion_rank;
	}

	/**
	 * @return mixed
	 */
	public function getCourseInserted() {
		return $this->course_inserted;
	}

	/**
	 * @return mixed
	 */
	public function getCourseUpdated() {
		return $this->course_updated;
	}

	/**
	 * @return mixed
	 */
	public function getCourseUpdateUser() {
		return $this->course_update_user;
	}


}