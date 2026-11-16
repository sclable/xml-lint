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
use Symfony\Component\Console\Tester\ApplicationTester;

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

    public function testTextHelpStartsWithTheVersion(): void
    {
        $tester = $this->runHelp(['--help' => true]);

        $this->assertStringStartsWith(Application::NAME, $tester->getDisplay());
    }

    public function testJsonHelpIsValidJson(): void
    {
        $tester = $this->runHelp(['--help' => true, '--format' => 'json']);

        $this->assertJson($tester->getDisplay());
    }

    public function testXmlHelpStartsWithTheXmlDeclaration(): void
    {
        $tester = $this->runHelp(['--help' => true, '--format' => 'xml']);

        $this->assertStringStartsWith('<?xml', $tester->getDisplay());
    }

    /**
     * @param array<string, bool|string> $input
     */
    private function runHelp(array $input): ApplicationTester
    {
        $application = new Application();
        $application->setAutoExit(false);

        $tester = new ApplicationTester($application);
        $this->assertSame(0, $tester->run($input));

        return $tester;
    }
}
