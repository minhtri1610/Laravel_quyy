<?php

declare(strict_types=1);

namespace App\Providers;

use Illuminate\Support\ServiceProvider;

use App\Models\Repositories\Contracts\RepositoryInterface;

use App\Models\Repositories\Contracts\UserServiceRepositoryInterface;
use App\Models\Repositories\Contracts\TemporaryUserServiceRepositoryInterface;
// add Interface

use App\Models\Repositories\Eloquent\Repository;


use App\Models\Repositories\Eloquent\UserServiceRepository;
use App\Models\Repositories\Eloquent\TemporaryUserServiceRepository;
// add Repository

class RepositoryServiceProvider extends ServiceProvider
{
    /**
     *
     * @return array
     */
    private function repositories()
    {
        return [
            RepositoryInterface::class => Repository::class,

            UserServiceRepositoryInterface::class => UserServiceRepository::class,
            TemporaryUserServiceRepositoryInterface::class => TemporaryUserServiceRepository::class,
            // add pair Interface Repository
        ];
    }

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
        foreach ($this->repositories() as $interface => $concrete) {
            $this->app->bind($interface, $concrete);
        }
    }
}
