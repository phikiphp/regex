<?php

namespace Phiki\Regex\Ast;

use Phiki\Regex\Evaluator\State;

class Element implements Node
{
    public function __construct(
        public Atom $atom,
        public ?Quantifier $quantifier = null,
    ) {}

    public function visit(State $state): bool
    {
        if ($this->quantifier === null) {
            return $this->atom->visit($state);
        }

        return $this->quantifier->visit($state, $this->atom);
    }
}
