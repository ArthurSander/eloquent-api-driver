<?php

namespace ArthurSander\Drivers\Api\Authentication\Providers;

use ArthurSander\Drivers\Api\Contracts\AuthenticationProvider;
use Httpful\Request;

class BasicAuthenticationProvider implements AuthenticationProvider
{
  protected string $username;
  protected string $password;

  public function __construct(string $username = '', string $password = '')
  {
    $this->username = $username;
    $this->password = $password;
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
    return 'Basic ' . base64_encode($this->username . ':' . $this->password);
  }
}