<?php

namespace ArthurSander\Drivers\Api\Contracts;

use ArthurSander\Drivers\Api\Model;

interface ModelTransformer
{
  /**
   * @param mixed $result
   * @return Model
   *
   * Should transform the result to model
   */
  public function transform(mixed $result): Model;
}