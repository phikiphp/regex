<?php

namespace Phiki\Regex\Ast\Quantifiers;

use Phiki\Regex\Ast\Atom;
use Phiki\Regex\Ast\Quantifier;
use Phiki\Regex\Evaluator\State;

class Exactly implements Quantifier
{
    public function __construct(
        public int $n,
        public bool $possessive = false,
    ) {}

    public function visit(State $state, Atom $atom): bool
    {
        dd();
    }
}
