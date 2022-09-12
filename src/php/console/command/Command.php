<?php

/**
 * This file is part of the Sclable Xml Lint Package.
 *
 * @copyright (c) 2022 Sclable Business Solutions GmbH
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace sclable\xmlLint\console\command;

use Symfony\Component\Console\Command\Command as BaseCommand;

/**
 * Class Command.
 */
class Command extends BaseCommand
{
    /** @var array local synopsis runtime cache */
    private $synopsis = [];

    /**
     * {@inheritdoc}
     *
     * @SuppressWarnings(PHPMD.Superglobals)
     */
    public function getSynopsis($short = false): string
    {
        $key = $short ? 'short' : 'long';

        if (!isset($this->synopsis[$key])) {
            $this->synopsis[$key] = trim(sprintf(
                '%s %s',
                $_SERVER['PHP_SELF'],
                $this->getDefinition()->getSynopsis($short)
            ));
        }

        return $this->synopsis[$key];
    }
}
