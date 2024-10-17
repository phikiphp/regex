<?php

namespace Phiki\Regex\Ast\Atoms\CharacterClassMembers;

use Phiki\Regex\Ast\CharacterClassMember;

class CharacterRange implements CharacterClassMember
{
    public function __construct(
        public string $start,
        public string $end,
    ) {}
}
