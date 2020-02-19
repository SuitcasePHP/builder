<?php

declare(strict_types=1);

namespace Suitcase\Builder;

use GuzzleHttp\Client;
use GuzzleHttp\Psr7\Response;
use Suitcase\Builder\ParameterBag;
use GuzzleHttp\Exception\GuzzleException;
use Tightenco\Collect\Support\Collection;
use Suitcase\Builder\Exceptions\MethodNotAllowed;
use Suitcase\Builder\Exceptions\ResourceException;
use Suitcase\Builder\Exceptions\UnsupportedScheme;
use Suitcase\Builder\Exceptions\ResourceNotRegistered;

final class SDK
{
    protected array $resource;

    protected string $scheme;

    protected string $host;

    protected string $path;

    protected array $url;

    protected Client $client;

    protected ParameterBag $query;

    protected Collection $resources;

    protected array $allowedSchemes = [
        'http',
        'https'
    ];

    public function __construct(string $url)
    {
        $parts = array_merge(parse_url($url));

        $this->scheme = isset($parts['scheme']) ? $this->allowedScheme($parts['scheme']) : '';
        $this->host = $parts['host'] ?? '';
        $this->path = $parts['path'] ?? '/';
        $this->client = new Client();
        $this->query = new ParameterBag();
        $this->resources = new Collection();
    }

    public function add(string $name, array $options = []): void
    {
        $resource = [
            'name' => $name,
            'endpoint' => array_key_exists('endpoint', $options) ? $options['endpoint'] : $name,
            'allows' => array_key_exists('allows', $options) ? $options['allows'] : [
                'get', 'find', 'create', 'update', 'delete'
            ],
        ];

        $this->resources->push($resource);
    }

    public function getScheme(): string
    {
        return $this->scheme;
    }

    public function getHost(): string
    {
        return $this->host;
    }

    public function getPath(): string
    {
        return $this->path;
    }

    public function getResources(): Collection
    {
        return $this->resources;
    }

    public function getQuery(): ParameterBag
    {
        return $this->query;
    }

    public function getClient(): Client
    {
        return $this->client;
    }

    public function use(string $resource): self
    {
        if (! $this->resources->pluck('name')->contains($resource)) {
            throw new ResourceNotRegistered("No resource registered for: {$resource}");
        }

        $this->resource = $this->resources->where('name', $resource)->first();

        return $this;
    }

    public function getResource():? array
    {
        return $this->resource;
    }

    public function get(string $method = 'GET')
    {
        if (! $this->checkAbility('get')) {
            throw new MethodNotAllowed("Method get is not available on this resource");
        }

        return $this->call($method, $this->resource['endpoint']);
    }

    public function find($identifier, string $method = 'GET')
    {
        if (! $this->checkAbility('find')) {
            throw new MethodNotAllowed("Method find is not available on this resource");
        }

        return $this->call($method, $this->resource['endpoint'] . '/' . (string) $identifier);
    }

    public function create(array $data)
    {
        if (! $this->checkAbility('create')) {
            throw new MethodNotAllowed("Method create is not available on this resource");
        }

        return $this->call('POST', $this->resource['endpoint'], $data);
    }

    public function update($identifier, array $data, string $method = 'PUT')
    {
        if (! $this->checkAbility('update')) {
            throw new MethodNotAllowed("Method update is not available on this resource");
        }

        return $this->call($method, $this->resource['endpoint'] . '/' . (string) $identifier, $data);
    }

    public function delete($identifier, string $method = 'DELETE')
    {
        if (! $this->checkAbility('delete')) {
            throw new MethodNotAllowed("Method delete is not available on this resource");
        }

        return $this->call($method, $this->resource['endpoint'] . '/' . (string) $identifier);
    }

    protected function checkAbility(string $method)
    {
        return collect($this->resource['allows'])->contains($method);
    }

    protected function call(string $method, string $endpoint, array $data = []): Response
    {
        $uri = $this->buildUri($endpoint);

        try {
            $response = $this->client->request(
                $method,
                $uri,
                $data
            );
        } catch (GuzzleException $e) {
            throw new ResourceException($e->getMessage());
        }

        return $response;
    }

    protected function buildUri(string $endpoint): string
    {
        $uri = '';

        if ($this->getScheme() !== '') {
            $uri .= $this->getScheme() . '://';
        }

        if ($this->getHost() !== '') {
            $uri .= $this->getHost();
        }

        $uri .= rtrim($this->getPath(), '/');

        $uri .= '/' . rtrim($endpoint, '/');

        return $uri;
    }

    protected function allowedScheme(string $scheme): string
    {
        $scheme = strtolower($scheme);

        if (! in_array($scheme, $this->allowedSchemes)) {
            throw new UnsupportedScheme(
                "The scheme `{$scheme}` isn't valid. It should be either `http` or `https`."
            );
        }

        return $scheme;
    }
}
