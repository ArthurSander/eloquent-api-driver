<?php

namespace ArthurSander\Drivers\Api;

use ArthurSander\Drivers\Api\Enums\ConnectionTypesEnum;
use Illuminate\Support\Collection;

abstract class Model extends \Illuminate\Database\Eloquent\Model
{
  public static ?string $connectionUrl;
  protected static ?string $connectionType = ConnectionTypesEnum::DATABASE;

  public static function all($columns = ['*'])
  {
    switch (self::$connectionType) {
      case ConnectionTypesEnum::DATABASE:
        return parent::all($columns);
      case ConnectionTypesEnum::API:
        return self::queryApi();
    }
    return parent::all($columns);
  }

  protected static function queryApi(): ?Collection
  {

  }
}