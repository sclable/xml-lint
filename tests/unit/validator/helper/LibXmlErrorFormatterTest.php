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

namespace sclable\xmlLint\tests\unit\validator\helper;

use PHPUnit\Framework\TestCase;
use sclable\xmlLint\validator\helper\LibXmlErrorFormatter;

/**
 * Class LibXmlErrorFormatterTest.
 *
 *
 * @author Michael Rutz <michael.rutz@sclable.com>
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
