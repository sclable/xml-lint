<?php
/**
 * This file is part of the Sclable Xml Lint Package.
 *
 * @copyright (c) 2015 Sclable Business Solutions GmbH
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace sclable\xmlLint\tests\unit\data;

use PHPUnit\Framework\TestCase;
use sclable\xmlLint\data\ValidationProblem;

/**
 * Class ValidationProblemTest.
 *
 *
 * @author Michael Rutz <michael.rutz@sclable.com>
 */
class ValidationProblemTest extends TestCase
{
    public function testCreateProblem()
    {
        $msg = 'my message';
        $problem = ValidationProblem::create($msg);
        $this->assertEquals($msg, $problem->getMessage());
    }
}
