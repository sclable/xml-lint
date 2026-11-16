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

namespace sclable\xmlLint\console\command;

use Symfony\Component\Console\Command\Command as BaseCommand;

/**
 * @internal
 */
abstract class Command extends BaseCommand
{
    /** @var array<string, string> local synopsis runtime cache */
    private array $synopsis = [];

    public function getSynopsis(bool $short = false): string
    {
        $key = $short ? 'short' : 'long';

        if (!isset($this->synopsis[$key])) {
            $this->synopsis[$key] = trim(sprintf(
                '%s %s',
                is_string($_SERVER['PHP_SELF'] ?? null) ? $_SERVER['PHP_SELF'] : $this->getName(),
                $this->getDefinition()->getSynopsis($short)
            ));
        }

        return $this->synopsis[$key];
    }
}
