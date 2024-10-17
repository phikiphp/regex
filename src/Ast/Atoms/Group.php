<?php

namespace Phiki\Regex\Ast\Atoms;

use Phiki\Regex\Ast\Atom;
use Phiki\Regex\Ast\Pattern;
use Phiki\Regex\Evaluator\State;

class Group implements Atom
{
    public function __construct(
        // FIXME: Add group types here.
        public Pattern $pattern,
    ) {}

    public function visit(State $state): bool
    {
        dd();
    }
}
