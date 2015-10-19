<?php
/**
 * This file is part of the Sclable Xml Lint Package.
 *
 * @copyright (c) 2015 Sclable Business Solutions GmbH
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace sclable\xmlLint\output;

/**
 * Class ErrorFormatter
 *
 * format a list of errors.
 *
 * @package sclable\xmlLint\output
 * @author Michael Rutz <michael.rutz@sclable.com>
 *
 */
class ErrorFormatter
{
    /** @var \LibXMLError[] a list of LibXMLErrors */
    protected $errors;

    /** @var string prefix every line with */
    protected $prefix;

    /**
     * ErrorFormatter constructor.
     * @param \LibXMLError[] $errors
     * @param string $prefix a prefix for every printed line
     */
    public function __construct(array $errors, $prefix = '')
    {
        $this->errors = $errors;
        $this->prefix = $prefix;
    }

    /**
     * convert the list of errors to a formatted string.
     * @return string
     */
    public function getErrorsAsString()
    {
        $ret = [];
        foreach ($this->errors as $error) {
            $ret[] = $this->formatError($error);
        }

        return implode(PHP_EOL, array_unique($ret)) . PHP_EOL;
    }

    /**
     * format the error
     * @param \LibXMLError $error
     * @return string
     */
    protected function formatError(\LibXMLError $error)
    {
        $template = $this->prefix . 'Line %s: [%s] %s';
        $message = trim($error->message);

        return sprintf($template, $error->line, $error->code, $message);
    }

    /**
     * get the current prefix
     * @return string
     */
    public function getPrefix()
    {
        return $this->prefix;
    }

    /**
     * set a prefix to use for every line to print.
     * @param string $prefix the prefix string
     * @return $this
     */
    public function setPrefix($prefix)
    {
        $this->prefix = $prefix;
        return $this;
    }
}
