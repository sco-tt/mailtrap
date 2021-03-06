<?php

namespace StephaneCoinon\Mailtrap;

use GuzzleHttp\Client as HttpClient;
use GuzzleHttp\Exception\RequestException;
use StephaneCoinon\Mailtrap\Exceptions\MailtrapException;
use StephaneCoinon\Mailtrap\Inbox;

class Client
{
    /** @var string */
    protected $apiToken;

    /** @var GuzzleHttp\Client */
    public $http;

    /** @var array */
    protected $errors = [];

    /**
     * Get new Client instance.
     *
     * @param string $apiToken
     */
    public function __construct($apiToken)
    {
        $this->apiToken = $apiToken;
        $this->http = new HttpClient([
            'base_uri' => 'https://mailtrap.io/api/v1/',
        ]);
    }

    /**
     * Make a new API request.
     *
     * @param  string $method     HTTP method: get, post...
     * @param  string $uri        request uri relative to base uri set in constructor
     * @param  array  $parameters request parameters
     * @param  array  $headers    request headers
     * @return Object|array
     */
    public function request($method, $uri, $parameters = [], $headers = [])
    {
        $this->errors = [];

        $headers = [
            'Api-Token' => $this->apiToken,
        ];

        try {
            $response = $this->http->request($method, $uri, [
                'query' => $parameters,
                'headers' => $headers,
            ]);
        } catch (RequestException $guzzleException) {
            $mailtrapException = MailtrapException::create($guzzleException);
            $this->setErrors($mailtrapException);
            return null;
        }

        return json_decode($response->getBody());
    }

    /**
     * Make a "GET" API request.
     *
     * @param  string $uri        request uri relative to base uri set in constructor
     * @param  array  $parameters request parameters
     * @param  array  $headers    request headers
     * @return Object|array
     */
    public function get($uri, $parameters = [], $headers = [])
    {
        return $this->request('GET', $uri, $parameters, $headers);
    }

    /**
     * Set response errors.
     *
     * @param MailtrapException $exception
     */
    protected function setErrors(MailtrapException $exception)
    {
        $this->errors[] = (Object) [
            'status' => $exception->status,
            'message' => $exception->error,
        ];
    }

    /**
     * Get the response errors.
     *
     * @return array
     */
    public function getErrors()
    {
        return $this->errors;
    }
}
