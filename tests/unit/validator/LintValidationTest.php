<?php
/**
 * This file is part of the Sclable Xml Lint Package.
 *
 * @copyright (c) 2015 Sclable Business Solutions GmbH
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace sclable\xmlLint\tests\unit\validator;

use sclable\xmlLint\data\FileReport;
use sclable\xmlLint\validator\LintValidation;

/**
 * Class LintValidationTest
 *
 *
 * @package sclable\xmlLint\tests\unit\validator
 * @author Michael Rutz <michael.rutz@sclable.com>
 *
 */
class LintValidationTest extends \PHPUnit_Framework_TestCase
{

    public function testNoValidationProblemsForIntactFile()
    {
        $validator = new LintValidation();

        $filename = dirname(dirname(__DIR__)) . '/functional/_testdata/fourtytwo.xml';
        $file = new \SplFileInfo($filename);

        $mock = $this->getMock(FileReport::class, null, [$file]);
        $mock->expects($this->exactly(0))
            ->method('reportProblem');

        /** @var FileReport $mock */
        $return = $validator->validateFile($mock);
        $this->assertTrue($return);
    }

    public function testReportProblemsForInvalidFile()
    {
        $validator = new LintValidation();

        $filename = dirname(dirname(__DIR__)) . '/functional/_testdata/broken.xml';
        $file = new \SplFileInfo($filename);

        $mock = $this->getMock(FileReport::class, ['reportProblem'], [$file]);
        $mock->expects($this->exactly(3))
            ->method('reportProblem');

        /** @var FileReport $mock */
        $return = $validator->validateFile($mock);
        $this->assertFalse($return);
    }

    public function testReportExceptionTextIfNoErrorsAvailable()
    {
        $validator = new LintValidation();

        $file = new \SplFileInfo('does_not_exist.xml');

        $mock = $this->getMock(FileReport::class, ['reportProblem'], [$file]);
        $mock->expects($this->exactly(1))
            ->method('reportProblem');

        /** @var FileReport $mock */
        $return = $validator->validateFile($mock);
        $this->assertFalse($return);
    }
}
