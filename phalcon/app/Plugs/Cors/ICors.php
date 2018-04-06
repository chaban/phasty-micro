<?php

namespace Phasty\Plugs\Cors;

interface ICors
{

    public function allowOrigins(): array;

    public function allowMethods(): array;

    public function allowHeaders(): array;

    public function addCorsHeaders();

    public function addPreflightHeaders();

    public function maxAge(): int;

    public function isAllowed(): bool;
}
