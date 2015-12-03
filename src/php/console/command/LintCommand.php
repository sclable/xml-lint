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

use sclable\xmlLint\data\FileReport;
use sclable\xmlLint\validator\ValidationFactory;
use sclable\xmlLint\validator\ValidationInterface;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Finder\Finder;
use Symfony\Component\Finder\SplFileInfo;

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
    const OPTION_PATTERN   = 'pattern';
    const OPTION_NO_XSD   = 'skip-xsd';

    /** @var OutputInterface */
    protected $output;

    /** @var InputInterface */
    protected $input;

    /** @var ValidationInterface */
    private $validator;

    /** @var FileReport[] */
    private $reports = [];

    /** @var float */
    private $start;

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
                'the path/to/file.xml to lint a single file or a path/to/directory to lint all xml ' .
                'files in a directory.'
            )
            ->addOption(
                self::OPTION_RECURSIVE,
                'r',
                InputOption::VALUE_OPTIONAL,
                'Whether to scan directories recursive.',
                true
            )
            ->addOption(
                self::OPTION_EXCLUDE,
                'e',
                InputOption::VALUE_REQUIRED,
                'Path(s) to exclude from linting, can be several separated by comma'
            )
            ->addOption(
                self::OPTION_PATTERN,
                'p',
                InputOption::VALUE_REQUIRED,
                'Filter files with one or more patterns, e.g.: *.svg,*.xml. Separate patterns by comma.'
            )
            ->addOption(
                self::OPTION_NO_XSD,
                's',
                InputOption::VALUE_NONE,
                'Skip downloading and checking against XSD-files.'
            )
        ;
    }

    /**
     * @inheritdoc
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $this->start = microtime(true);
        $this->output = $output;
        $this->input = $input;

        if ($input->getOption(self::OPTION_NO_XSD)) {
            $this->validator = ValidationFactory::createLintOnlyValidation();
        } else {
            $this->validator = ValidationFactory::createDefaultCollection();
        }

        $file = $input->getArgument(self::ARGUMENT_FILE);

        $output->writeln('progress: ');
        if (is_dir($file)) {
            $status = $this->lintDir($file);
        } else {
            $status = $this->lintFile(new \SplFileInfo($file));
        }

        $output->writeln('');

        if ($status === false) {
            $this->printReportsOfFilesWithProblems();
        }

        $this->output->writeln(sprintf(
            PHP_EOL . '%d files / %1.2f seconds <info>done</info>',
            count($this->reports),
            microtime(true) - $this->start
        ));

        return $status ? 0 : 1;
    }

    /**
     * lint the content of a directory, recursive if defined
     * @param string $dir path/to/dir
     * @return bool
     */
    private function lintDir($dir)
    {
        $finder = Finder::create();
        $finder->files()
            ->in($dir);

        $patterns = $this->input->getOption(self::OPTION_PATTERN);
        if (!empty($patterns)) {
            $patterns = explode(',', $patterns);
            foreach ($patterns as $pattern) {
                $finder->name(trim($pattern));
            }
        } else {
            $finder->name('*.xml.dist')
                ->name('*.xml');
        }

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
            $ret = $this->lintFile($file) && $ret;
            if (++$counter % 30 == 0) {
                $this->output->writeln(sprintf(
                    ' %8d/%d %6.2f%%',
                    $counter,
                    $totalFiles,
                    $counter / $totalFiles * 100
                ));
            }
        }

        return $ret;
    }

    /**
     * format and print the errors from the queue to the output
     */
    private function printReportsOfFilesWithProblems()
    {
        $this->output->writeln(PHP_EOL . '<error>errors:</error>');

        foreach ($this->reports as $report) {
            if ($report->hasProblems() === false) {
                continue;
            }

            $file = $report->getFile();
            $this->output->writeln('file: ' . $file->getPath() . DIRECTORY_SEPARATOR . $file->getFilename());

            foreach ($report->getProblems() as $problem) {
                $this->output->write(
                    '  > ' . $problem->getMessage() . PHP_EOL
                );
            }

            $this->output->write(' - - ' . PHP_EOL);
        }
    }

    /**
     * lint a file, pass errors to the queue
     * @param \SplFileInfo $file
     * @return bool
     */
    private function lintFile(\SplFileInfo $file)
    {

        if ($this->output->getVerbosity() >= OutputInterface::VERBOSITY_VERBOSE) {
            $this->output->write('lint file ' . $file . ' ... ');
        }

        $report = FileReport::create($file);
        $this->reports[] = $report;
        $status = $this->validator->validateFile($report);

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
