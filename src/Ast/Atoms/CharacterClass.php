<?php

namespace Phiki\Regex\Ast\Atoms;

use Phiki\Regex\Ast\Atom;
use Phiki\Regex\Evaluator\State;

class CharacterClass implements Atom
{
    /** @param CharacterClassMember[] */
    public function __construct(
        public array $members,
        public bool $negated = false,
    ) {}

    public function visit(State $state): bool
    {
        dd();
    }
}
