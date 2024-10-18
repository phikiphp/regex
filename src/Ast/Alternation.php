<?php

namespace Phiki\Regex\Ast;

use Phiki\Regex\Evaluator\State;

class Alternation implements Node
{
    public function __construct(
        public array $elements,
    ) {}

    public function visit(State $state): bool
    {
        $matched = false;

        foreach ($this->elements as $element) {
            $matched = $element->visit($state);

            if (! $matched) {
                break;
            }
        }

        return $matched;
    }
}