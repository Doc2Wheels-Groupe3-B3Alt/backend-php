<?php

namespace App\Http;

class Request
{
    private string $uri;
    private string $method;
    private array $headers;
    private string $payload;
    private array $queryParams;

    public function __construct()
    {
        $this->uri = $_SERVER['REQUEST_URI'];
        $this->method = $_SERVER['REQUEST_METHOD'];
        $this->headers = getallheaders();
        $this->payload = file_get_contents('php://input');
        $this->queryParams = $this->parseQueryParams();
    }

    private function parseQueryParams(): array
    {
        $queryString = parse_url($this->uri, PHP_URL_QUERY);
        $params = [];

        if ($queryString) {
            parse_str($queryString, $params);
        }

        return $params;
    }

    public function getQueryParams($key = null): array|string|null
    {
        if ($key) {
            return $this->queryParams[$key] ?? null;
        }
        return $this->queryParams;
    }

    public function getQueryParam(string $name, $default = null)
    {
        return $this->queryParams[$name] ?? $default;
    }

    public function getPayload(): string
    {
        return $this->payload;
    }

    public function getUri(): string
    {
        return $this->uri;
    }

    public function getMethod(): string
    {
        return $this->method;
    }

    public function getHeaders(): array
    {
        return $this->headers;
    }
}
