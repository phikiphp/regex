<?php

namespace Phiki\Regex\Ast;

use Phiki\Regex\Evaluator\State;

class Pattern implements Node
{
    /** @param Element[] $elements */
    public function __construct(
        public array $elements,
    ) {}

    public function visit(State $state): bool
    {
        $alternation = false;

        foreach ($this->elements as $element) {
            if ($element->visit($state)) {
                $alternation = true;
            }
        }

        return $alternation;
    }
}
