<?php

namespace ArthurSander\Drivers\Api\Contracts;

interface ConnectionBuilder
{
  public function build(): ApiConnection;
}