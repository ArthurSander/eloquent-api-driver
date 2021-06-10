<?php

namespace ArthurSander\Drivers\Api\Responses;

use ArthurSander\Drivers\Api\Contracts\ConnectionResponse;
use ArthurSander\Drivers\Api\Contracts\ModelTransformer;
use ArthurSander\Drivers\Api\Model;
use Swis\JsonApi\Client\Interfaces\DocumentInterface;

class JsonApiResponse implements ConnectionResponse
{
  protected DocumentInterface $result;
  protected ModelTransformer $transformer;

  public function __construct(DocumentInterface $result, ModelTransformer $transformer)
  {
    $this->result = $result;
    $this->transformer = $transformer;
  }

  public function getBody(): ?array
  {
    return $this->result->getJsonapi()->toArray();
  }

  public function getRawBody(): ?string
  {
    return json_encode($this->getBody());
  }

  public function getCode(): ?int
  {
    return $this->result->getResponse()->getStatusCode();
  }

  public function getHeaders(): ?array
  {
    return $this->result->getResponse()->getHeaders();
  }

  public function getModel(): ?Model
  {
    $data = $this->result->getData()->toJsonApiArray();
    return $this->transformToModel($data);
  }

  public function wasSuccessful(): bool
  {
    return $this->result->hasErrors();
  }

  public function getErrors(): ?array
  {
    return $this->result->getErrors()->toArray();
  }

  public function getModels(): ?array
  {
    $models = [];
    foreach ($this->result->getData()->toJsonApiArray() as $item){
      $models[] = $this->transformToModel($item);
    }
    return $models;
  }

  private function transformToModel(array $rawModelData): ?Model
  {
    $attributes = array_merge([
      'id' => data_get($rawModelData,'id', null)
    ], (array)data_get($rawModelData, 'attributes', []));
    return $this->transformer->transform($attributes);
  }
}