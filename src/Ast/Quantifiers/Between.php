<?php

namespace Phiki\Regex\Ast\Quantifiers;

use Phiki\Regex\Ast\Quantifier;

class Between implements Quantifier
{
    public function __construct(
        public int $min,
        public int $max,
        public bool $greedy = true,
        public bool $lazy = false,
        public bool $possessive = false,
    ) {}
}