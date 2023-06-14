<?php

namespace App\Enums\Task;

enum TaskState: string
{
    case CREATED = "CREATED";
    case ASSIGNED = "ASSIGNED";
    case COMPLETED = "COMPLETED";
    case ARCHIVED = "ARCHIVED";
}