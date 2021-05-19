<?php

namespace ArthurSander\Drivers\Api\Authentication\Providers;

use ArthurSander\Drivers\Api\Contracts\AuthenticationProvider;
use Httpful\Request;

class BearerAuthenticationProvider implements AuthenticationProvider
{
  protected string $token;

  public function __construct(string $token = '')
  {
    $this->token = $token;
  }

  public function getHeader(): array
  {
    return [
      'Authorization' => $this->getHeaderValue()
    ];
  }

  public function setHeader(Request $request): Request
  {
    return $request->addHeader('Authorization', $this->getHeaderValue());
  }

  private function getHeaderValue(): string
  {
    return 'Bearer ' . $this->token;
  }
}