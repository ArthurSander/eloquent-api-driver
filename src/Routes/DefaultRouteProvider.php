<?php

namespace ArthurSander\Drivers\Api\Routes;

use ArthurSander\Drivers\Api\Contracts\RouteProvider;
use ArthurSander\Drivers\Api\Model;
use Illuminate\Support\Facades\Config;

class DefaultRouteProvider implements RouteProvider
{
  protected Model $model;

  public function __construct(Model $model)
  {
    $this->model = $model;
  }

  public function find(string $id): string
  {
    $baseUrl = $this->getUrl();
    return $baseUrl . '/' . $id;
  }

  public function findBy(array $params = []): string
  {
    return $this->getUrl();
  }

  public function findOneBy(array $params = []): string
  {
    return $this->getUrl();
  }

  public function all(): string
  {
    return $this->getUrl();
  }

  public function create(): string
  {
    return $this->getUrl();
  }

  public function update(string $id): string
  {
    return $this->getUrl() . '/' . $id;
  }

  public function delete(string $id): string
  {
    return $this->getUrl() . '/' . $id;
  }

  private function getUrl(): string
  {
    $baseUrl = $this->model->baseUrl ?? Config::get('database.api.base_url');

    if(blank($baseUrl)){
      return '';
    }

    return $baseUrl . '/' . $this->model->baseUri;
  }

}