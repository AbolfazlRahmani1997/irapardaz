<?php

namespace App\Enums;

enum LinkStatus:int
{
    case CREATED = 1;
    case PROCESS = 2;
    case ACTIVE = 3;
}
