<?php

declare(strict_types=1);

use App\Contracts\UserRepositoryInterface;
use App\Contracts\VacationRepositoryInterface;
use App\Core\Container;
use App\Repositories\UserRepository;
use App\Repositories\VacationRepository;

/**
 * Register interface bindings in the DI container.
 *
 * @param Container $container
 * @return void
 */
return function (Container $container): void {
    $container->set(UserRepositoryInterface::class, fn () => new UserRepository());
    $container->set(VacationRepositoryInterface::class, fn () => new VacationRepository());
};
