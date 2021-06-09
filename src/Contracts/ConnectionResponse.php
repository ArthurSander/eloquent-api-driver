<?php

namespace ArthurSander\Drivers\Api\Contracts;

use ArthurSander\Drivers\Api\Model;

interface ConnectionResponse
{
  public function getBody(): ?array;
  public function getRawBody(): ?string;
  public function getCode(): ?int;
  public function getHeaders(): ?array;
  public function getErrors(): ?array;

  public function getModel(): ?Model;

  /**
   * @return Model[]|null
   */
  public function getModels(): ?array;

  public function wasSuccessful(): bool;
}