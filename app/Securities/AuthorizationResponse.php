<?php

namespace App\Securities;


class AuthorizationResponse
{
    public function __construct(
        public readonly bool $allowed,
        public readonly ?string $message = null,
        public readonly ?string $title = null,
    ) {}

    public static function allow(): self
    {
        return new self(true);
    }

    public static function deny(
        string $message,
        ?string $title = null,
    ): self {

        return new self(
            false,
            $message,
            $title
        );
    }
}