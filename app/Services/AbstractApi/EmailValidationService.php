<?php

declare(strict_types=1);

namespace App\Services\AbstractApi;

use App\Interfaces\EmailValidationInterface;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\ConnectException;
use GuzzleHttp\HandlerStack;
use GuzzleHttp\Middleware;
use Psr\Http\Message\RequestInterface;
use Psr\Http\Message\ResponseInterface;
use RuntimeException;

class EmailValidationService implements EmailValidationInterface
{
    private string $baseUrl = 'https://emailreputation.abstractapi.com/v1/';
    public function __construct(private string $apiKey){}
    public function verify(string $email): array
    {
        $stack = HandlerStack::create();
        $maxRetry = 3;

        $stack->push($this->getRetryMiddleware($maxRetry));
        $client = new Client([
            'base_uri' => $this->baseUrl,
            'timeout' => 5,
            'handler' => $stack
        ]);

        $params = [
            'api_key' => $this->apiKey,
            'email' => $email
        ];

        $response = $client->get('', ['query' => $params]);

        return json_decode($response->getBody()->getContents(),true);
    }

    public function getRetryMiddleware($maxRetry){

        return Middleware::retry(
            function (
                int $retries,
                RequestInterface $request,
                ?ResponseInterface $response = null,
                ?RuntimeException $e = null
                ) use ($maxRetry) {
                    if($retries >= $maxRetry){
                        return false;
                    }

                    if($response && in_array($response->getStatusCode(),[249,429,503,404])){
                        return true;
                    }

                    if($e instanceof ConnectException){
                        return true;
                    }

                    return false;
                }
        );

    }
    
}