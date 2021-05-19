<?php

namespace ArthurSander\Drivers\Api;

use ArthurSander\Drivers\Api\Contracts\AuthenticationProvider;
use ArthurSander\Drivers\Api\Contracts\RouteProvider;
use Httpful\Request;
use Httpful\Response;

class ApiConnection
{
  protected RouteProvider $routeProvider;

  protected AuthenticationProvider $authenticationProvider;

  protected ?array $headers;

  public function __construct(RouteProvider $routeProvider, AuthenticationProvider $authenticationProvider)
  {
    $this->routeProvider = $routeProvider;
    $this->authenticationProvider = $authenticationProvider;
  }

  public function find(string $id): Response
  {
    $request = Request::get($this->routeProvider->find($id));
    return $this->send($request);
  }

  public function findBy(array $params = []): Response
  {
    $request = Request::get($this->routeProvider->findBy($params));
    return $this->send($request);
  }

  public function findOneBy(array $params = []): Response
  {
    $request = Request::get($this->routeProvider->findOneBy($params));
    return $this->send($request);
  }

  public function all(): Response
  {
    $request = Request::get($this->routeProvider->all());
    return $this->send($request);
  }

  public function create(array $attributes): Response
  {
    $request = Request::post($this->routeProvider->create(), $attributes);
    return $this->send($request);
  }

  public function updateAsPut(string $id, array $attributes): Response
  {
    $request = Request::put($this->routeProvider->update($id), $attributes);
    return $this->send($request);
  }

  public function updateAsPatch(string $id, array $attributes): Response
  {
    $request = Request::patch($this->routeProvider->update($id), $attributes);
    return $this->send($request);
  }

  public function delete(string $id): Response
  {
    $request = Request::delete($this->routeProvider->update($id));
    return $this->send($request);
  }

  public function custom(string $url, string $method, array $payload = [])
  {
    $request = Request::init($method);
    $request->uri($url);
    if(!blank($payload)){
      $request->body($payload);
    }
    return $this->send($request);
  }

  public function setHeaders(array $headers): void
  {
    $this->headers = $headers;
  }

  public function addHeader(string $key, string $value): void
  {
    $this->assureHeadersNotNull();
    $this->headers[$key] = $value;
  }

  public function removeHeader(string $key): void
  {
    $this->assureHeadersNotNull();
    unset($this->headers[$key]);
    if(count($this->headers) <= 0) {
      $this->headers = null;
    }
  }

  public function clearHeaders(): void
  {
    $this->headers = null;
  }

  protected function assureHeadersNotNull(): void
  {
    if(is_null($this->headers)){
      $this->headers = [];
    }
  }

  protected function prepareRequestHeaders(Request $request): Request
  {
    if(!blank($this->headers)){
      $request->addHeaders($this->headers);
    }

    return $request;
  }

  private function send(Request $request): Response
  {
    $request = $this->prepareRequestHeaders($request);
    return $request->send();
  }
}