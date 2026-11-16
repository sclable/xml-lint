<?php

/**
 * This file is part of the Sclable Xml Lint Package.
 *
 * @copyright (c) 2015-present Sclable Business Solutions GmbH
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

use Behat\Config\Suite;
use Behat\Config\Config;
use Behat\Config\Profile;
use sclable\xmlLint\tests\functional\contexts\FeatureContext;

return (new Config())
    ->withProfile(
        (new Profile('default'))
            ->withSuite(
                (new Suite('default'))
                    ->withPaths('%paths.base%/tests/functional')
                    ->withContexts(FeatureContext::class)
            )
    );
