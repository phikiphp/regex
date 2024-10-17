<?php

namespace Phiki\Regex\Ast\Quantifiers;

use Phiki\Regex\Ast\Quantifier;

class ExactlyOrMore implements Quantifier
{
    public function __construct(
        public int $n,
        public bool $greedy = true,
        public bool $lazy = false,
        public bool $possessive = false,
    ) {}
}
