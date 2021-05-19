<?php

namespace ArthurSander\Drivers\Api\Contracts;

use ArthurSander\Drivers\Api\Service;

interface ServiceBuilder
{
  public function build(): Service;
}