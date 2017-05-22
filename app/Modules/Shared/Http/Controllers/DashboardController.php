<?php

namespace App\Modules\Shared\Http\Controllers;

use App\Http\Controllers\Controller;
use View;

/**
 * Class DashboardController
 * @package App\Modules\Shared\Http\Controllers
 */
class DashboardController extends Controller {

	/**
	 * Function returns dashboard view
	 *
	 * @return mixed
	 */
	public function index() {
		return View::make('shared::dashboard');
	}
}
