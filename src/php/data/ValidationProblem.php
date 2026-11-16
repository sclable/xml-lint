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
 * Class ValidationProblem.
 */
class ValidationProblem
{
    /**
     * factory method to create a validation problem.
     */
    public static function create(string $message): static
    {
        return new static($message);
    }

    public function __construct(private readonly string $message)
    {
    }

    public function getMessage(): string
    {
        return $this->message;
    }
}
