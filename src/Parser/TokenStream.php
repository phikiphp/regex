<?php

namespace Phiki\Regex\Parser;

use Exception;

class TokenStream
{
    protected int $position = 0;

    protected int $length = 0;

    /** @param array<Token> $tokens */
    public function __construct(
        protected readonly array $tokens,
    ) {
        $this->length = count($tokens);
    }

    public function current(): ?Token
    {
        return $this->tokens[$this->position] ?? null;
    }

    public function peek(): ?Token
    {
        return $this->tokens[$this->position + 1] ?? null;
    }

    public function next(): void
    {
        $this->position++;
    }

    public function expect(TokenKind $kind): Token
    {
        $token = $this->current();

        if ($token === null) {
            throw new Exception('Unexpected end of input, expected ' . $kind->name);
        }

        if ($token->kind !== $kind) {
            throw new Exception('Unexpected token: ' . $token->kind->name . ', expected ' . $kind->name);
        }

        $this->next();

        return $token;
    }

    public function is(TokenKind $kind): bool
    {
        return $this->current()?->kind === $kind;
    }

    public function isAny(TokenKind ...$kinds): bool
    {
        return in_array($this->current()?->kind, $kinds, true);
    }

    public function isEof(): bool
    {
        return $this->position >= $this->length;
    }
}