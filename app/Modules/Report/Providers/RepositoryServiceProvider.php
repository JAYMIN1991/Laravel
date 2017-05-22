<?php

namespace App\Modules\Report\Providers;

use App\Modules\Report\Repositories\Contracts\LMSCopyContentRepo;
use App\Modules\Report\Repositories\LMSCopyContent;
use Illuminate\Support\ServiceProvider;

/**
 * Class RepositoryServiceProvider
 * @package App\Modules\Report\Providers
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
        $this->app->bind(LMSCopyContentRepo::class, LMSCopyContent::class);
        //:end-bindings:
    }
}
