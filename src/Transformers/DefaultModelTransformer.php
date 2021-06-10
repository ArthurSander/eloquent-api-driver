<?php

namespace ArthurSander\Drivers\Api\Transformers;

use ArthurSander\Drivers\Api\Contracts\ModelTransformer;
use ArthurSander\Drivers\Api\Model;

class DefaultModelTransformer implements ModelTransformer
{
  protected Model $model;

  public function __construct(Model $model)
  {
    $this->model = $model;
  }

  public function transform(mixed $result): Model
  {
    $result = array_merge([
      'id' => data_get($result,'data.id', null)
    ], (array)data_get($result, 'data.attributes', []));

    return $this->model->newInstance($result);
  }

  public function transformMultiple(mixed $result): array
  {
    if(!is_array($result)){
      return [];
    }

    $models = [];
    foreach ($result as $item) {
      $models[] = $this->transform($item);
    }

    return $models;
  }
}