<?php

/**
 * This file is part of the Sclable Xml Lint Package.
 *
 * @copyright (c) 2025 Sclable Business Solutions GmbH
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace sclable\xmlLint\tests\unit\data;

use PHPUnit\Framework\TestCase;
use sclable\xmlLint\data\FileReport;
use sclable\xmlLint\data\ValidationProblem;

/**
 * Class FileReportTest.
 */
class FileReportTest extends TestCase
{
    public function testHasProblemsReturnsFalseOnNoProblems()
    {
        $report = new FileReport(new \SplFileInfo('no_file.xml'));
        $this->assertFalse($report->hasProblems());
    }

    public function testHasProblemsReturnsTrueOnProblems()
    {
        $report = new FileReport(new \SplFileInfo('no_file.xml'));
        $problem = $this->getMockBuilder(ValidationProblem::class)
            ->disableOriginalConstructor()
            ->getMock();

        /* @var ValidationProblem $problem */
        $report->addProblem($problem);
        $this->assertTrue($report->hasProblems());
    }

    public function testCreateFromString()
    {
        $report = FileReport::create('no_file.xml');
        $this->assertInstanceOf(FileReport::class, $report);
    }

    public function testReportProblemCreatesValidationProblem()
    {
        $report = FileReport::create('test_file.xml');
        $report->reportProblem('my_message');
        $result = $report->getProblems();
        $problem = reset($result);

        $this->assertInstanceOf(ValidationProblem::class, $problem);
        $this->assertEquals('my_message', $problem->getMessage());
    }
}
