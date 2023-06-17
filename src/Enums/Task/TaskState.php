<?php

namespace App\Enums\Task;

enum TaskState: string
{
    case CREATED = "CREATED";
    case ASSIGNED = "ASSIGNED";
    case COMPLETED = "COMPLETED";
    case ARCHIVED = "ARCHIVED";

    public function toString(): ?string
    {
        return match ($this) {
            self::CREATED => 'Created',
            self::ASSIGNED => 'Assigned',
            self::COMPLETED => 'Completed',
            self::ARCHIVED => 'Archived',
        };
    }
}