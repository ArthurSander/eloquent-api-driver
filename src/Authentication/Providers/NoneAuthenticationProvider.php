<?php

namespace ArthurSander\Drivers\Api\Authentication\Providers;

use ArthurSander\Drivers\Api\Contracts\AuthenticationProvider;
use Httpful\Request;

class NoneAuthenticationProvider implements AuthenticationProvider
{

  public function getHeader(): array
  {
    return [];
  }

  public function setHeader(Request $request): Request
  {
    return $request;
  }
}