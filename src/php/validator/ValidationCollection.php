<?php
/**
 * This file is part of the Sclable Xml Lint Package.
 *
 * @copyright (c) 2015 Sclable Business Solutions GmbH
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace sclable\xmlLint\validator;

use sclable\xmlLint\data\FileReport;

/**
 * Class ValidationCollection
 *
 *
 * @package php\validator
 * @author Michael Rutz <michael.rutz@sclable.com>
 *
 */
class ValidationCollection implements ValidationInterface
{
    /** @var ValidationInterface[] */
    private $collection = [];

    /**
     * @inheritDoc
     */
    public function validateFile(FileReport $report)
    {
        $status = true;
        foreach ($this->collection as $validation) {
            $status = $validation->validateFile($report) && $status;
        }

        return $status;
    }

    public function addValidation(ValidationInterface $validation)
    {
        $this->collection[] = $validation;
        return $this;
    }
}
