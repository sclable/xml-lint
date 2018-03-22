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
 * Interface ValidationInterface.
 *
 * @author Michael Rutz <michael.rutz@sclable.com>
 */
interface ValidationInterface
{
    /**
     * @param FileReport $report
     *
     * @return bool
     */
    public function validateFile(FileReport $report);
}
