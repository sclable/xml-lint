<?php

/**
 * This file is part of the Sclable Xml Lint Package.
 *
 * @copyright (c) 2020 Sclable Business Solutions GmbH
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace sclable\xmlLint\tests\unit\validator\helper;

use PHPUnit\Framework\TestCase;
use sclable\xmlLint\validator\helper\LibXmlErrorFormatter;

/**
 * Class LibXmlErrorFormatterTest.
 */
class LibXmlErrorFormatterTest extends TestCase
{
    public function testFormatError()
    {
        $errors = [$this->createLibXmlError('test', 1, 2)];
        $this->assertEquals(
            [
                'Line 2: [1] test',
            ],
            (new LibXmlErrorFormatter())->formatErrors($errors)
        );
    }

    public function testCustomFormatError()
    {
        $errors = [$this->createLibXmlError('test', 1, 2)];
        $formatter = new LibXmlErrorFormatter();
        $formatter->setFormat('%s / %s / %s');
        $this->assertEquals(
            [
                '2 / 1 / test',
            ],
            $formatter->formatErrors($errors)
        );
    }

    public function testFilterDuplicates()
    {
        $errors = [
            $this->createLibXmlError('test', 1, 2),
            $this->createLibXmlError('test', 1, 2),
        ];

        $this->assertCount(1, (new LibXmlErrorFormatter())->formatErrors($errors));
    }

    /**
     * @param string $msg
     * @param int    $code
     * @param int    $line
     *
     * @return \LibXMLError
     */
    private function createLibXmlError($msg, $code, $line)
    {
        $error = new \LibXMLError();
        $error->message = $msg;
        $error->code = $code;
        $error->line = $line;

        return $error;
    }
}
