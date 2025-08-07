<?php

namespace App\Enums;

enum StatusEnum: int
{
    case APPROVED = 1;
    case REJECTED = 2;
    case PENDING = 3;
}
