<?php

namespace Phiki\Regex\Ast\Atoms;

use Phiki\Regex\Ast\Atom;
use Phiki\Regex\Evaluator\State;
use Phiki\Regex\Ast\CharacterClassMember;

class CharacterClass implements Atom
{
    /** @param CharacterClassMember[] */
    public function __construct(
        public array $members,
        public bool $negated = false,
    ) {}

    public function visit(State $state): bool
    {
        foreach ($this->members as $member) {
            $matched = $member->visit($state);

            if ($this->negated) {
                $matched = ! $matched;
            }

            if ($matched) {
                $state->advance();

                return true;
            }
        }

        return false;
    }
}
