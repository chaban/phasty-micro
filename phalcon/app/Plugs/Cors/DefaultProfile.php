<?php

namespace Phasty\Plugs\Cors;

use Phalcon\Mvc\User\Plugin;

class DefaultProfile extends Plugin  implements ICors
{

    public function allowOrigins(): array
    {
        return $this->config->cors->allow_origins->toArray();
    }

    public function allowMethods(): array
    {
        return $this->config->cors->allow_methods->toArray();
    }

    public function allowHeaders(): array
    {
        return $this->config->cors->allow_headers->toArray();
    }

    public function maxAge(): int
    {
        return $this->config->cors->max_age;
    }

    public function addCorsHeaders()
    {
        return $this->response
            ->setHeader('Access-Control-Allow-Origin', $this->allowedOriginsToString());
    }

    public function addPreflightHeaders()
    {
        $this->response
            ->setHeader('Access-Control-Allow-Methods', $this->toString($this->allowMethods()))
            ->setHeader('Access-Control-Allow-Headers', $this->toString($this->allowHeaders()))
            ->setHeader('Access-Control-Allow-Origin', $this->allowedOriginsToString())
            ->setHeader('Access-Control-Max-Age', $this->maxAge());

        $this->response->setStatusCode(200, 'OK')->send();

        exit;
    }

    public function isAllowed(): bool
    {
        if (! in_array($this->request->getMethod(), $this->allowMethods())) {
            return false;
        }

        if (in_array('*', $this->allowOrigins())) {
            return true;
        }

        return in_array($this->request->header('Origin'), $this->allowOrigins());
    }

    protected function toString(array $array): string
    {
        return implode(', ', $array);
    }

    protected function allowedOriginsToString(): string
    {
        if (! $this->isAllowed()) {
            return '';
        }

        if (in_array('*', $this->allowOrigins())) {
            return '*';
        }

        return $this->request->getHeader('ORIGIN');
    }
}
