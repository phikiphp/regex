<?php

namespace Phiki\Regex\Evaluator;

final class State
{
    protected array $captures = [];

    protected array $backrefs = [];

    public function __construct(
        public readonly string $subject,
        protected int $position = 0,
    ) {}

    public function pushBackref(int $index, string $value): void
    {
        $this->backrefs[$index] = $value;
    }

    public function pushGroup(int|string $key, string $value): void
    {
        $this->captures[$key] = $value;
    }

    public function setRootCapture(string $value): void
    {
        $this->captures = [0 => $value] + $this->captures;
    }

    public function setCapture(int $index, string $value): void
    {
        $this->captures[$index] = $value;
    }

    public function hasCapture(int $index): bool
    {
        return isset($this->captures[$index]);
    }

    public function captures(): array
    {
        return $this->captures;
    }

    public function capture(int $index): string
    {
        return $this->captures[$index];
    }

    public function position(): int
    {
        return $this->position;
    }

    public function isEof(): bool
    {
        return $this->position >= strlen($this->subject);
    }

    public function advance(): void
    {
        $this->position++;
    }

    public function current(): string
    {
        return $this->subject[$this->position];
    }
}