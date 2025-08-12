<?php

/**
 * This file is part of the Sclable Xml Lint Package.
 *
 * @copyright (c) 2025 Sclable Business Solutions GmbH
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

/**
 * @param string $file
 *
 * @return bool|string
 */
function includeIfExists($file)
{
    /* @noinspection PhpIncludeInspection */
    return file_exists($file) ? include $file : false;
}

if ((!$loader = includeIfExists(__DIR__ . '/../../vendor/autoload.php'))
    && (!$loader = includeIfExists(__DIR__ . '/../../../../autoload.php'))) {
    echo 'You must set up the project dependencies, run the following commands:' . PHP_EOL .
        'curl -sS https://getcomposer.org/installer | php' . PHP_EOL .
        'php composer.phar install' . PHP_EOL;
    exit(1);
}

return $loader;
