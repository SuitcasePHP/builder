<?php

require __DIR__ . '/../vendor/autoload.php';

use Suitcase\Builder\SDK;

class Acme
{
    protected $client;

    protected $resource = '';

    private function __construct(string $authToken)
    {
        $this->client = SDK::make('https://api.acme.com')->withAuthHeaders($authToken);
    }

    public static function create(string $authToken): self
    {
        return new self($authToken);
    }

    public function load(...$resources): self
    {
        foreach ($resources as $resource) {
            $this->client->add($resource);
        }

        return $this;
    }

    public function __get(string $resource): self
    {
        $this->resource = $resource;

        return $this;
    }

    public function get(): array
    {
        return json_decode($this->client->use($this->resource)->get()->getBody()->getContents());
    }

    public function find($identifier)
    {
        return json_decode($this->client->use($this->resource)->find($identifier)->getBody()->getContents());
    }
}

// Create your SDK
$acme = Acme::create('1234-api-token-1234')->load('users', 'posts'. 'categories', 'likes');

// Access the Users resource and get all records from your API
$acme->users->get();

// Access the Users resource and find a specific resource using an identifier from your API
$acme->users->find(1);









