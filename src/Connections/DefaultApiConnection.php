<?php

namespace ArthurSander\Drivers\Api\Connections;

use ArthurSander\Drivers\Api\Contracts\ApiConnection;
use ArthurSander\Drivers\Api\Contracts\AuthenticationProvider;
use ArthurSander\Drivers\Api\Contracts\ConnectionResponse;
use ArthurSander\Drivers\Api\Contracts\ModelTransformer;
use ArthurSander\Drivers\Api\Contracts\RouteProvider;
use ArthurSander\Drivers\Api\Responses\BasicApiResponse;
use ArthurSander\Drivers\Api\Responses\JsonApiResponse;
use Httpful\Request;
use Httpful\Response;
use Swis\JsonApi\Client\DocumentClient;
use Swis\JsonApi\Client\Interfaces\DocumentInterface;

class DefaultApiConnection implements ApiConnection
{
  protected RouteProvider $routeProvider;
  protected AuthenticationProvider $authenticationProvider;
  protected ModelTransformer $transformer;

  protected ?DocumentClient $client;

  protected ?array $headers = null;

  public function __construct(
    RouteProvider $routeProvider,
    AuthenticationProvider $authenticationProvider,
    ModelTransformer $transformer
  )
  {
    $this->routeProvider = $routeProvider;
    $this->authenticationProvider = $authenticationProvider;
    $this->transformer = $transformer;

    $this->client = DocumentClient::create();
  }

  public function find(string $id): ConnectionResponse
  {
    $result = $this->client->get($this->routeProvider->find($id), $this->getHeadersForRequest());
    return $this->getJsonApiResponse($result);
  }

  public function findBy(array $params = []): ConnectionResponse
  {
    throw new \Exception('Not implemented.');
  }

  public function findOneBy(array $params = []): ConnectionResponse
  {
    throw new \Exception('Not implemented.');
  }

  public function all(): ConnectionResponse
  {
    $result = $this->client->get($this->routeProvider->all(), $this->getHeadersForRequest());
    return $this->getJsonApiResponse($result);
  }

  public function create(array $attributes): ConnectionResponse
  {
    $request = Request::post($this->routeProvider->create(), json_encode($attributes))
    ->contentType('application/json');
    $result = $this->send($request);
    return $this->getBasicApiResponse($result);
  }

  public function updateAsPut(string $id, array $attributes): ConnectionResponse
  {
    $request = Request::put($this->routeProvider->update($id), json_encode($attributes))
      ->contentType('application/json');
    $result = $this->send($request);
    return $this->getBasicApiResponse($result);
  }

  public function updateAsPatch(string $id, array $attributes): ConnectionResponse
  {
    $request = Request::patch($this->routeProvider->update($id), json_encode($attributes))
      ->contentType('application/json');

    $result = $this->send($request);
    return $this->getBasicApiResponse($result);
  }

  public function delete(string $id): ConnectionResponse
  {
    $request = Request::delete($this->routeProvider->update($id));
    $result = $this->send($request);
    return $this->getBasicApiResponse($result);
  }

  public function custom(string $url, string $method, array $payload = []): ConnectionResponse
  {
    throw new \Exception('Not implemented.');
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

  public function addHeaders(array $headers)
  {
    $this->headers = array_merge($this->headers, $headers);
  }

  public function clearHeaders(): void
  {
    $this->headers = $this->getDefaultHeaders();
  }

  protected function getHeadersForRequest(): array
  {
    $this->assureHeadersNotNull();
    return array_merge($this->headers, $this->authenticationProvider->getHeader());
  }

  protected function assureHeadersNotNull(): void
  {
    if(blank($this->headers)){
      $this->headers = $this->getDefaultHeaders();
    }
  }

  private function send(Request $request): Response
  {
    $result = $request->addHeaders($this->getHeadersForRequest())->send();
    $this->finishRequest();
    return $result;
  }

  private function getJsonApiResponse(DocumentInterface $result): JsonApiResponse
  {
    return new JsonApiResponse($result, $this->transformer);
  }

  private function getBasicApiResponse(Response $result): BasicApiResponse
  {
    return new BasicApiResponse($result, $this->transformer);
  }

  protected function finishRequest(): void
  {
    $this->clearHeaders();
  }

  protected function getDefaultHeaders(): array
  {
    return [
      'accept' => 'application/json'
    ];
  }
}