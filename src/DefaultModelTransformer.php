<?php

namespace ArthurSander\Drivers\Api;

use ArthurSander\Drivers\Api\Contracts\ModelTransformer;
use AutoMapperPlus\AutoMapper;
use AutoMapperPlus\Configuration\AutoMapperConfig;
use AutoMapperPlus\DataType;

class DefaultModelTransformer implements ModelTransformer
{
  protected Model $exampleModelInstance;

  public function __construct(Model $exampleModelInstance)
  {
    $this->exampleModelInstance = $exampleModelInstance;
  }

  public function transform(mixed $result): Model
  {
    $mapper = AutoMapper::initialize(function (AutoMapperConfig $config) {
      $config->registerMapping(DataType::ARRAY, $this->exampleModelInstance::class);
    });

    return $mapper->map($result, $this->exampleModelInstance::class);
  }
}