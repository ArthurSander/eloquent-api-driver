<?php

namespace ArthurSander\Drivers\Api\Contracts;

use ArthurSander\Drivers\Api\Model;

interface RequestTransformer
{
  public function transform(Model $model): mixed;
}