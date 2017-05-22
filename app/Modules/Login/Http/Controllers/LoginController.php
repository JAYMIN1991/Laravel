<?php

namespace App\Modules\Login\Http\Controllers;

use App\Modules\Admin\Auth\Traits\AuthenticateBackOfficeUsersTrait;
use App\Http\Controllers\Controller;

/**
 * Class LoginController
 * @package App\Modules\Login\Http\Controllers
 */
class LoginController extends Controller
{
	use AuthenticateBackOfficeUsersTrait;

}
