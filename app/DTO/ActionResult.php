<?php

namespace App\DTO;

class ActionResult
{

    public function __construct(
        public bool $success,
        public ?string $message = null,
        public mixed $data = null
    ) {}
}
