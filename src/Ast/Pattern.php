<?php

namespace Phiki\Regex\Ast;

use Phiki\Regex\Evaluator\State;

class Pattern implements Node
{
    /** @param Alternation[] $elements */
    public function __construct(
        public array $alternations,
    ) {}

    public function visit(State $state): bool
    {
        $matched = false;

        foreach ($this->alternations as $alternation) {
            if ($alternation->visit($state)) {
                $matched = true;
                break;
            }
        }

        return $matched;
    }
}
