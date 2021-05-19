<?php

namespace ArthurSander\Drivers\Api\Authentication\Providers;

use ArthurSander\Drivers\Api\Contracts\AuthenticationProvider;
use Httpful\Request;

class ApiKeyAuthenticationProvider implements AuthenticationProvider
{
  protected string $key;
  protected string $value;

  public function __construct(string $key = '', string $value = '')
  {
    $this->key = $key;
    $this->value = $value;
  }

  public function getHeader(): array
  {
    return [
      $this->key => $this->value
    ];
  }

  public function setHeader(Request $request): Request
  {
    return $request->addHeader($this->key, $this->value);
  }
}