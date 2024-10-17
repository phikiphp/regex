<?php

namespace Phiki\Regex\Ast\Quantifiers;

use Phiki\Regex\Ast\Quantifier;

class Exactly implements Quantifier
{
    public function __construct(
        public int $count,
        public bool $possessive = false,
    ) {}
}
