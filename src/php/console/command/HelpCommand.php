<?php

/**
 * This file is part of the Sclable Xml Lint Package.
 *
 * @copyright (c) 2022 Sclable Business Solutions GmbH
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace sclable\xmlLint\console\command;

use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * Class HelpCommand.
 *
 * customized help command to render the help without sub_command.
 *
 * default: `xmllint <sub_command> [--option|-o] <argument>`
 * customized: `xmllint [--option|-o] <argument>`
 *
 * @see \Symfony\Component\Console\Command\HelpCommand
 */
class HelpCommand extends \Symfony\Component\Console\Command\HelpCommand
{
    /**
     * {@inheritdoc}
     */
    protected function configure()
    {
        $this->ignoreValidationErrors();

        $this
            ->setName('help')
            ->setDefinition([
                new InputOption('xml', null, InputOption::VALUE_NONE, 'To output help as XML'),
                new InputOption(
                    'format',
                    null,
                    InputOption::VALUE_REQUIRED,
                    'The output format (txt, xml, json, or md)',
                    'txt'
                ),
                new InputOption('raw', null, InputOption::VALUE_NONE, 'To output raw command help'),
            ])
            ->setDescription('Display help')
            ->setHelp(<<<EOF
The <info>%command.name%</info> command displays the help.
You can also output the help in other formats by using the <comment>--format</comment> option:

  <info>php %command.full_name% --help --format=xml</info>
EOF
            );
    }

    /**
     * {@inheritdoc}
     */
    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $this->setCommand(
            $this->getApplication()->find(LintCommand::COMMAND_NAME)
        );

        return parent::execute($input, $output);
    }
}
