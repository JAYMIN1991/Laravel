<?php

namespace App\Modules\Subscription\Repositories;

use Exception;
use Flinnt\Repository\Eloquent\BaseRepository;
use App\Modules\Subscription\Repositories\Contracts\BackOfficeCourseInvitationRepo;
use Illuminate\Support\Collection;

/**
 * Class BackOfficeCourseInvitation
 * @package namespace App\Modules\Subscription\Repositories;
 */
class BackOfficeCourseInvitation extends BaseRepository implements BackOfficeCourseInvitationRepo {

	/**
	 * Primary Key
	 * @var String
	 */
	protected $primaryKey = 'invitation_id';


	/**
	 * Specify Tablename
	 *
	 * @return string
	 */
	public function model() {
		return TABLE_BKOFF_COURSE_INVITATIONS;
	}


	/**
	 * Boot up the repository, pushing criteria
	 */
	public function boot() {
	}

	/**
	 * Insert the record in database
	 *
	 * @param array $attributes Data to insert into database
	 *
	 * @return bool|int If inserted successfully returns id, otherwise false
	 * @throws \Exception
	 */
	public function inviteFromBackOffice( array $attributes ) {
		$id = null;
		try {
			$id = $this->insertGetId($attributes);
		} catch ( Exception $e ) {
			throw $e;
		}

		return $id;
	}

	/**
	 * Get the back office course invitation list
	 *
	 * @param string $status Status of invitation, Default : 'READY'
	 *
	 * @return Collection
	 */
	public function getCourseInvitationList( $status = BKOFF_COURSE_INVITE_READY ) {

		$result = $this->where('invite_status', '=', $status)->whereNotNull('user_id')->get([
			'invitation_id',
			'course_id',
			'user_id',
			'invite_bkoff_user'
		]);

		return $this->parserResult($result);
	}

	/**
	 * Update the record of course invitation
	 *
	 * @param array $data         Data to update in course invitation
	 * @param int   $invitationId Id of the record
	 *
	 * @return mixed
	 */
	public function updateCourseInvitation( array $data, $invitationId ) {
		return $this->updateById($data, $invitationId);
	}

	/**
	 * Update the status of course invitation
	 *
	 * @param string $status        Course invitation status
	 * @param int    $invitationIds Id/s of the record
	 *
	 * @return int
	 */
	public function updateCourseInvitationStatus( $status, $invitationIds ) {
		$invitationId = $invitationIds;

		if ( ! is_array($invitationIds) ) {
			$invitationId = [$invitationIds];
		}

		return $this->whereIn('invitation_id', $invitationId)->update(['invite_status' => $status]);
	}
}
