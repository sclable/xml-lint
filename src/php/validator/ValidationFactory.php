<?php

/**
 * This file is part of the Sclable Xml Lint Package.
 *
 * @copyright (c) 2022 Sclable Business Solutions GmbH
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace sclable\xmlLint\validator;

use sclable\xmlLint\validator\helper\LibXmlErrorFormatter;

/**
 * Class ValidationFactory.
 */
class ValidationFactory
{
    /**
     * @return ValidationInterface
     */
    public static function createDefaultCollection()
    {
        $collection = new ValidationCollection();

        $formatter = new LibXmlErrorFormatter();

        $collection->addValidation(new LintValidation($formatter))
            ->addValidation(new XsdValidation($formatter));

        return $collection;
    }

    /**
     * @return ValidationInterface
     */
    public static function createLintOnlyValidation()
    {
        $formatter = new LibXmlErrorFormatter();

        return new LintValidation($formatter);
    }
}
