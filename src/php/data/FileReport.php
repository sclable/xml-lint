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

namespace sclable\xmlLint\data;

/**
 * @internal
 */
final class FileReport
{
    /** @var list<ValidationProblem> */
    private array $problems = [];

    public function __construct(private readonly \SplFileInfo $file)
    {
    }

    public static function create(\SplFileInfo|string $file): self
    {
        if (is_string($file)) {
            $file = new \SplFileInfo($file);
        }

        return new self($file);
    }

    public function reportProblem(string $msg): self
    {
        $this->addProblem(ValidationProblem::create($msg));

        return $this;
    }

    /**
     * report a problem to a file.
     */
    public function addProblem(ValidationProblem $problem): self
    {
        $this->problems[] = $problem;

        return $this;
    }

    /**
     * indicate whether a file has any reported problems or not.
     */
    public function hasProblems(): bool
    {
        return [] !== $this->problems;
    }

    public function getFile(): \SplFileInfo
    {
        return $this->file;
    }

    /**
     * @return list<ValidationProblem>
     */
    public function getProblems(): array
    {
        return $this->problems;
    }
}
