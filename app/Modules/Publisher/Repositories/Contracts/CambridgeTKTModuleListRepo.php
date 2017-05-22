<?php

namespace App\Modules\Publisher\Repositories\Contracts;

use Flinnt\Repository\Contracts\RepositoryInterface;

/**
 * Interface CambridgeTKTModuleListRepo
 * @package namespace App\Modules\Publisher\Repositories\Contracts;
 * @see     CambridgeTKTModuleListRepo
 */
interface CambridgeTKTModuleListRepo extends RepositoryInterface {

	// get cambridge TKT exam module list
	public function getCambridgeTKTExamModuleList();

	// get TKT exam city list
	public function getCambridgeTKTExamCityList();

	// get cambridge TKT exam search result
	public function getCambridgeTKTExamSearch( $pagination = false );

	// insert cambridge TKT exam records
	public function insertCambridgeTKTExamData( $CambridgeTKTExamData );

	// update cambridge TKT exam records
	public function CambridgeTKTExamDataUpdate( $CambridgeTKTExamUpdateData, $CambridgeTKTExamRequestId );
}
