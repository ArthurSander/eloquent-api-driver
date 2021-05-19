<?php

namespace ArthurSander\Drivers\Api\Transformers;

use ArthurSander\Drivers\Api\Contracts\RequestModelTransformer;
use ArthurSander\Drivers\Api\Model;

class DefaultRequestTransformer implements RequestModelTransformer
{

  public function transform(Model $model): mixed
  {
    return $model->toArray();
  }
}