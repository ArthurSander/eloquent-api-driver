<?php

namespace ArthurSander\Drivers\Api\Contracts;

use ArthurSander\Drivers\Api\Model;

interface ModelTransformer
{
  /**
   * @param mixed $result
   * @return Model
   *
   * Should transform the result into a model
   */
  public function transform(mixed $result): Model;

  /**
   * @param mixed $result
   * @return Model[]
   *
   * Should transform the result into multiple models
   */
  public function transformMultiple(mixed $result): array;
}