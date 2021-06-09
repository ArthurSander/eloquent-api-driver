<?php

namespace ArthurSander\Drivers\Api\Contracts;

interface ApiConnection
{
  public function find(string $id): ConnectionResponse;

  public function findBy(array $params = []): ConnectionResponse;

  public function findOneBy(array $params = []): ConnectionResponse;

  public function all(): ConnectionResponse;

  public function create(array $attributes): ConnectionResponse;

  public function updateAsPut(string $id, array $attributes): ConnectionResponse;

  public function updateAsPatch(string $id, array $attributes): ConnectionResponse;

  public function delete(string $id): ConnectionResponse;

  public function custom(string $url, string $method, array $payload = []): ConnectionResponse;

  public function setHeaders(array $headers): void;

  public function addHeader(string $key, string $value): void;

  public function removeHeader(string $key): void;

  public function clearHeaders(): void;
}