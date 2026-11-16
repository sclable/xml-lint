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

namespace sclable\xmlLint\console\application;

use Composer\InstalledVersions;
use Symfony\Component\Console\Command\Command;
use sclable\xmlLint\console\command\LintCommand;
use Symfony\Component\Console\Command\HelpCommand;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * @internal
 *
 * customized console application for Xml Lint
 */
final class Application extends \Symfony\Component\Console\Application
{
    public const PACKAGE = 'sclable/xml-lint';
    public const NAME = 'Sclable Xml Lint';

    public function __construct()
    {
        parent::__construct(self::NAME, self::version());
        $this->setDefaultCommand(LintCommand::COMMAND_NAME, true);
    }

    private static function version(): string
    {
        if (!InstalledVersions::isInstalled(self::PACKAGE)) {
            return 'dev';
        }

        $version = InstalledVersions::getPrettyVersion(self::PACKAGE) ?? 'dev';

        // Composer's placeholder when the root package has no VCS metadata, e.g. in Docker builds
        return str_ends_with($version, '+no-version-set') ? 'dev' : $version;
    }

    protected function getDefaultCommands(): array
    {
        return [
            new HelpCommand(),
            new LintCommand(),
        ];
    }

    protected function doRunCommand(Command $command, InputInterface $input, OutputInterface $output): int
    {
        if ('version' != $command->getName() && !self::isMachineReadableHelp($command, $input)) {
            $output->writeln($this->getLongVersion());
        }

        return parent::doRunCommand($command, $input, $output);
    }

    private static function isMachineReadableHelp(Command $command, InputInterface $input): bool
    {
        return $command instanceof HelpCommand
            && 'txt' !== $input->getParameterOption('--format', 'txt', true);
    }
}
