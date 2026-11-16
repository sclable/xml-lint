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

namespace sclable\xmlLint\tests\unit\console\command;

use PHPUnit\Framework\TestCase;
use PHPUnit\Framework\Attributes\CoversClass;
use sclable\xmlLint\console\command\LintCommand;
use Symfony\Component\Console\Tester\CommandTester;
use sclable\xmlLint\console\application\Application;

#[CoversClass(LintCommand::class)]
class LintCommandTest extends TestCase
{
    private string $cwd;

    protected function setUp(): void
    {
        $this->cwd = (string) getcwd();
        chdir(dirname(__DIR__, 3) . '/functional/_testdata');
    }

    protected function tearDown(): void
    {
        chdir($this->cwd);
    }

    public function testPrintsRelativePathOfBrokenFile(): void
    {
        $tester = $this->execute(['file' => 'broken.xml']);

        $this->assertSame(1, $tester->getStatusCode());
        $this->assertStringContainsString(PHP_EOL . 'file: broken.xml' . PHP_EOL, $tester->getDisplay());
    }

    /**
     * @param array<string, mixed> $input
     */
    private function execute(array $input): CommandTester
    {
        $tester = new CommandTester((new Application())->find(LintCommand::COMMAND_NAME));
        $tester->execute($input);

        return $tester;
    }
}
