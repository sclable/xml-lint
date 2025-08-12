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
use sclable\xmlLint\validator\helper\LibXmlErrorFormatter;

/**
 * Class XsdValidation.
 */
class XsdValidation implements ValidationInterface
{
    /** @var LibXmlErrorFormatter */
    private $formatter;

    /** @var array */
    private $cache = [];

    /**
     * XsdValidation constructor.
     */
    public function __construct(LibXmlErrorFormatter $formatter)
    {
        $this->formatter = $formatter;
        libxml_use_internal_errors(true);
    }

    public function validateFile(FileReport $report)
    {
        $file = $report->getFile()->getRealPath();

        if (empty($file)) {
            return false;
        }

        $domDoc = new \DOMDocument();
        $loaded = $domDoc->load($file, LIBXML_NOERROR | LIBXML_NOWARNING | LIBXML_PEDANTIC);

        if (false === $loaded) {
            return false;
        }

        $validation = $this->getSchemaValidationFile($domDoc);

        if (false === $validation) {
            return true;
        }

        $validationSource = $this->getSchemaValidationSource($validation, $report);

        if (false === $validationSource) {
            return false;
        }

        libxml_clear_errors();
        if (true !== $domDoc->schemaValidateSource($validationSource)) {
            $errors = libxml_get_errors();
            foreach ($this->formatter->formatErrors($errors) as $problem) {
                $report->reportProblem($problem);
            }

            return false;
        }

        return true;
    }

    /**
     * @param string     $filename
     * @param FileReport $report
     *
     * @return bool|string
     */
    private function getSchemaValidationSource($filename, $report)
    {
        if (0 === preg_match('/^(http|https|ftp):/i', $filename)) {
            if (false === file_exists($filename)) {
                $filename = $report->getFile()->getPath() . '/' . $filename;
            }
            if (!is_readable($filename)) {
                $report->reportProblem('unable to validate, schema file is not readable: ' . $filename);

                return false;
            }
        }

        if (isset($this->cache[$filename])) {
            return $this->cache[$filename];
        }

        $validationSource = @file_get_contents($filename);

        if (false === $validationSource) {
            $report->reportProblem('unable to load schema file from: ' . $filename);

            return false;
        }

        if (empty($validationSource)) {
            $report->reportProblem(sprintf('xsd validation file is empty ("%s").', $filename));

            return false;
        }

        return $this->cache[$filename] = $validationSource;
    }

    /**
     * @return bool|string
     */
    private function getSchemaValidationFile(\DOMDocument $document)
    {
        $firstChild = $this->getFirstChild($document);
        // @codeCoverageIgnoreStart
        if (false === $firstChild) {
            return false;
        }
        // @codeCoverageIgnoreEnd

        $attribute = $firstChild->getAttribute('xsi:noNamespaceSchemaLocation');

        if (empty($attribute)) {
            return false;
        }

        return $attribute;
    }

    /**
     * @return bool|\DOMElement
     */
    private function getFirstChild(\DOMDocument $document)
    {
        foreach ($document->childNodes as $child) {
            if ($child instanceof \DOMElement) {
                return $child;
                // @codeCoverageIgnoreStart
            }
        }

        return false;
        // @codeCoverageIgnoreEnd
    }
}
