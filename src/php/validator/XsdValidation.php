<?php
/**
 * ----------------------------------------------------------------------------
 * This code is part of the Sclable Business Application Development Platform
 * and is subject to the provisions of your License Agreement with
 * Sclable Business Solutions GmbH.
 *
 * @copyright (c) 2015 Sclable Business Solutions GmbH
 * ----------------------------------------------------------------------------
 */
namespace sclable\xmlLint\validator;

use sclable\xmlLint\data\FileReport;
use sclable\xmlLint\validator\helper\LibXmlErrorFormatter;

/**
 * Class XsdValidation
 *
 *
 * @package sclable\xmlLint\validator\xsdValidation
 * @author Michael Rutz <michael.rutz@sclable.com>
 *
 */
class XsdValidation implements ValidationInterface
{
    /** @var LibXmlErrorFormatter */
    private $formatter;

    /** @var array */
    private $cache = [];

    /**
     * XsdValidation constructor.
     * @param LibXmlErrorFormatter $formatter
     */
    public function __construct(LibXmlErrorFormatter $formatter)
    {
        $this->formatter = $formatter;
        libxml_use_internal_errors(true);
    }


    /**
     * @inheritDoc
     */
    public function validateFile(FileReport $report)
    {
        $file = $report->getFile()->getPath() . '/' . $report->getFile()->getBasename();
        $domDoc = new \DOMDocument();
        $loaded = $domDoc->load($file, LIBXML_NOERROR | LIBXML_NOWARNING | LIBXML_PEDANTIC);

        if ($loaded === false) {
            return false;
        }

        $validation = $this->getSchemaValidationFile($domDoc);

        if ($validation === false) {
            return true;
        }

        $validationSource = $this->getSchemaValidationSource($validation, $report);

        if ($validationSource === false) {
            return false;
        }

        libxml_clear_errors();
        if ($domDoc->schemaValidateSource($validationSource) !== true) {
            $errors = libxml_get_errors();
            foreach ($this->formatter->formatErrors($errors) as $problem) {
                $report->reportProblem($problem);
            }
            return false;
        }

        return true;
    }

    /**
     * @param string $filename
     * @param FileReport $report
     * @return bool|string
     */
    private function getSchemaValidationSource($filename, $report)
    {
        if ((preg_match('/^(http|https|ftp):/i', $filename) === 0)) {
            if (file_exists($filename) === false) {
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

        if ($validationSource === false) {
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
     * @param \DOMDocument $document
     * @return bool|string
     */
    private function getSchemaValidationFile(\DOMDocument $document)
    {
        $firstChild = $this->getFirstChild($document);
        // @codeCoverageIgnoreStart
        if ($firstChild === false) {
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
     * @param \DOMDocument $document
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
