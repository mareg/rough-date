<?php

/*
 * This file is part of RoughDate library.
 *
 * (c) Marek Matulka <marek@matulka.net>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

declare(strict_types=1);

namespace Mareg\RoughDate\Exception;

class UnrecognizedDateFormat extends \RuntimeException
{
    /**
     * @param string $format
     */
    public function __construct(string $format)
    {
        parent::__construct("Failed to recognize date format of `{$format}`.");
    }
}
