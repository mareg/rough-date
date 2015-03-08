<?php

namespace RoughDate\Helper;

use RoughDate\Exception\UnrecognizedDateFormat;

class StringDateNormalizer
{
    /**
     * @param string $input
     *
     * @throws UnrecognizedDateFormat
     *
     * @return string
     */
    public function normalize($input)
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

        if (preg_match('/^[a-zA-Z]{3} \d{4}$/', $input)) {
            return (new \DateTime($input))->format('Y-m') . '-00';
        }

        if (preg_match('/^\d{4}$/', $input)) {
            return $input . '-00-00';
        }

        throw new UnrecognizedDateFormat($input);
    }
}
