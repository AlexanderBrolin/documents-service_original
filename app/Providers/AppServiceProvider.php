<?php

namespace App\Providers;

use App\Repositories\Document\DocumentRepository;
use App\Repositories\Document\DocumentRepositoryInterface;
use App\Repositories\DocumentImage\DocumentImageRepository;
use App\Repositories\DocumentImage\DocumentImageRepositoryInterface;
use App\Repositories\Field\FieldRepository;
use App\Repositories\Field\FieldRepositoryInterface;
use App\Repositories\Individual\IndividualRepository;
use App\Repositories\Individual\IndividualRepositoryInterface;
use App\Repositories\Role\RoleRepository;
use App\Repositories\Role\RoleRepositoryInterface;
use App\Repositories\Task\TaskRepository;
use App\Repositories\Task\TaskRepositoryInterface;
use App\Repositories\User\UserRepository;
use App\Repositories\User\UserRepositoryInterface;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{

    public $bindings = [
        UserRepositoryInterface::class => UserRepository::class,
        RoleRepositoryInterface::class => RoleRepository::class,
        TaskRepositoryInterface::class => TaskRepository::class,
        IndividualRepositoryInterface::class => IndividualRepository::class,
        DocumentRepositoryInterface::class => DocumentRepository::class,
        DocumentImageRepositoryInterface::class => DocumentImageRepository::class,
        FieldRepositoryInterface::class => FieldRepository::class
    ];

    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        //
    }
}
