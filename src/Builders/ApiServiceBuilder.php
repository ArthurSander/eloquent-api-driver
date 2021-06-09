<?php

namespace ArthurSander\Drivers\Api\Builders;

use ArthurSander\Drivers\Api\Authentication\AuthenticationProviderFactory;
use ArthurSander\Drivers\Api\Contracts\ApiConnection;
use ArthurSander\Drivers\Api\Contracts\ServiceBuilder;
use ArthurSander\Drivers\Api\Model;
use ArthurSander\Drivers\Api\Service;

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
    $service->setConnection($this->model->getApiConnectionBuilder()->build());
    $service->setModelTransformer($this->model->getTransformer());
    $service->setRequestTransformer($this->model->getRequestTransformer());

    return $service;
  }
}