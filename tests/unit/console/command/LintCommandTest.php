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
use PHPUnit\Framework\Attributes\DataProvider;
use sclable\xmlLint\console\command\LintCommand;
use Symfony\Component\Console\Tester\CommandTester;
use sclable\xmlLint\console\application\Application;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Exception\InvalidOptionException;

#[CoversClass(LintCommand::class)]
class LintCommandTest extends TestCase
{
    private const TREE = __DIR__ . '/../../_testdata/tree';

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

    public function testQuietPrintsOnlyTheErrors(): void
    {
        $tester = new CommandTester((new Application())->find(LintCommand::COMMAND_NAME));
        $tester->execute(['file' => 'broken.xml'], ['verbosity' => OutputInterface::VERBOSITY_QUIET]);

        $this->assertSame(1, $tester->getStatusCode());
        $this->assertStringContainsString('file: broken.xml' . PHP_EOL . '  > Line ', $tester->getDisplay());
        $this->assertStringNotContainsString('progress', $tester->getDisplay());
    }

    /**
     * @return iterable<string, array{array<string, mixed>, int}>
     */
    public static function recursiveOptions(): iterable
    {
        yield 'default' => [[], 3];
        yield 'bare option' => [['--recursive' => null], 3];
        yield '1' => [['--recursive' => '1'], 3];
        yield 'yes' => [['--recursive' => 'yes'], 3];
        yield '0' => [['--recursive' => '0'], 1];
        yield 'false' => [['--recursive' => 'false'], 1];
        yield 'no' => [['--recursive' => 'no'], 1];
        yield 'off' => [['--recursive' => 'off'], 1];
    }

    /**
     * @param array<string, mixed> $options
     */
    #[DataProvider('recursiveOptions')]
    public function testRecursiveOption(array $options, int $expectedFiles): void
    {
        $tester = $this->execute(['file' => self::TREE] + $options);

        $this->assertSame(0, $tester->getStatusCode());
        $this->assertStringContainsString($expectedFiles . ' files /', $tester->getDisplay());
    }

    public function testExcludeTrimsPathsAndIgnoresEmptyOnes(): void
    {
        $tester = $this->execute(['file' => self::TREE, '--exclude' => 'sub, other,']);

        $this->assertSame(0, $tester->getStatusCode());
        $this->assertStringContainsString('1 files /', $tester->getDisplay());
    }

    public function testEmptyPatternFallsBackToXmlFiles(): void
    {
        $tester = $this->execute(['file' => self::TREE, '--pattern' => ' , ']);

        $this->assertSame(0, $tester->getStatusCode());
        $this->assertStringContainsString('3 files /', $tester->getDisplay());
    }

    public function testRejectsInvalidRecursiveValue(): void
    {
        $this->expectException(InvalidOptionException::class);

        $this->execute(['file' => self::TREE, '--recursive' => 'foo']);
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
