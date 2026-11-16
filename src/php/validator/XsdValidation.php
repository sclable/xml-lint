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
 * @internal
 */
final class XsdValidation implements ValidationInterface
{
    /** @var array<string, string> */
    private array $cache = [];

    public function __construct(private readonly LibXmlErrorFormatter $formatter)
    {
        libxml_use_internal_errors(true);
    }

    public function validateFile(FileReport $report): bool
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

    private function getSchemaValidationSource(string $filename, FileReport $report): string|false
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

    private function getSchemaValidationFile(\DOMDocument $document): string|false
    {
        $firstChild = $this->getFirstChild($document);
        // @codeCoverageIgnoreStart
        if (false === $firstChild) {
            return false;
        }
        // @codeCoverageIgnoreEnd

        $schemaLocation = $firstChild->getAttribute('xsi:schemaLocation');
        $noNamespaceSchemaLocation = $firstChild->getAttribute('xsi:noNamespaceSchemaLocation');

        if ($schemaLocation === '' && $noNamespaceSchemaLocation === '') {
            return false;
        }

        if ($noNamespaceSchemaLocation !== '') {
            return $noNamespaceSchemaLocation;
        }

        $parts = explode(' ', $schemaLocation);

        if (count($parts) === 2) {
            return trim($parts[1]);
        }

        return false;
    }

    private function getFirstChild(\DOMDocument $document): \DOMElement|false
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
