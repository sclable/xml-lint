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
use sclable\xmlLint\validator\LintValidation;
use sclable\xmlLint\validator\helper\LibXmlErrorFormatter;

/**
 * Class LintValidationTest.
 */
class LintValidationTest extends TestCase
{
    public function testNoValidationProblemsForIntactFile(): void
    {
        $validator = new LintValidation(new LibXmlErrorFormatter());

        $filename = dirname(dirname(__DIR__)) . '/functional/_testdata/fourtytwo.xml';
        $file = new \SplFileInfo($filename);

        $report = new FileReport($file);

        $this->assertTrue($validator->validateFile($report));
        $this->assertSame([], self::messages($report));
    }

    public function testReportProblemsForInvalidFile(): void
    {
        $validator = new LintValidation(new LibXmlErrorFormatter());

        $filename = dirname(dirname(__DIR__)) . '/functional/_testdata/broken.xml';
        $file = new \SplFileInfo($filename);

        $report = new FileReport($file);

        $this->assertFalse($validator->validateFile($report));
        $this->assertGreaterThanOrEqual(2, count(self::messages($report)));
    }

    public function testReportExceptionTextIfNoErrorsAvailable(): void
    {
        $validator = new LintValidation(new LibXmlErrorFormatter());

        $file = new \SplFileInfo('does_not_exist.xml');

        $report = new FileReport($file);

        $this->assertFalse($validator->validateFile($report));
        $this->assertSame(['file not found: does_not_exist.xml'], self::messages($report));
    }

    public function testReportXmlFileNotReadable(): void
    {
        $validator = new LintValidation(new LibXmlErrorFormatter());
        $filename = dirname(dirname(__DIR__)) . '/functional/_testdata/fourtytwo.xml';
        $file = new \SplFileInfo($filename);
        $fileMod = $file->getPerms();
        try {
            chmod($filename, 0o333);
            $report = new FileReport($file);

            $this->assertFalse($validator->validateFile($report));
            $this->assertSame(['file not readable: ' . $file->getRealPath()], self::messages($report));
        } finally {
            chmod($filename, $fileMod);
        }
    }

    /**
     * @return list<string>
     */
    private static function messages(FileReport $report): array
    {
        return array_map(static fn ($problem) => $problem->getMessage(), $report->getProblems());
    }
}
