<?php

declare(strict_types=1);

/**
 * This file is part of the Sclable Xml Lint Package.
 *
 * @copyright (c) 2015-present Sclable Business Solutions GmbH
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace sclable\xmlLint\tests\unit\console\application;

use Composer\InstalledVersions;
use PHPUnit\Framework\TestCase;
use PHPUnit\Framework\Attributes\CoversClass;
use sclable\xmlLint\console\application\Application;

#[CoversClass(Application::class)]
class ApplicationTest extends TestCase
{
    public function testVersionComesFromComposer(): void
    {
        $this->assertSame(
            InstalledVersions::getPrettyVersion(Application::PACKAGE),
            (new Application())->getVersion()
        );
    }
}
