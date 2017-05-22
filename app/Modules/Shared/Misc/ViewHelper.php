<?php
/**
 * Created by PhpStorm.
 * User: flinnt-php-6
 * Date: 17/1/17
 * Time: 4:28 PM
 */

namespace App\Modules\Shared\Misc;

/**
 * Class ViewHelper
 * @package App\Modules\Shared\Misc
 */
class ViewHelper {

	/**
	 * Value of Any option of select
	 */
	const SELECT_OPTION_VALUE_ANY = "";

	/**
	 * Value of No option of select
	 */
	const SELECT_OPTION_VALUE_NO = 1;

	/**
	 * Value of Yes option of select
	 */
	const SELECT_OPTION_VALUE_YES = 2;

	/**
	 * Value of placeholder option of select
	 */
	const SELECT_OPTION_VALUE_PLACEHOLDER = 0;
    /**
     * value for placeholder time bound course type
     */
    const SELECT_COURSE_TYPE_TIME_BOUND = 2;

    /**
     * value for placeholder self paced course type
     */
    const SELECT_COURSE_TYPE_SELF_PACED = 3;
}

/**
 * Class UserCommissionRangeListViewHelper
 * @package App\Modules\Shared\Misc
 */
class UserCommissionRangeListViewHelper {

	/**
	 *
	 */
	const SELECT_COMMISSION_RANGE_EQUAL = '=';
	/**
	 *
	 */
	const SELECT_COMMISSION_RANGE_LESS_THEN = '<';
	/**
	 *
	 */
	const SELECT_COMMISSION_RANGE_GREATER_THEN = '>';
	/**
	 *
	 */
	const SELECT_COMMISSION_RANGE_LESS_EQUAL = '<=';
	/**
	 *
	 */
	const SELECT_COMMISSION_RANGE_GREATER_EQUAL = '>=';
}
/*
 * Course order status const class
 * */
class CourseOrderSummary{
    const SELECT_ORDER_STATUS_INITIALIZED = '0';
    const SELECT_ORDER_STATUS_IN_SESSION = '1';
    const SELECT_ORDER_STATUS_PROCESSING = '2';
    const SELECT_ORDER_STATUS_CANCELLED = '3';
    const SELECT_ORDER_STATUS_FAILED = '4';
    const SELECT_ORDER_STATUS_COMPLETED = '5';
}

/*
 * Course Order Paid Status
 * */
class CourseOrderPaidSummary{
    const SELECT_PAID_STATUS_YES = '1';
    const SELECT_PAID_STATUS_NO = '0';
}
/**
 * Class InstituteUsersListViewHelper
 * @package App\Modules\Shared\Misc
 */
class InstituteUsersListViewHelper {

	/**
	 * Value of plan status unlocked option
	 */
	const SELECT_PLAN_STATUS_UN_LOCKED = 1;

	/**
	 * Value of plan status locked option
	 */
	const SELECT_PLAN_STATUS_LOCKED = 2;

	/**
	 * Value of user type creator option
	 */
	const SELECT_USER_TYPE_CREATOR = 1;

	/**
	 * Value of user type learner option
	 */
	const SELECT_USER_TYPE_LEARNER = 3;

	/**
	 * Value of user type teacher option
	 */
	const SELECT_USER_TYPE_TEACHER = 2;
}


/**
 * Class AcquisitionReportViewHelper
 * @package App\Modules\Shared\Misc
 */
class AcquisitionReportViewHelper {

	/* Value of greater than option */
	const SELECT_OPTION_GREATER_THAN = 1;

	/* Value of less than option */
	const SELECT_OPTION_LESS_THAN = 2;

	/* Value of 'equal to' option */
	const SELECT_OPTION_EQUALS_TO = 3;

	/* Value of institute option for 'date range on' select  */
	const SELECT_OPTION_DATE_RANGE_ON_INSTITUTE = 1;

	/* Value of user option for 'date range on' select  */
	const SELECT_OPTION_DATE_RANGE_ON_USER = 2;
}

/**
 * Class SalesVisitViewHelper
 * @package App\Modules\Shared\Misc
 */
class SalesVisitViewHelper {

	/* Value of new institute option */
	const SELECT_OPTION_NEW_INSTITUTE = 1;

	/* Value of existing institute option */
	const SELECT_OPTION_EXISTING_INSTITUTE = 2;
}

/**
 * Class ContentUserReportViewHelper
 * @package App\Modules\Shared\Misc
 */
class ContentUserReportViewHelper {

	/**
	 * Value of job not started option
	 */
	const COPY_CONTENT_JOB_NOT_STARTED = 1;

	/**
	 * Value of job running option
	 */
	const COPY_CONTENT_JOB_RUNNING = 2;

	/**
	 * Value of job completed option
	 */
	const COPY_CONTENT_JOB_COMPLETED = 3;

	/**
	 * Value of job failed option
	 */
	const COPY_CONTENT_JOB_FAILED = 4;
}


/**
 * Class CoursesReviewViewHelper
 * @package App\Modules\Shared\Misc
 */
class CoursesReviewViewHelper
{

	/**
	 * Value of review pending option
	 */
	const SELECT_OPTION_REVIEW_PENDING = 1;

	/**
	 * Value of approved option
	 */
	const SELECT_OPTION_APPROVED = 2;

	/**
	 * Value of not approved option
	 */
	const SELECT_OPTION_NOT_APPROVED = 3;

	/**
	 * Value of deactivated option
	 */
	const SELECT_OPTION_DEACTIVATED = 4;
}

/**
 * Class InstituteListViewHelper
 * @package App\Modules\Shared\Misc
 */
class InstituteListViewHelper {

	/**
	 * Value of plan status verification pending
	 */
	const PLAN_STATUS_VERIFICATION_PENDING = 1;

	/**
	 * Value of plan status verified
	 */
	const PLAN_STATUS_VERIFIED = 2;

	/**
	 * Value of plan status cancelled
	 */
	const PLAN_STATUS_CANCELLED = 3;

	/**
	 * Value of plan status deactivated
	 */
	const PLAN_STATUS_DEACTIVATED = 4;
}

class CambridgeModuleTypeViewHelper {
	const EXISTING_MODULE = 1;

	const NEW_MODULE = 2;

	const SELECT_OPTION_VALUE_ANY = '';
}

class CambridgeSubmissionCategoryViewHelper{

	const CAMBRIDGE_SUBMISSION_CATEGORY_LEARNER = 'learner';

	const CAMBRIDGE_SUBMISSION_CATEGORY_SCHOOL = 'school';

	const CAMBRIDGE_SUBMISSION_CATEGORY_TERTIARY = 'tertiary';
}

class CambridgeSubmissionActivityViewHelper{
	const CAMBRIDGE_SUBMISSION_ACTIVITY_EXAM = 'exam';

	const CAMBRIDGE_SUBMISSION_ACTIVITY_SUBSKILLS = 'subskills';

	const CAMBRIDGE_SUBMISSION_ACTIVITY_LANGUAGE = 'language';
}

class CoursePromotionViewHelper{

	/**
	 * Value of course price status paid
	 */
	const COURSE_PAID = 1;

	/**
	 * Value of course price status free
	 */
	const COURSE_FREE = 2;
}
/*
 * constant for Coupon status
 * */
class CourseVerifyOfflinePaymentCoupon{
	// Generated Coupon code
	const COUPON_GENERATED = 1;

	// Not Generated Coupon code
	const COUPON_NOT_GENERATED = 0;
}
