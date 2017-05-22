<?php

namespace App\Modules\Admin\Providers;

use App\Modules\Admin\Repositories\Contracts\AdminUsersRepo;
use App\Modules\Admin\Repositories\AdminUsers;
use App\Modules\Admin\Repositories\Contracts\AdminUserIPRepo;
use App\Modules\Admin\Repositories\AdminUserIP;
use Illuminate\Support\ServiceProvider;

/**
 * Class RepositoryServiceProvider
 * @package App\Modules\Admin\Providers
 */
class RepositoryServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap the application services.
     *
     * @return void
     */
    public function boot()
    {
        //
    }

    /**
     * Register the application services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->bind(AdminUsersRepo::class, AdminUsers::class);
	    $this->app->bind(AdminUserIPRepo::class, AdminUserIP::class);
        //:end-bindings:
    }
}
