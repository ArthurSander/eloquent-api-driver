<?php

namespace ArthurSander\Drivers\Api;

use ArthurSander\Drivers\Api\Authentication\AuthenticationProviderFactory;
use ArthurSander\Drivers\Api\Contracts\ServiceBuilder;

class ApiServiceBuilder implements ServiceBuilder
{
  protected Model $model;
  protected AuthenticationProviderFactory $authenticationProviderFactory;

  public function __construct(Model $model)
  {
    $this->model = $model;
    $this->authenticationProviderFactory = new AuthenticationProviderFactory();
  }

  public function build(): Service
  {
    $service = new Service();

    $service->setModel($this->model);
    $service->setConnection(new ApiConnection($this->model->getRouteProvider(), $this->authenticationProviderFactory->create($this->model)));
    $service->setModelTransformer($this->model->getTransformer());
    $service->setRequestTransformer($this->model->getRequestTransformer());

    return $service;
  }
}