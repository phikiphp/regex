<?php

namespace Phiki\Regex\Parser;

class InputStream
{
    protected int $position = 0;

    protected int $length = 0;

    public function __construct(
        protected readonly string $input,
    ) {
        $this->length = strlen($input);
    }

    public function position(): int
    {
        return $this->position;
    }

    public function is(string ...$char): bool
    {
        return in_array($this->current(), $char, true);
    }

    public function nextIs(string ...$char): bool
    {
        return in_array($this->peek(), $char, true);
    }

    public function current(): ?string
    {
        return $this->input[$this->position] ?? null;
    }

    public function peek(): ?string
    {
        return $this->input[$this->position + 1] ?? null;
    }

    public function next(): void
    {
        $this->position++;
    }

    public function isEof(): bool
    {
        return $this->position >= $this->length;
    }
}
