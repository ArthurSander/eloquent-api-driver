<?php

namespace ArthurSander\Drivers\Api\Responses;

use ArthurSander\Drivers\Api\Contracts\ConnectionResponse;
use ArthurSander\Drivers\Api\Contracts\ModelTransformer;
use ArthurSander\Drivers\Api\Model;
use Httpful\Response;

class BasicApiResponse implements ConnectionResponse
{
  protected Response $response;
  protected ModelTransformer $transformer;

  public function __construct(Response $response, ModelTransformer $transformer)
  {
    $this->response = $response;
    $this->transformer = $transformer;
  }

  public function getBody(): ?array
  {
    return (array)$this->response->body;
  }

  public function getRawBody(): ?string
  {
    return $this->response->raw_body;
  }

  public function getCode(): ?int
  {
    return $this->response->code;
  }

  public function getHeaders(): ?array
  {
    return $this->response->headers?->toArray();
  }

  public function wasSuccessful(): bool
  {
    return !$this->response->hasErrors();
  }

  public function getModel(): ?Model
  {
    return $this->transformToModel(data_get($this->getBody(), 'data', []));
  }

  public function getErrors(): ?array
  {
    $body = $this->getBody();
    return data_get($body, 'errors', data_get($body, '*.errors', data_get($body, '*.*.errors')));
  }

  public function getModels(): ?array
  {
    $models = [];
    foreach($this->getBody() as $arrModel){
      $models[] = $this->transformToModel($arrModel);
    }
    return $models;
  }

  private function transformToModel(array $result): ?Model
  {
    $attributes = array_merge([
      'id' => data_get($result,'id', null)
    ], (array)data_get($result, 'attributes', []));

    return $this->transformer->transform($attributes);
  }
}