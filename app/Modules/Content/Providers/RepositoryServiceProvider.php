<?php

namespace App\Modules\Content\Providers;
use App\Modules\Content\Repositories as Repositories;
use Illuminate\Support\ServiceProvider;

/**
 * Class RepositoryServiceProvider
 * @package App\Modules\Content\Providers
 */
class RepositoryServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap the application services
     *
     * @return void
     */
    public function boot(){
    }

    /**
     * Register the application services
     *
     * @return void
     */
    public function register()
    {
        $this->app->bind(Repositories\Contracts\LMSSectionsRepo::class, Repositories\LMSSections::class);
        $this->app->bind(Repositories\Contracts\LMSContentsRepo::class, Repositories\LMSContents::class);
        $this->app->bind(Repositories\Contracts\CourseCategoriesRepo::class, Repositories\CourseCategories::class);
        //:end-bindings:
    }
}
