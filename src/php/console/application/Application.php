<?php

/**
 * This file is part of the Sclable Xml Lint Package.
 *
 * @copyright (c) 2022 Sclable Business Solutions GmbH
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace sclable\xmlLint\console\application;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\ArgvInput;
use sclable\xmlLint\console\command\LintCommand;
use Symfony\Component\Console\Command\HelpCommand;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * Class Application.
 *
 * customized console application for Xml Lint
 */
class Application extends \Symfony\Component\Console\Application
{
    const VERSION = 'dev';
    const NAME = 'Sclable Xml Lint';

    /**
     * {@inheritdoc}
     */
    public function __construct($name = 'UNKNOWN', $version = 'UNKNOWN')
    {
        $name = self::NAME;
        $version = self::VERSION;
        parent::__construct($name, $version);
        $this->setDefaultCommand(LintCommand::COMMAND_NAME);
    }

    /**
     * {@inheritdoc}
     */
    protected function getDefaultCommands(): array
    {
        parent::getDefaultCommands();

        return [
            new HelpCommand(),
            new LintCommand(),
        ];
    }

    /**
     * {@inheritdoc}
     *
     * @SuppressWarnings(PHPMD.Superglobals)
     */
    public function run(InputInterface $input = null, OutputInterface $output = null): int
    {
        if (null === $input) {
            // rewrite the input for single command usage
            $argv = $_SERVER['argv'];
            $scriptName = array_shift($argv);
            array_unshift($argv, 'lint');
            array_unshift($argv, $scriptName);
            $input = new ArgvInput($argv);
        }

        return parent::run($input, $output);
    }

    /**
     * {@inheritdoc}
     */
    protected function doRunCommand(Command $command, InputInterface $input, OutputInterface $output)
    {
        if ('version' != $command->getName()) {
            $output->writeln($this->getLongVersion());
        }

        return parent::doRunCommand($command, $input, $output);
    }
}
