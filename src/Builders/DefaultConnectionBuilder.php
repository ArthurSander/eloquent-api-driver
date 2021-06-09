<?php

namespace ArthurSander\Drivers\Api\Builders;

use ArthurSander\Drivers\Api\Connections\DefaultApiConnection;
use ArthurSander\Drivers\Api\Contracts\ApiConnection;
use ArthurSander\Drivers\Api\Contracts\AuthenticationProvider;
use ArthurSander\Drivers\Api\Contracts\ConnectionBuilder;
use ArthurSander\Drivers\Api\Contracts\ModelTransformer;
use ArthurSander\Drivers\Api\Contracts\RouteProvider;

class DefaultConnectionBuilder implements ConnectionBuilder
{
  protected AuthenticationProvider $authProvider;
  protected ModelTransformer $transformer;
  protected RouteProvider $routeProvider;

  public function __construct(
    RouteProvider $routeProvider,
    ModelTransformer $transformer,
    AuthenticationProvider $authenticationProvider
  )
  {
    $this->authProvider = $authenticationProvider;
    $this->transformer = $transformer;
    $this->routeProvider = $routeProvider;
  }

  public function build(): ApiConnection
  {
    return new DefaultApiConnection(
      $this->routeProvider,
      $this->authProvider,
      $this->transformer
    );
  }
}