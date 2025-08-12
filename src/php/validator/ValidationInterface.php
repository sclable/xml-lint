<?php

/**
 * This file is part of the Sclable Xml Lint Package.
 *
 * @copyright (c) 2025 Sclable Business Solutions GmbH
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace sclable\xmlLint\validator;

use sclable\xmlLint\data\FileReport;

/**
 * Interface ValidationInterface.
 */
interface ValidationInterface
{
    /**
     * @return bool
     */
    public function validateFile(FileReport $report);
}
