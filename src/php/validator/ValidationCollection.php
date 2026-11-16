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

namespace sclable\xmlLint\validator;

use sclable\xmlLint\data\FileReport;

/**
 * @internal
 */
final class ValidationCollection implements ValidationInterface
{
    /** @var list<ValidationInterface> */
    private array $collection = [];

    public function validateFile(FileReport $report): bool
    {
        $status = true;
        foreach ($this->collection as $validation) {
            $status = $validation->validateFile($report) && $status;
        }

        return $status;
    }

    public function addValidation(ValidationInterface $validation): self
    {
        $this->collection[] = $validation;

        return $this;
    }
}
