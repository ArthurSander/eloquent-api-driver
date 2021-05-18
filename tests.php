<?php

require 'Tests/Person.php';
require 'src/Model.php';
require "src/DefaultModelTransformer.php";


$exampleModel = new \ArthurSander\Tests\Person();
$transformer = new \ArthurSander\Drivers\Api\DefaultModelTransformer($exampleModel);

var_dump($transformer->transform([
  'name' => 'Arthur Teste',
  'email' => 'arthur.sanderj@gmail.com'
]));