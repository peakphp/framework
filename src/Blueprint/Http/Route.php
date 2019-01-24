<?php

declare(strict_types=1);

namespace Peak\Blueprint\Http;

use Psr\Http\Message\ServerRequestInterface;

interface Route
{
    /**
     * @param ServerRequestInterface $request
     * @return bool
     */
    public function match(ServerRequestInterface $request): bool;

    /**
     * @return string|null
     */
    public function getMethod(): ?string;

    /**
     * @return string
     */
    public function getPath(): string;
}
