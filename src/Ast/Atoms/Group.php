<?php

namespace Phiki\Regex\Ast\Atoms;

use Phiki\Regex\Ast\Atom;
use Phiki\Regex\Ast\Pattern;

class Group implements Atom
{
    public function __construct(
        // FIXME: Add group types here.
        public Pattern $pattern,
    ) {}
}
