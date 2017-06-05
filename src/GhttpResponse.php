<?php

namespace Skychf\Ghttp;

class GhttpResponse
{
    protected $response;

    public function setResponse($response)
    {
        $this->response = $response;
        return $this;
    }

    public function body()
    {
        return (string) $this->response->getBody();
    }

    public function json()
    {
        return json_decode($this->response->getBody(), true);
    }

    public function header($header)
    {
        return $this->response->getHeaderLine($header);
    }

    public function status()
    {
        return $this->response->getStatusCode();
    }

    public function isSuccess()
    {
        return $this->status() >= 200 && $this->status() < 300;
    }

    public function isRedirect()
    {
        return $this->status() >= 300 && $this->status() < 400;
    }

    public function isClientError()
    {
        return $this->status() >= 400 && $this->status() < 500;
    }

    public function isServerError()
    {
        return $this->status() >= 500;
    }

    public function __call($method, $args)
    {
        return $this->response->{$method}(...$args);
    }
}