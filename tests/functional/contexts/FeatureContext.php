<?php

/**
 * This file is part of the Sclable Xml Lint Package.
 *
 * @copyright (c) 2020 Sclable Business Solutions GmbH
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace sclable\xmlLint\tests\functional\contexts;

use Behat\Behat\Context\Context;
use Behat\Behat\Context\SnippetAcceptingContext;
use Symfony\Component\Console\Tester\CommandTester;
use sclable\xmlLint\console\application\Application;

/**
 * Defines application features from the specific context.
 */
class FeatureContext implements Context, SnippetAcceptingContext
{
    /** @var Application */
    protected $application;

    /** @var CommandTester */
    protected $commandTester;

    /** @var int the exit code */
    protected $exitCode;

    /** @var string */
    protected $file;

    /**
     * Initializes context.
     *
     * Every scenario gets its own context instance.
     * You can also pass arbitrary arguments to the
     * context constructor through behat.yml.
     */
    public function __construct()
    {
        $this->application = new Application();

        $command = $this->application->find('lint');

        $this->commandTester = new CommandTester($command);
    }

    /**
     * @Given the file :file
     *
     * @param $file
     */
    public function theFile($file)
    {
        $this->file = dirname(__DIR__) . '/_testdata/' . $file;
    }

    /**
     * @When I run lint
     */
    public function iRunLint()
    {
        $this->exitCode = $this->commandTester->execute([
            'file' => $this->file,
        ]);
    }

    /**
     * @Then I have a return code :code
     *
     * @param $code
     *
     * @throws \Exception
     */
    public function iHaveAReturnCode($code)
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
