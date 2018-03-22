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
use sclable\xmlLint\validator\helper\LibXmlErrorFormatter;

/**
 * Class LintValidation.
 *
 *
 * @author Michael Rutz <michael.rutz@sclable.com>
 */
class LintValidation implements ValidationInterface
{
    /** @var LibXmlErrorFormatter */
    private $formatter;

    /**
     * LintValidation constructor.
     *
     * @param LibXmlErrorFormatter $formatter
     */
    public function __construct(LibXmlErrorFormatter $formatter)
    {
        $this->formatter = $formatter;
        libxml_use_internal_errors(true);
    }

    /**
     * {@inheritdoc}
     */
    public function validateFile(FileReport $report)
    {
        $realPath = $report->getFile()->getRealPath();

        if (false === is_file($realPath)) {
            $report->reportProblem('file not found: ' . $realPath);

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
