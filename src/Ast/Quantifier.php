<?php

namespace Phiki\Regex\Ast;

use Phiki\Regex\Evaluator\State;

interface Quantifier
{
    public function visit(State $state, Atom $atom): bool;
}
