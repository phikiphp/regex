<?php

namespace Phiki\Regex\Ast\Atoms;

use Phiki\Regex\Ast\Atom;
use Phiki\Regex\Evaluator\State;

class LiteralCharacter implements Atom
{
    public function __construct(
        public string $character,
    ) {}

    public function visit(State $state): bool
    {
        dd();
    }
}
