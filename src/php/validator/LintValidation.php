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
use sclable\xmlLint\validator\helper\LibXmlErrorFormatter;

/**
 * Class LintValidation.
 */
class LintValidation implements ValidationInterface
{
    public function __construct(private readonly LibXmlErrorFormatter $formatter)
    {
        libxml_use_internal_errors(true);
    }

    public function validateFile(FileReport $report): bool
    {
        $realPath = $report->getFile()->getRealPath();

        if (false === $realPath || false === is_file($realPath)) {
            $report->reportProblem('file not found: ' . $report->getFile()->getPathname());

            return false;
        }

        if (false === is_readable($realPath)) {
            $report->reportProblem('file not readable: ' . $realPath);

            return false;
        }

        libxml_clear_errors();
        $domDoc = new \DOMDocument();
        $domDoc->load($realPath, LIBXML_NOERROR | LIBXML_NOWARNING | LIBXML_PEDANTIC);

        $errors = libxml_get_errors();
        foreach ($this->formatter->formatErrors($errors) as $problem) {
            $report->reportProblem($problem);
        }

        return !$report->hasProblems();
    }
}
