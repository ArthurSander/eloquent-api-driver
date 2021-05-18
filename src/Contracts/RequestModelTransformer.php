<?php

namespace ArthurSander\Drivers\Api\Contracts;

use ArthurSander\Drivers\Api\Model;

interface RequestModelTransformer
{
  public function transform(Model $model): mixed;
}