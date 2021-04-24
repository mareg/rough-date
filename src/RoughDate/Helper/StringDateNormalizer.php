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

namespace Mareg\RoughDate\Helper;

use Mareg\RoughDate\Exception\UnrecognizedDateFormat;

final class StringDateNormalizer
{
    /**
     * @param string $input
     *
     * @throws UnrecognizedDateFormat
     *
     * @return string
     */
    public function normalize(string $input): string
    {
        if (preg_match('/^\d{4}[\-][0]{2}[\-][0]{2}$/', $input) || preg_match('/^\d{4}[\-]\d{2}[\-][0]{2}$/', $input)) {
            return $input;
        }

        if (preg_match('/^\d{4}[\-|\/]\d{2}[\-|\/]\d{2}$/', $input) || preg_match('/^\d{1,2}\.? [a-zA-Z]{3} \d{4}$/', $input)) {
            return (new \DateTime($input))->format('Y-m-d');
        }

        if (preg_match('/^\d{4}[\-|\/|\.]\d{2}[\-|\/|\.]\d{2}$/', $input)) {
            return str_replace('.', '-', $input);
        }

        if (preg_match('/^\d{4}[\-|\/|\.]\d{2}$/', $input)) {
            return str_replace('/', '-', str_replace('.', '-', $input)) . '-00';
        }

        if (preg_match('/^[A-Z][a-z]* \d{4}$/', $input)) {
            return (new \DateTime($input))->format('Y-m') . '-00';
        }

        if (preg_match('/^\d{4}$/', $input)) {
            return $input . '-00-00';
        }

        throw new UnrecognizedDateFormat($input);
    }
}
