<?php

/*
 * This file is part of RoughDate library.
 *
 * (c) Marek Matulka <marek@matulka.net>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace RoughDate\Exception;

class UnrecognizedDateFormat extends \Exception
{
    /**
     * @param string $format
     */
    public function __construct($format)
    {
        parent::__construct("Failed to recognize date format of `{$format}`.", 500);
    }
}
