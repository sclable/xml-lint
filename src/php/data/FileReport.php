<?php

/**
 * This file is part of the Sclable Xml Lint Package.
 *
 * @copyright (c) 2020 Sclable Business Solutions GmbH
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace sclable\xmlLint\data;

/**
 * Class FileReport.
 */
class FileReport
{
    /** @var \SplFileInfo */
    private $file;

    /** @var ValidationProblem[] */
    private $problems = [];

    /**
     * FileReport constructor.
     */
    public function __construct(\SplFileInfo $file)
    {
        $this->file = $file;
    }

    /**
     * @param \SplFileInfo|string $file
     *
     * @return static
     */
    public static function create($file)
    {
        if (false === ($file instanceof \SplFileInfo)) {
            $file = new \SplFileInfo($file);
        }

        return new static($file);
    }

    /**
     * @param string $msg
     *
     * @return $this
     */
    public function reportProblem($msg)
    {
        $this->addProblem(ValidationProblem::create($msg));

        return $this;
    }

    /**
     * report a problem to a file.
     *
     * @return $this
     */
    public function addProblem(ValidationProblem $problem)
    {
        $this->problems[] = $problem;

        return $this;
    }

    /**
     * indicate whether a file has any reported problems or not.
     *
     * @return bool
     */
    public function hasProblems()
    {
        return !empty($this->problems);
    }

    /**
     * @return \SplFileInfo
     */
    public function getFile()
    {
        return $this->file;
    }

    /**
     * @return ValidationProblem[]
     */
    public function getProblems()
    {
        return $this->problems;
    }
}
