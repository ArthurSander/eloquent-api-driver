<?php

namespace ArthurSander\Drivers\Api;

use ArthurSander\Drivers\Api\Contracts\ModelTransformer;
use ArthurSander\Drivers\Api\Contracts\RequestModelTransformer;
use Httpful\Response;

class Service
{
  private Model $model;

  private ApiConnection $connection;

  private ModelTransformer $modelTransformer;

  private RequestModelTransformer $requestTransformer;

  /**
   * @param string $id
   * @return Model|null
   * @throws \Exception
   */
  public function find(string $id): ?Model
  {
    $this->verifyIntegrity();

    $result = $this->connection->find($id);

    if(!$this->wasSuccessful($result, false)){
      return null;
    }

    return $this->modelTransformer->transform($result->body);
  }

  /**
   * @param string $id
   * @return Model[]
   */
  public function findBy(array $params): array
  {
    $this->verifyIntegrity();

    $result = $this->connection->findBy($params);

    if(!$this->wasSuccessful($result, false)){
      return [];
    }

    return $this->modelTransformer->transformMultiple($result->body);
  }

  public function findOneBy(array $params): ?Model
  {
    $this->verifyIntegrity();

    $result = $this->connection->findOneBy($params);

    if(!$this->wasSuccessful($result, false)){
      return null;
    }

    return $this->modelTransformer->transform(data_get($result, 0));
  }

  /**
   * @return Model[]
   */
  public function all(): array
  {
    $this->verifyIntegrity();

    $result = $this->connection->all();

    if(!$this->wasSuccessful($result, false)){
      return [];
    }

    return $this->modelTransformer->transformMultiple($result->body);
  }

  public function save()
  {
    $this->verifyIntegrity();

    $result = $this->createOrUpdate();

    if(!$this->wasSuccessful($result, false)){
      return null;
    }

    return $this->modelTransformer->transform($result);
  }

  public function delete(): bool
  {
    $this->verifyIntegrity();

    if(blank($this->model->getKey())){
      return false;
    }

    $result = $this->connection->delete($this->model->getKey());

    if(!$this->wasSuccessful($result, false)){
      return false;
    }

    return true;
  }

  /**
   * @param Model $model
   */
  public function setModel(Model $model): void
  {
    $this->model = $model;
  }

  /**
   * @param ApiConnection $connection
   */
  public function setConnection(ApiConnection $connection): void
  {
    $this->connection = $connection;
  }

  /**
   * @param ModelTransformer $modelTransformer
   */
  public function setModelTransformer(ModelTransformer $modelTransformer): void
  {
    $this->modelTransformer = $modelTransformer;
  }

  /**
   * @param RequestModelTransformer $requestTransformer
   */
  public function setRequestTransformer(RequestModelTransformer $requestTransformer): void
  {
    $this->requestTransformer = $requestTransformer;
  }

  private function verifyIntegrity(): void
  {
    if(blank($this->model)){
      throw new \Exception('Model has not been configured.');
    }

    if(blank($this->connection)){
      throw new \Exception('Connection has not been configured.');
    }

    if(blank($this->modelTransformer)){
      throw new \Exception('Model Transformer has not been configured.');
    }

    if(blank($this->requestTransformer)){
      throw new \Exception('Request Transformer has not been configured.');
    }
  }

  private function wasSuccessful(Response $response, bool $nullable = true): bool
  {
    if(!$nullable && blank($response->raw_body)){
      return false;
    }

    if($response->code < 199 || $response->code > 300) {
      return false;
    }

    return true;
  }

  private function createOrUpdate(): Response
  {
    return blank($this->model->getKey()) ?
      $this->create() :
      $this->udpate();
  }

  private function create(): Response
  {
    $attributes = $this->requestTransformer->transform($this->model);
    return $this->connection->create($attributes);
  }

  private function udpate(): Response
  {
    $attributes = $this->requestTransformer->transform($this->model);
    return $this->connection->updateAsPatch(strval($this->model->getKey()), $attributes);
  }
}