<?php

namespace App\Attributes;

use Attribute;

#[Attribute(Attribute::TARGET_METHOD)]
class CheckFeatureFlag
{
    public function __construct(
        public string $flagName = 'AB',
        public mixed $fallbackReturn = 'Was not executed'
    ) {}
}
