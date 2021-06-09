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
  public function transform(array $response): ?Model;

  /**
   * @param mixed $result
   * @return Model[]
   *
   * Should transform the result into multiple models
   */
  public function transformMultiple(array $response): ?array;

}