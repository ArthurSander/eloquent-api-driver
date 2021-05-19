<?php

namespace ArthurSander\Drivers\Api\Authentication;

use ArthurSander\Drivers\Api\Authentication\Providers\ApiKeyAuthenticationProvider;
use ArthurSander\Drivers\Api\Authentication\Providers\BasicAuthenticationProvider;
use ArthurSander\Drivers\Api\Authentication\Providers\BearerAuthenticationProvider;
use ArthurSander\Drivers\Api\Authentication\Providers\NoneAuthenticationProvider;
use ArthurSander\Drivers\Api\Contracts\AuthenticationProvider;
use ArthurSander\Drivers\Api\Enums\AuthenticationTypes;
use ArthurSander\Drivers\Api\Model;
use Illuminate\Support\Facades\Config;

class AuthenticationProviderFactory
{
  public function create(Model $model): AuthenticationProvider
  {
    return match ($model->authenticationType) {

      AuthenticationTypes::BEARER_TOKEN => new BearerAuthenticationProvider(Config::get('database.api.authentication.bearer_token') ?? $model->token),

      AuthenticationTypes::BASIC_AUTH => new BasicAuthenticationProvider(
        Config::get('database.api.authentication.username') ?? $model->username,
        Config::get('database.api.authentication.password') ?? $model->password
      ),

      AuthenticationTypes::API_KEY => new ApiKeyAuthenticationProvider(
        Config::get('database.api.authentication.api_key') ?? $model->authenticationApiKey,
        Config::get('database.api.authentication.api_value') ?? $model->authenticationApiValue
      ),

      default => new NoneAuthenticationProvider(),
    };

  }
}