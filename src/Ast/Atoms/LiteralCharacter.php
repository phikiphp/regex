<?php

namespace Phiki\Regex\Ast\Atoms;

use Phiki\Regex\Ast\Atom;

class LiteralCharacter implements Atom
{
    public function __construct(
        public string $character,
    ) {}
}
