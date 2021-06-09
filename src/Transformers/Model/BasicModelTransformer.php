<?php

namespace ArthurSander\Drivers\Api\Transformers\Model;

use ArthurSander\Drivers\Api\Contracts\ModelTransformer;
use ArthurSander\Drivers\Api\Model;

class BasicModelTransformer implements ModelTransformer
{
  protected ?Model $model;

  public function __construct(Model $model)
  {
    $this->model = $model;
  }

  public function transform(array $response): ?Model
  {
    return $this->transformUnit($response);
  }

  public function transformMultiple(array $response): ?array
  {
    $models = [];
    foreach($response as $item){
      $models[] = $this->transformUnit($item);
    }
    return $models;
  }

  protected function transformUnit(array $arrModel): ?Model
  {
    return $this->model->newInstance($arrModel);
  }
}