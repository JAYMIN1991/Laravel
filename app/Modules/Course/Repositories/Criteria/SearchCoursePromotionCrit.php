<?php

namespace App\Modules\Course\Repositories\Criteria;

use App\Common\GeneralHelpers;
use Flinnt\Repository\Criteria\AbstractCriteria;
use Flinnt\Repository\Contracts\RepositoryInterface;
use Illuminate\Database\Eloquent\Builder;

/**
 * Class SearchPromotedCourseCrit
 * @package namespace App\Modules\Course\Repositories\Criteria;
 */
class SearchCoursePromotionCrit extends AbstractCriteria {

	/**
	 * Apply criteria in query repository
	 *
	 * @param                     $model
	 * @param RepositoryInterface $repository
	 *
	 * @return mixed
	 */
	public function apply( $model, RepositoryInterface $repository ) {
		$request = request();
		/** @var Builder $model */

		/* Apply institute filter */
		if ( $request->has('institute_id') ) {
			$model->where(TABLE_COURSES . '.course_owner', GeneralHelpers::decode($request->input('institute_id')));
		}

		/* Apply course filter */
		if ( $request->has('course_id') ) {
			$model->where(TABLE_COURSES . '.course_id', GeneralHelpers::decode($request->input('course_id')));
		}

		/* Apply price filter */
		if ( $request->has('price_id') ) {
			$priceType = 1;
			if ( $request->input('price_id') == 1 ) {
				$priceType = 0;
			}
			$model->where(TABLE_COURSES . '.course_is_free', $priceType);
		}

		/* Apply public type filter */
		if ( $request->has('public_type') ) {
			$model->where(TABLE_COURSES . '.course_public_type_id', $request->input('public_type'));
		}

		/* Apply location filter only when you are on search page. and some location filter is provided in request */
		if ( $request->route()
		             ->getName() == 'course.promotion.index' && $request->has('location') && count($request->input('location')) > 0
		) {
			$model->whereIn(TABLE_COURSE_PROMO_LOCATIONS . '.promo_loc_id', $request->input('location'));
		}

		return $model;
	}
}
