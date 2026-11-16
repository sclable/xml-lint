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
use PHPUnit\Framework\Attributes\DataProvider;
use sclable\xmlLint\validator\ValidationInterface;
use sclable\xmlLint\validator\ValidationCollection;

/**
 * Class ValidationCollectionTest.
 */
class ValidationCollectionTest extends TestCase
{
    /**
     * @param bool $return1
     * @param bool $return2
     * @param bool $expected
     */
    #[DataProvider('getMockReturnValues')]
    public function testCollectionCorrectReturnValue($return1, $return2, $expected): void
    {
        $mock1 = $this->createStub(ValidationInterface::class);
        $mock2 = $this->createStub(ValidationInterface::class);

        $mock1->method('validateFile')
            ->willReturn($return1);
        $mock2->method('validateFile')
            ->willReturn($return2);

        $collection = new ValidationCollection();
        $collection->addValidation($mock1)
            ->addValidation($mock2);

        $this->assertEquals($expected, $collection->validateFile(FileReport::create('some_file.xml')));
    }

    public static function getMockReturnValues()
    {
        return [
            [true, true, true],
            [false, false, false],
            [false, true, false],
            [true, false, false],
        ];
    }
}
