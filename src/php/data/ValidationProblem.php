<?php
/**
 * This file is part of the Sclable Xml Lint Package.
 *
 * @copyright (c) 2015 Sclable Business Solutions GmbH
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace sclable\xmlLint\data;

/**
 * Class ValidationProblem
 *
 *
 * @package php\data
 * @author Michael Rutz <michael.rutz@sclable.com>
 *
 */
class ValidationProblem
{
    /** @var string */
    private $msg = '';

    /**
     * factory method to create a validation problem
     * @param string $message
     * @return ValidationProblem
     */
    public static function create($message)
    {
        return new static($message);
    }

    /**
     * ValidationProblem constructor.
     * @param string $message
     */
    public function __construct($message)
    {
        $this->msg = $message;
    }


    /**
     * @return string
     */
    public function getMessage()
    {
        return $this->msg;
    }
}
