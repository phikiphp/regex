<?php

namespace Phiki\Regex\Ast\Quantifiers;

use Phiki\Regex\Ast\Quantifier;

class ExactlyOrMore implements Quantifier
{
    public function __construct(
        public int $count,
        public bool $greedy = true,
        public bool $lazy = false,
        public bool $possessive = false,
    ) {}
}
