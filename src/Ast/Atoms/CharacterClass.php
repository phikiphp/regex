<?php

namespace Phiki\Regex\Ast\Atoms;

use Phiki\Regex\Ast\Atom;

class CharacterClass implements Atom
{
    /** @param CharacterClassMember[] */
    public function __construct(
        public array $members,
        public bool $negated = false,
    ) {}
}
