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

namespace sclable\xmlLint\validator\helper;

/**
 * Class LibXmlErrorFormatter.
 */
class LibXmlErrorFormatter
{
    private string $format = 'Line %s: [%s] %s';

    /**
     * @param array<\LibXMLError> $xmlErrors
     *
     * @return array<string>
     */
    public function formatErrors(array $xmlErrors): array
    {
        $messages = [];
        foreach ($xmlErrors as $xmlError) {
            $messages[] = $this->format($xmlError);
        }

        return array_unique($messages);
    }

    private function format(\LibXMLError $xmlError): string
    {
        return sprintf(
            $this->format,
            $xmlError->line,
            $xmlError->code,
            trim($xmlError->message)
        );
    }

    public function setFormat(string $format): static
    {
        $this->format = $format;

        return $this;
    }
}
