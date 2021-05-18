<?php

namespace ArthurSander\Tests;

use ArthurSander\Drivers\Api\Model;

/**
 * Class Person
 * @package ArthurSander\Tests
 */
class Person extends Model
{
  public $fillable = [
    'name' => 'string',
    'email' => 'string'
  ];
}