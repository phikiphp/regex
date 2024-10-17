<?php

namespace Phiki\Regex\Ast;

use Phiki\Regex\Evaluator\State;

interface Node
{
    public function visit(State $state): bool;
}