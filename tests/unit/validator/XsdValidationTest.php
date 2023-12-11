<?php

/**
 * This file is part of the Sclable Xml Lint Package.
 *
 * @copyright (c) 2022 Sclable Business Solutions GmbH
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace sclable\xmlLint\tests\unit\validator;

use PHPUnit\Framework\TestCase;
use sclable\xmlLint\data\FileReport;
use sclable\xmlLint\validator\XsdValidation;
use sclable\xmlLint\validator\helper\LibXmlErrorFormatter;

/**
 * Class XsdValidationTest.
 *
 * @coversDefaultClass \sclable\xmlLint\validator\XsdValidation
 */
class XsdValidationTest extends TestCase
{
    public function testValidateIntactFileWithXsd()
    {
        $file = new \SplFileInfo(
            dirname(dirname(__DIR__)) . '/functional/_testdata/with_xsd.xml'
        );
        $report = new FileReport($file);

        $validator = new XsdValidation(new LibXmlErrorFormatter());
        $validator->validateFile($report);

        $this->assertFalse($report->hasProblems());
    }

    public function testValidateCorruptFileWithXsd()
    {
        $file = new \SplFileInfo(
            dirname(dirname(__DIR__)) . '/functional/_testdata/with_xsd_broken.xml'
        );
        $report = new FileReport($file);

        $validator = new XsdValidation(new LibXmlErrorFormatter());
        $validator->validateFile($report);

        $this->assertTrue($report->hasProblems());
    }

    public function testSkipNotExistingFile()
    {
        $file = new \SplFileInfo('not_exists.xml');

        $mock = $this->getMockBuilder(FileReport::class)
            ->onlyMethods(['reportProblem'])
            ->setConstructorArgs([$file])
            ->getMock();
        $mock->expects($this->exactly(0))
            ->method('reportProblem');

        $validator = new XsdValidation(new LibXmlErrorFormatter());
        /* @var FileReport $mock */
        $this->assertFalse($validator->validateFile($mock));
    }

    public function testSkipFileWithoutXsd()
    {
        $file = new \SplFileInfo(
            dirname(dirname(__DIR__)) . '/functional/_testdata/fourtytwo.xml'
        );
        $report = new FileReport($file);

        $validator = new XsdValidation(new LibXmlErrorFormatter());
        $return = $validator->validateFile($report);

        $this->assertTrue($return);
        $this->assertFalse($report->hasProblems());
    }

    public function testReportNonExistingSchemaFile()
    {
        $file = new \SplFileInfo(
            dirname(__DIR__) . '/_testdata/with_not_existing_xsd.xml'
        );

        $mock = $this->getMockBuilder(FileReport::class)
            ->onlyMethods(['reportProblem', 'hasProblems'])
            ->setConstructorArgs([$file])
            ->getMock();
        $mock->method('hasProblems')->willReturn(true);
        $mock->expects($this->once())
            ->method('reportProblem')
            ->with(
                'unable to validate, schema file is not readable: '
                . dirname(__DIR__) . '/_testdata/i_dont_exist.xsd'
            );

        $validator = new XsdValidation(new LibXmlErrorFormatter());
        /* @var FileReport $mock */
        $this->assertFalse($validator->validateFile($mock));
    }

    /**
     * @covers ::<private>
     */
    public function testUseInternalCache()
    {
        $file = new \SplFileInfo(
            dirname(dirname(__DIR__)) . '/functional/_testdata/with_xsd.xml'
        );
        $report = new FileReport($file);
        $validator = new XsdValidation(new LibXmlErrorFormatter());
        $this->assertTrue($validator->validateFile($report));
        $this->assertFalse($report->hasProblems());
        $this->assertTrue($validator->validateFile($report));
    }

    /**
     * @covers ::<private>
     */
    public function testEmptyXml()
    {
        $file = new \SplFileInfo(
            dirname(__DIR__) . '/_testdata/empty.xml'
        );

        $report = new FileReport($file);
        $validator = new XsdValidation(new LibXmlErrorFormatter());
        $this->assertFalse($validator->validateFile($report));
    }

    /**
     * @covers ::<private>
     */
    public function testEmptyXsd()
    {
        $file = new \SplFileInfo(
            dirname(__DIR__) . '/_testdata/with_empty_xsd.xml'
        );

        $mock = $this->getMockBuilder(FileReport::class)
            ->onlyMethods(['reportProblem', 'hasProblems'])
            ->setConstructorArgs([$file])
            ->getMock();
        $mock->method('hasProblems')->willReturn(true);
        $mock->expects($this->once())
            ->method('reportProblem')
            ->with($this->stringContains('xsd validation file is empty'));

        $validator = new XsdValidation(new LibXmlErrorFormatter());
        /* @var FileReport $mock */
        $this->assertFalse($validator->validateFile($mock));
    }

    /**
     * @covers ::<private>
     */
    public function testDeadUrlXsd()
    {
        $file = new \SplFileInfo(
            dirname(__DIR__) . '/_testdata/with_bad_url_xsd.xml'
        );

        $mock = $this->getMockBuilder(FileReport::class)
            ->onlyMethods(['reportProblem', 'hasProblems'])
            ->setConstructorArgs([$file])
            ->getMock();
        $mock->method('hasProblems')->willReturn(true);
        $mock->expects($this->once())
            ->method('reportProblem')
            ->with($this->stringContains('unable to load schema file from'));

        $validator = new XsdValidation(new LibXmlErrorFormatter());
        /* @var FileReport $mock */
        $this->assertFalse($validator->validateFile($mock));
    }
}
