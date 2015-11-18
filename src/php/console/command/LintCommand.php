<?php
/**
 * This file is part of the Sclable Xml Lint Package.
 *
 * @copyright (c) 2015 Sclable Business Solutions GmbH
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace sclable\xmlLint\console\command;

use sclable\xmlLint\output\ErrorFormatter;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Finder\Finder;
use Symfony\Component\Finder\SplFileInfo;
use xmlHandler\exception\XMLHandlerException;
use xmlHandler\XMLFileHandler;

/**
 * Class LintCommand
 *
 *
 * @package sclable\xmlLint\console\command
 * @author Michael Rutz <michael.rutz@sclable.com>
 *
 * @const string COMMAND_NAME the name of the command
 * @const string ARGUMENT_FILE the name of the file argument
 * @const string OPTION_RECURSIVE the name of the recursive option
 */
class LintCommand extends Command
{
    const COMMAND_NAME = 'lint';
    const ARGUMENT_FILE = 'file';
    const OPTION_RECURSIVE = 'recursive';
    const OPTION_EXCLUDE   = 'exclude';

    /** @var OutputInterface */
    protected $output;

    /** @var InputInterface */
    protected $input;

    /** @var \SplQueue */
    protected $errorQueue;

    /**
     * @inheritdoc
     */
    protected function configure()
    {
        $this
            ->setName(self::COMMAND_NAME)
            ->setDescription('lint an xml file')
            ->addArgument(
                self::ARGUMENT_FILE,
                InputArgument::REQUIRED,
                'the path/to/file.xml to lint or a directory to lint all files'
            )
            ->addOption(
                self::OPTION_RECURSIVE,
                'r',
                InputOption::VALUE_OPTIONAL,
                'whether to scan directories recursive.',
                true
            )
            ->addOption(
                self::OPTION_EXCLUDE,
                'e',
                InputOption::VALUE_OPTIONAL,
                'path(s) to exclude from linting, can be several separated by comma'
            )
        ;
    }

    /**
     * @inheritdoc
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $this->output = $output;
        $this->input = $input;
        $this->errorQueue = new \SplQueue();

        $file = $input->getArgument(self::ARGUMENT_FILE);

        $output->writeln('progress: ');
        if (is_dir($file)) {
            $status = $this->lintDir($file);
        } else {
            $status = $this->lintFile($file);
        }
        $output->writeln('');

        if ($status === false) {
            $this->printErrorQueue();
            return 1;
        }

        $this->output->writeln(PHP_EOL . '<info>done</info>');

        return 0;
    }

    /**
     * lint the content of a directory, recursive if defined
     * @param string $dir path/to/dir
     * @return bool
     */
    private function lintDir($dir)
    {
        $finder = new Finder();
        $finder->files()
            ->name('*.xml')
            ->name('*.xml.dist')
            ->in($dir);

        if (!$this->input->getOption(self::OPTION_RECURSIVE)) {
            $finder->depth(0);
        }

        if ($this->input->hasOption(self::OPTION_EXCLUDE)) {
            $exclude = explode(',', $this->input->getOption(self::OPTION_EXCLUDE));
            $finder->exclude($exclude);
        }

        $totalFiles = $finder->count();

        $counter = 0;
        $ret = true;
        /** @var SplFileInfo $file */
        foreach ($finder as $file) {
            $ret = $this->lintFile($file->getRealPath()) && $ret;
            if (++$counter % 30 == 0) {
                $this->output->writeln(sprintf(
                    '    (%s/%s %s%%)',
                    $counter,
                    $totalFiles,
                    round($counter/$totalFiles*100, 0)
                ));
            }
        }

        return $ret;
    }

    /**
     * format and print the errors from the queue to the output
     */
    private function printErrorQueue()
    {
        $this->output->writeln(PHP_EOL . '<error>errors:</error>');

        foreach ($this->errorQueue as $error) {
            $this->output->writeln('file: ' . $error->file);
            $this->output->write($error->message . PHP_EOL);
            $this->output->write(' - - ' . PHP_EOL);
        }
    }

    /**
     * lint a file, pass errors to the queue
     * @param string $file path/to/file
     * @return bool
     */
    private function lintFile($file)
    {

        if ($this->output->getVerbosity() >= OutputInterface::VERBOSITY_VERBOSE) {
            $this->output->write('lint file ' . $file . ' ... ');
        }

        $status = false;
        $handler = XMLFileHandler::createXmlHandlerFromFile($file);
        try {
            $handler->getDOMDocument();
            $status = true;
        } catch (XMLHandlerException $e) {
            $msg = new \stdClass();
            if ($handler->hasErrors()) {
                $formatter = new ErrorFormatter($handler->getXmlErrors());
                $formatter->setPrefix('    > ');
                $msg->file = $file;
                $msg->message = $formatter->getErrorsAsString();
            } else {
                $msg->file = $file;
                $msg->message = sprintf(
                    '    > %s (Exception: "%s")' . PHP_EOL,
                    $e->getMessage(),
                    get_class($e)
                );
            }
            $this->errorQueue->add($this->errorQueue->count(), $msg);
        }

        if ($this->output->getVerbosity() >= OutputInterface::VERBOSITY_VERBOSE) {
            if ($status === false) {
                $this->output->writeln('<error>errors</error>');
            } else {
                $this->output->writeln('<info>passed.</info>');
            }
        } else {
            if ($status === false) {
                $this->output->write('<error>F</error>');
            } else {
                $this->output->write('.');
            }
        }

        return $status;
    }
}
