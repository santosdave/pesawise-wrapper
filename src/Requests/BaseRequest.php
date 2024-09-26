<?php

namespace Santosdave\PesawiseWrapper\Requests;

use Santosdave\PesawiseWrapper\Traits\ValidationTrait;

abstract class BaseRequest
{
    use ValidationTrait;

    /**
     * Validate the request data
     *
     * @return void
     * @throws \InvalidArgumentException
     */
    abstract public function validate(): void;

    /**
     * Convert the request to an array
     *
     * @return array
     */
    abstract public function toArray(): array;
}