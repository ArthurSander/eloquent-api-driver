<?php

namespace ArthurSander\Drivers\Api\Contracts;

interface RouteProvider
{
  public function find(string $id): string;

  public function findBy(array $params = []): string;

  public function findOneBy(array $params = []): string;

  public function all(): string;

  public function create(): string;

  public function update(string $id): string;

  public function delete(string $id): string;
}