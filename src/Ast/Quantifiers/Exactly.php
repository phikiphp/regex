<?php

namespace Phiki\Regex\Ast\Quantifiers;

use Phiki\Regex\Ast\Quantifier;

class Exactly implements Quantifier
{
    public function __construct(
        public int $n,
        public bool $possessive = false,
    ) {}
}
