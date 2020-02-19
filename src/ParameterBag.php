<?php

declare(strict_types=1);

namespace Suitcase\Builder;

final class ParameterBag
{
    protected array $parameters;

    public function __construct(array $parameters = [])
    {
        $this->parameters = $parameters;
    }

    public function get(string $key):? string
    {
        return $this->parameters[$key] ?? null;
    }

    public function has(string $key): bool
    {
        return array_key_exists($key, $this->parameters);
    }

    public function set(string $key, string $value): self
    {
        $this->parameters[$key] = $value;

        return $this;
    }

    public function remove(string $key): self
    {
        unset($this->parameters[$key]);

        return $this;
    }

    public function all(): array
    {
        return $this->parameters;
    }
}
