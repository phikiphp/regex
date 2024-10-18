<?php

namespace Phiki\Regex\Ast\Atoms\CharacterClassMembers;

use Phiki\Regex\Ast\CharacterClassMember;
use Phiki\Regex\Evaluator\State;

class Character implements CharacterClassMember
{
    public function __construct(
        public string $character,
    ) {}

    public function visit(State $state): bool
    {
        return $state->current() === $this->character;
    }
}
