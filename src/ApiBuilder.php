<?php

namespace ArthurSander\Drivers\Api;

use Httpful\Request;

class ApiBuilder
{
  protected string $connection;
  public function __construct(Model $model)
  {
    $this->connection = $model::$connectionUrl ?? '';
  }

  public function get()
  {
    $result = Request::get($this->connection);
  }
}