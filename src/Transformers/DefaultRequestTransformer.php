<?php

namespace ArthurSander\Drivers\Api\Transformers;

use ArthurSander\Drivers\Api\Contracts\RequestTransformer;
use ArthurSander\Drivers\Api\Model;

class DefaultRequestTransformer implements RequestTransformer
{

  public function transform(Model $model): mixed
  {
    return $model->toArray();
  }
}