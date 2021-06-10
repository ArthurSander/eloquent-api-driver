<?php

namespace ArthurSander\Drivers\Api;

use ArthurSander\Drivers\Api\Authentication\AuthenticationProviderFactory;
use ArthurSander\Drivers\Api\Builders\ApiServiceBuilder;
use ArthurSander\Drivers\Api\Builders\DefaultConnectionBuilder;
use ArthurSander\Drivers\Api\Contracts\ConnectionBuilder;
use ArthurSander\Drivers\Api\Contracts\ModelTransformer;
use ArthurSander\Drivers\Api\Contracts\RequestTransformer;
use ArthurSander\Drivers\Api\Contracts\RouteProvider;
use ArthurSander\Drivers\Api\Enums\AuthenticationTypes;
use ArthurSander\Drivers\Api\Enums\ConnectionTypes;
use ArthurSander\Drivers\Api\Routes\DefaultRouteProvider;
use ArthurSander\Drivers\Api\Transformers\DefaultModelTransformer;
use ArthurSander\Drivers\Api\Transformers\DefaultRequestTransformer;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Collection;
use Swis\JsonApi\Client\Interfaces\ItemDocumentInterface;

abstract class Model extends \Illuminate\Database\Eloquent\Model
{
  public ?string $baseUrl;

  public ?string $baseUri;

  protected ?string $connectionType = ConnectionTypes::DATABASE;

  public ?string $authenticationType = AuthenticationTypes::NONE;

  public ?string $username;

  public ?string $password;

  public ?string $token;

  public ?string $authenticationApiKey;

  public ?string $authenticationApiValue;


  public static function all($columns = ['*']): Collection
  {
    switch ((new static)->connectionType) {
      case ConnectionTypes::DATABASE:
        return parent::all($columns);
      case ConnectionTypes::API:
        return collect(self::getService()->all());
    }
    return parent::all($columns);
  }

  public static function find(string $id): ?Model
  {
    switch ((new static)->connectionType) {
      case ConnectionTypes::DATABASE:
        return parent::query()->find($id);
      case ConnectionTypes::API:
        return self::getService()->find($id);
    }
    return parent::query()->find($id);
  }

  public static function findOneBy(array $params = []): ?Model
  {
    switch ((new static)->connectionType) {
      case ConnectionTypes::DATABASE:
        return self::dbPrepareQueryFindBy($params)->first();
      case ConnectionTypes::API:
        return self::getService()->findOneBy($params);
    }
    return self::dbFindBy($params)->first();
  }

  public static function findBy(array $params = []): Collection
  {
    switch ((new static)->connectionType) {
      case ConnectionTypes::DATABASE:
        return self::dbPrepareQueryFindBy($params)->get();
      case ConnectionTypes::API:
        return collect(self::getService()->findOneBy($params));
    }
    return self::dbFindBy($params)->first();
  }

  public function save(array $options = [])
  {
    switch ((new static)->connectionType) {
      case ConnectionTypes::DATABASE:
        return parent::save($options);
      case ConnectionTypes::API:
        $result = self::getService($this)->save();
        $this->attributes = $result->attributes;
        return $result;
    }
    return parent::save($options);
  }

  public function delete()
  {
    switch ((new static)->connectionType) {
      case ConnectionTypes::DATABASE:
        return parent::delete();
      case ConnectionTypes::API:
        return self::getService($this)->delete();
    }
    return parent::delete();
  }

  protected static function getService(?Model $model = null): Service
  {
    if(blank($model)){
      $model = new static;
    }
    return (new ApiServiceBuilder($model))->build();
  }

  public function getTransformer(): ModelTransformer
  {
    return new DefaultModelTransformer((new static));
  }

  public function getRequestTransformer(): RequestTransformer
  {
    return new DefaultRequestTransformer();
  }

  public function getRouteProvider(): RouteProvider
  {
    return new DefaultRouteProvider($this);
  }

  public function getApiConnectionBuilder(): ConnectionBuilder
  {
    return new DefaultConnectionBuilder(
      $this->getRouteProvider(),
      $this->getTransformer(),
      (new AuthenticationProviderFactory())->create($this)
    );
  }

  protected static function dbPrepareQueryFindBy(array $params): Builder
  {
    $query = parent::query();
    foreach ($params as $key => $value){
      $query->where($key, '=', $value);
    }
    return $query;
  }



}