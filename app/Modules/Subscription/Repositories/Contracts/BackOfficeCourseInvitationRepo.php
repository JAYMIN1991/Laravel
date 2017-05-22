<?php

namespace App\Modules\Subscription\Repositories\Contracts;

use App\Modules\Subscription\Repositories\BackOfficeCourseInvitation;
use Flinnt\Repository\Contracts\RepositoryInterface;
use Illuminate\Support\Collection;

/**
 * Interface BackOfficeCourseInvitationRepo
 * @package namespace App\Modules\Subscription\Repositories\Contracts;
 * @see     BackOfficeCourseInvitation
 */
interface BackOfficeCourseInvitationRepo extends RepositoryInterface {

	/**
	 * Insert the record in database
	 *
	 * @param array $attributes Data to insert into database
	 *
	 * @return bool|int If inserted successfully returns id, otherwise false
	 * @throws \Exception
	 */
	public function inviteFromBackOffice( array $attributes );

	/**
	 * Get the back office course invitation list
	 *
	 * @param string $status Status of invitation, Default : 'READY'
	 *
	 * @return Collection
	 */
	public function getCourseInvitationList( $status = BKOFF_COURSE_INVITE_READY );

	/**
	 * Update the record of course invitation
	 *
	 * @param array $data         Data to update in course invitation
	 * @param int   $invitationId Id of the record
	 *
	 * @return mixed
	 */
	public function updateCourseInvitation( array $data, $invitationId );

	/**
	 * Update the status of course invitation
	 *
	 * @param string    $status        Course invitation status
	 * @param array|int $invitationIds Id/s of the record
	 */
	public function updateCourseInvitationStatus( $status, $invitationIds );
}
