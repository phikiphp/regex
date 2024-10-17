<?php

namespace Phiki\Regex\Ast\Atoms\CharacterClassMembers;

use Phiki\Regex\Ast\CharacterClassMember;
use Phiki\Regex\Evaluator\State;

class CharacterRange implements CharacterClassMember
{
    public function __construct(
        public string $start,
        public string $end,
    ) {}

    public function visit(State $state): bool
    {
        dd();
    }
}
