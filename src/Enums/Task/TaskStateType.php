<?php

namespace App\Enums\Task;

use App\Enums\Task\TaskState;
use Doctrine\DBAL\Types\StringType;
use Doctrine\DBAL\Platforms\AbstractPlatform;

class TaskStateType extends StringType
{
    public function getName(): string
    {
        return 'task_state';
    }

    public function convertToDatabaseValue($value, AbstractPlatform $platform)
    {
        if ($value instanceof TaskState) {
            return $value->value;
        }

        return $value;
    }

    public function convertToPHPValue($value, AbstractPlatform $platform)
    {
        if ($value !== null) {
            return TaskState::from($value);
        }

        return $value;
    }

    public function getMappedDatabaseTypes(AbstractPlatform $platform)
    {
        return ['string'];
    }
}
