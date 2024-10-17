<?php

namespace Phiki\Regex\Ast\Atoms\CharacterClassMembers;

use Phiki\Regex\Ast\CharacterClassMember;

class Character implements CharacterClassMember
{
    public function __construct(
        public string $character,
    ) {}
}
