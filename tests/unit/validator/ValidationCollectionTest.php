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
    #[DataProvider('getMockReturnValues')]
    public function testCollectionCorrectReturnValue(bool $return1, bool $return2, bool $expected): void
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

        $this->assertSame($expected, $collection->validateFile(FileReport::create('some_file.xml')));
    }

    /**
     * @return list<array{bool, bool, bool}>
     */
    public static function getMockReturnValues(): array
    {
        return [
            [true, true, true],
            [false, false, false],
            [false, true, false],
            [true, false, false],
        ];
    }
}
