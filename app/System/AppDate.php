<?php

namespace App\System;

use DateTimeImmutable;

class AppDate extends DateTimeImmutable
{
    public function __toString()
    {
        return $this->format('Y-m-d H:i:s');
    }
}
