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

namespace sclable\xmlLint\validator;

use sclable\xmlLint\validator\helper\LibXmlErrorFormatter;

/**
 * Class ValidationFactory.
 */
class ValidationFactory
{
    public static function createDefaultCollection(): ValidationInterface
    {
        $collection = new ValidationCollection();

        $formatter = new LibXmlErrorFormatter();

        $collection->addValidation(new LintValidation($formatter))
            ->addValidation(new XsdValidation($formatter));

        return $collection;
    }

    public static function createLintOnlyValidation(): ValidationInterface
    {
        $formatter = new LibXmlErrorFormatter();

        return new LintValidation($formatter);
    }
}
