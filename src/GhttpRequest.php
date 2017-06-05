<?php

namespace Skychf\Ghttp;

use GuzzleHttp\Client;

class GhttpRequest
{
    private static $instance;

    private $client;

    private $response;

    public function __construct()
    {
        $this->options = [
            'http_errors' => false,
        ];
        $this->bodyFormat = 'json';
        $this->client     = new Client;
        $this->response   = new GhttpResponse;
    }

    public static function getInstance()
    {
        if (! isset(self::$instance)) {
            self::$instance = new self;
        }

        return self::$instance;
    }

    public function withoutRedirecting()
    {
        $this->options = array_merge_recursive($this->options, [
                'allow_redirects' => false
        ]);
        return $this;
    }

    public function asJson()
    {
        return $this->bodyFormat('json')->contentType('application/json');
    }

    public function asFormParams()
    {
        return $this->bodyFormat('form_params')->contentType('application/x-www-form-urlencoded');
    }

    public function bodyFormat($format)
    {
        $this->bodyFormat = $format;
        return $this;
    }

    public function contentType($contentType)
    {
        return $this->withHeaders(['Content-Type' => $contentType]);
    }

    public function accept($header)
    {
        return $this->withHeaders(['Accept' => $header]);
    }

    public function withHeaders($headers)
    {
        $this->options = array_merge_recursive($this->options, [
                'headers' => $headers
        ]);
        return $this;
    }

    public function get($url, $queryParams = [])
    {
        return $this->send('GET', $url, [
            'query' => $queryParams,
        ]);
    }

    public function post($url, $params = [])
    {
        return $this->send('POST', $url, [
            $this->bodyFormat => $params,
        ]);
    }

    public function patch($url, $params = [])
    {
        return $this->send('PATCH', $url, [
            $this->bodyFormat => $params,
        ]);
    }

    public function put($url, $params = [])
    {
        return $this->send('PUT', $url, [
            $this->bodyFormat => $params,
        ]);
    }

    public function delete($url, $params = [])
    {
        return $this->send('DELETE', $url, [
            $this->bodyFormat => $params,
        ]);
    }

    public function send($method, $url, $options)
    {
        return $this->response->setResponse($this->client->request($method, $url, $this->mergeOptions([
            'query' => $this->parseQueryParams($url),
        ], $options)));
    }

    private function mergeOptions(...$options)
    {
        return array_merge_recursive($this->options, ...$options);
    }

    private function parseQueryParams($url)
    {
        return parse_str(parse_url($url, PHP_URL_QUERY));
    }
}