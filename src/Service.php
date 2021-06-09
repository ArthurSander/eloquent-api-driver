<?php

namespace ArthurSander\Drivers\Api;

use ArthurSander\Drivers\Api\Contracts\ApiConnection;
use ArthurSander\Drivers\Api\Contracts\ModelTransformer;
use ArthurSander\Drivers\Api\Contracts\RequestTransformer;

class Service
{
  private Model $model;

  private ApiConnection $connection;

  private ModelTransformer $modelTransformer;

  private RequestTransformer $requestTransformer;

  /**
   * @param string $id
   * @return Model|null
   * @throws \Exception
   */
  public function find(string $id): ?Model
  {
    $this->verifyIntegrity();

    $result = $this->connection->find($id);

    return $this->modelTransformer->transform($result);
  }

  /**
   * @param string $id
   * @return Model[]
   */
  public function findBy(array $params): array
  {
    $this->verifyIntegrity();

    $result = $this->connection->findBy($params);

    return $this->modelTransformer->transformMultiple($result);
  }

  public function findOneBy(array $params): ?Model
  {
    $this->verifyIntegrity();

    $result = $this->connection->findOneBy($params);

    return $this->modelTransformer->transform(data_get($result, 0));
  }

  /**
   * @return Model[]
   */
  public function all(): array
  {
    $this->verifyIntegrity();

    $result = $this->connection->all();

    return $this->modelTransformer->transformMultiple($result);
  }

  public function save()
  {
    $this->verifyIntegrity();

    $result = $this->createOrUpdate();

    return $this->modelTransformer->transform($result);
  }

  public function delete(): bool
  {
    $this->verifyIntegrity();

    if(blank($this->model->getKey())){
      return false;
    }

    $this->connection->delete($this->model->getKey());

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
   * @param RequestTransformer $requestTransformer
   */
  public function setRequestTransformer(RequestTransformer $requestTransformer): void
  {
    $this->requestTransformer = $requestTransformer;
  }

  public function addHeader(string $key, string $value): Service
  {
    $this->connection->addHeader($key, $value);
    return $this;
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

  private function createOrUpdate(): array
  {
    return blank($this->model->getKey()) ?
      $this->create() :
      $this->udpate();
  }

  private function create(): array
  {
    $attributes = $this->requestTransformer->transform($this->model);
    return $this->connection->create($attributes);
  }

  private function udpate(): array
  {
    $attributes = $this->requestTransformer->transform($this->model);
    return $this->connection->updateAsPatch(strval($this->model->getKey()), $attributes);
  }
}