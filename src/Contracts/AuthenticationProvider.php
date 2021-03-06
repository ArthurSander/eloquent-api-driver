<?php

namespace ArthurSander\Drivers\Api\Contracts;

use Httpful\Request;

interface AuthenticationProvider
{
  public function getHeader(): array;
}