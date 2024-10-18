<?php

namespace Phiki\Regex\Ast\Atoms;

use Phiki\Regex\Ast\Atom;
use Phiki\Regex\Ast\Pattern;
use Phiki\Regex\Evaluator\State;

class Group implements Atom
{
    public function __construct(
        // FIXME: Add group types here.
        public Pattern $pattern,
        public int $n,
    ) {}

    public function visit(State $state): bool
    {
        $position = $state->position();

        $matched = $this->pattern->visit($state);

        if ($matched) {
            $state->pushGroup($this->n, substr($state->subject, $position, $state->position() - $position));
        }

        return $matched;
    }
}
