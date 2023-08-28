<?php

it('doesnt use debug functions')
    ->expect(['dd', 'dump', 'ray'])
    ->each->not->toBeUsed();
