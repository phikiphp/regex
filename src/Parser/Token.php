<?php

namespace Phiki\Regex\Parser;

class Token
{
    public function __construct(
        public TokenKind $kind,
        public string $value,
        public int $offset,
    ) {}
}
