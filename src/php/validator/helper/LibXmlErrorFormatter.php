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

namespace sclable\xmlLint\validator\helper;

/**
 * Class LibXmlErrorFormatter.
 *
 *
 * @author Michael Rutz <michael.rutz@sclable.com>
 */
class LibXmlErrorFormatter
{
    private $format = 'Line %s: [%s] %s';

    public function formatErrors(array $xmlErrors)
    {
        $messages = [];
        foreach ($xmlErrors as $xmlError) {
            $messages[] = $this->format($xmlError);
        }

        return array_unique($messages);
    }

    private function format($xmlError)
    {
        return sprintf(
            $this->format,
            $xmlError->line,
            $xmlError->code,
            trim($xmlError->message)
        );
    }

    public function setFormat($format)
    {
        $this->format = $format;

        return $this;
    }
}
