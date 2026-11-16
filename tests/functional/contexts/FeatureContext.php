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

namespace sclable\xmlLint\tests\functional\contexts;

use Behat\Step\Then;
use Behat\Step\When;
use Behat\Step\Given;
use Behat\Behat\Context\Context;
use Symfony\Component\Console\Tester\CommandTester;
use sclable\xmlLint\console\application\Application;

/**
 * Defines application features from the specific context.
 */
class FeatureContext implements Context
{
    private Application $application;

    private CommandTester $commandTester;

    private ?int $exitCode = null;

    private string $file;

    /**
     * Initializes context.
     *
     * Every scenario gets its own context instance.
     * You can also pass arbitrary arguments to the
     * context constructor through behat.php.
     */
    public function __construct()
    {
        $this->application = new Application();

        $command = $this->application->find('lint');

        $this->commandTester = new CommandTester($command);
    }

    #[Given('the file :file')]
    public function theFile(string $file): void
    {
        $this->file = dirname(__DIR__) . '/_testdata/' . $file;
    }

    #[When('I run lint')]
    public function iRunLint(): void
    {
        $this->exitCode = $this->commandTester->execute([
            'file' => $this->file,
        ]);
    }

    /**
     * @throws \Exception
     */
    #[Then('I have a return code :code')]
    public function iHaveAReturnCode(string $code): void
    {
        $code = (int) $code;

        if (null === $this->exitCode) {
            echo $this->commandTester->getDisplay();
            throw new \Exception('the return code was NULL.');
        }

        if ($this->exitCode !== $code) {
            echo $this->commandTester->getDisplay();
            throw new \Exception(sprintf("the return code does not match. \n Expected: %s\n Actual: %s", $code, $this->exitCode));
        }
    }
}
