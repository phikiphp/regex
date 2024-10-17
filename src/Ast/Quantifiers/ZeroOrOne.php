<?php

namespace Phiki\Regex\Ast\Quantifiers;

use Phiki\Regex\Ast\Quantifier;

class ZeroOrOne implements Quantifier
{
    public function __construct(
        public bool $greedy = true,
        public bool $lazy = false,
        public bool $possessive = false,
    ) {}
}