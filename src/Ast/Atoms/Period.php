<?php

namespace Phiki\Regex\Ast\Atoms;

use Phiki\Regex\Ast\Atom;
use Phiki\Regex\Evaluator\State;

class Period implements Atom
{
    public function visit(State $state): bool
    {
        dd();
    }
}
