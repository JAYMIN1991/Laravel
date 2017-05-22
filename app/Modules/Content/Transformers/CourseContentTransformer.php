<?php

namespace App\Modules\Content\Transformers;

use App\Common\URLHelpers;
use League\Fractal\TransformerAbstract;

/**
 * Class CourseContentTransformer
 * @package namespace App\Modules\Content\Transformers;
 */
class CourseContentTransformer extends TransformerAbstract {

	/**
	 * Transform the \CourseContent entity
	 *
	 * @param array $courseContentDetails
	 *
	 * @return array
	 */
	public function transform( $courseContentDetails ) {
		$transformedCourseContentDetails = [
			'section_id'     => $courseContentDetails['section_id'],
			'section_name'   => $courseContentDetails['section_title'],
			'content_id'     => $courseContentDetails['content_id'],
			'content_type'   => $courseContentDetails['content_type'],
			'content_title'  => $courseContentDetails['content_title'],
			'attach_id'      => $courseContentDetails['attach_id'],
			'attach_file'    => $courseContentDetails['attach_file'],
			'attach_preview' => $courseContentDetails['attach_preview'],
			'copied_from'    => $courseContentDetails['copied_from'],
			'preview_class'  => 'content_preview_class'
		];

		/* check if attachment url is for youtube  */
		if ( URLHelpers::isYoutubeURL($courseContentDetails['attach_file']) ) {
			$transformedCourseContentDetails['content_preview_class'] = 'content-icon-youtube';
			$transformedCourseContentDetails['content_preview_icon'] = 'fa-youtube';
		} elseif ( preg_match('/(ftp|http|https):\/\//i', $courseContentDetails['attach_file']) ) {
			$transformedCourseContentDetails['content_preview_class'] = 'content-icon-link';
			$transformedCourseContentDetails['content_preview_icon'] = 'fa-link';
		} else {
			$extension = pathinfo($courseContentDetails['attach_file'], PATHINFO_EXTENSION);

			if ( in_array($extension, ['jpg', 'jpeg', 'bmp', 'gif', 'png']) ) {
				$transformedCourseContentDetails['content_preview_class'] = 'content-icon-img';
				$transformedCourseContentDetails['content_preview_icon'] = 'fa-file-image-o';
			} elseif ( $extension == 'mp3' ) {
				$transformedCourseContentDetails['content_preview_class'] = "content-icon-${extension}";
				$transformedCourseContentDetails['content_preview_icon'] = 'fa-file-audio-o';
			} elseif ( $extension == 'mp4' ) {
				$transformedCourseContentDetails['content_preview_class'] = "content-icon-${extension}";
				$transformedCourseContentDetails['content_preview_icon'] = "fa-file-video-o";
			} elseif ( $extension == 'pdf' ) {
				$transformedCourseContentDetails['content_preview_class'] = "content-icon-${extension}";
				$transformedCourseContentDetails['content_preview_icon'] = "fa-file-pdf-o";
			} elseif ( in_array($extension, ['doc', 'docx', 'odt']) ) {
				$transformedCourseContentDetails['content_preview_class'] = 'content-icon-word';
				$transformedCourseContentDetails['content_preview_icon'] = 'fa-file-word-o';
			} elseif ( in_array($extension, ['ppt', 'pptx']) ) {
				$transformedCourseContentDetails['content_preview_class'] = 'content-icon-ppt';
				$transformedCourseContentDetails['content_preview_icon'] = 'fa-file-powerpoint-o';
			} elseif ( in_array($extension, ['xls', 'xlsx', 'ods']) ) {
				$transformedCourseContentDetails['content_preview_class'] = 'content-icon-xls';
				$transformedCourseContentDetails['content_preview_icon'] = 'fa-file-excel-o';
			} else {
				$transformedCourseContentDetails['content_preview_class'] = 'content-icon-txt';
				$transformedCourseContentDetails['content_preview_icon'] = 'fa-file-text-o';
			}
		}

		return $transformedCourseContentDetails;
	}
}
