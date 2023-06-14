<?php

namespace App;

use Symfony\Bundle\FrameworkBundle\Kernel\MicroKernelTrait;
use Symfony\Component\HttpKernel\Kernel as BaseKernel;
use Doctrine\DBAL\Types\Type;
use App\Enums\Task\TaskStateType;

class Kernel extends BaseKernel
{
    use MicroKernelTrait;

    public function boot()
    {
        parent::boot();

        if(!Type::hasType('task_state')) {
            Type::addType('task_state', 'App\Enums\Task\TaskStateType');
        }
    }
}
