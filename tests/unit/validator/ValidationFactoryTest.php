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

use sclable\xmlLint\validator\ValidationFactory;
use sclable\xmlLint\validator\ValidationInterface;

/**
 * Class ValidationFactoryTest.
 *
 *
 * @author Michael Rutz <michael.rutz@sclable.com>
 */
class ValidationFactoryTest extends \PHPUnit_Framework_TestCase
{
    public function testDefaultCollection()
    {
        $collection = ValidationFactory::createDefaultCollection();
        $this->assertInstanceOf(ValidationInterface::class, $collection);
    }
}
