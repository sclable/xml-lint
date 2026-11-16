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

namespace sclable\xmlLint\tests\unit\validator;

use PHPUnit\Framework\TestCase;
use sclable\xmlLint\data\FileReport;
use sclable\xmlLint\validator\XsdValidation;
use PHPUnit\Framework\Attributes\CoversClass;
use sclable\xmlLint\validator\helper\LibXmlErrorFormatter;

/**
 * Class XsdValidationTest.
 */
#[CoversClass(XsdValidation::class)]
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

        $report = new FileReport($file);

        $validator = new XsdValidation(new LibXmlErrorFormatter());
        $this->assertFalse($validator->validateFile($report));
        $this->assertSame([], self::messages($report));
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

        $report = new FileReport($file);

        $validator = new XsdValidation(new LibXmlErrorFormatter());
        $this->assertFalse($validator->validateFile($report));
        $this->assertSame(
            ['unable to validate, schema file is not readable: ' . dirname(__DIR__) . '/_testdata/i_dont_exist.xsd'],
            self::messages($report)
        );
    }

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

    public function testEmptyXml()
    {
        $file = new \SplFileInfo(
            dirname(__DIR__) . '/_testdata/empty.xml'
        );

        $report = new FileReport($file);
        $validator = new XsdValidation(new LibXmlErrorFormatter());
        $this->assertFalse($validator->validateFile($report));
    }

    public function testEmptyXsd()
    {
        $file = new \SplFileInfo(
            dirname(__DIR__) . '/_testdata/with_empty_xsd.xml'
        );

        $report = new FileReport($file);

        $validator = new XsdValidation(new LibXmlErrorFormatter());
        $this->assertFalse($validator->validateFile($report));
        $this->assertCount(1, self::messages($report));
        $this->assertStringContainsString('xsd validation file is empty', self::messages($report)[0]);
    }

    public function testDeadUrlXsd()
    {
        $file = new \SplFileInfo(
            dirname(__DIR__) . '/_testdata/with_bad_url_xsd.xml'
        );

        $report = new FileReport($file);

        $validator = new XsdValidation(new LibXmlErrorFormatter());
        $this->assertFalse($validator->validateFile($report));
        $this->assertCount(1, self::messages($report));
        $this->assertStringContainsString('unable to load schema file from', self::messages($report)[0]);
    }

    /**
     * @return list<string>
     */
    private static function messages(FileReport $report): array
    {
        return array_map(static fn ($problem) => $problem->getMessage(), $report->getProblems());
    }
}
