<?php

/**
 * This file is part of the Sclable Xml Lint Package.
 *
 * @copyright (c) 2025 Sclable Business Solutions GmbH
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace sclable\xmlLint\validator\helper;

/**
 * Class LibXmlErrorFormatter.
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
