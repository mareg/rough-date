<?php

/*
 * This file is part of RoughDate library.
 *
 * (c) Marek Matulka <marek@matulka.net>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace RoughDate;

use RoughDate\Exception\UnrecognizedDateFormatException;

final class RoughDate
{
    const DEFAULT_DATE_FORMAT = 'Y-m-d';

    /**
     * @var string
     */
    private $date;

    /**
     * @param string $dateString
     */
    private function __construct($dateString)
    {
        $this->date = $dateString;
    }

    /**
     * @param string $string
     *
     * @return RoughDate
     */
    public static function fromString($string)
    {
        return new RoughDate(self::normalizeDateFromString($string));
    }

    /**
     * @param \DateTimeInterface $dateTime
     *
     * @return RoughDate
     */
    public static function fromDateTime(\DateTimeInterface $dateTime)
    {
        return new RoughDate($dateTime->format(self::DEFAULT_DATE_FORMAT));
    }

    /**
     * @return string
     */
    public function __toString()
    {
        return $this->format();
    }

    /**
     * @param string $format
     *
     * @return
     */
    public function format($format = self::DEFAULT_DATE_FORMAT)
    {
        if ($format != self::DEFAULT_DATE_FORMAT) {
            return $this->formatDateToString($format);
        }

        return $this->date;
    }

    /**
     * @param string $format
     *
     * @return string
     */
    private function formatDateToString($format)
    {
        $dateParts = explode('-', $this->date);

        if ('00' === $dateParts[1] && '00' === $dateParts[2]) {
            $date = \DateTime::createFromFormat('Y', $dateParts[0]);
            $format = preg_replace('/[^L|^Y|^y|^ |^\.|^\,]/', '', $format);
        } elseif ('00' === $dateParts[2]) {
            $date = \DateTime::createFromFormat('Y-m', join('-', array_slice($dateParts, 0, 2)));
            $format = preg_replace('/[^F|^m|^M|^n|^t|^L|^Y|^y|^ |^\.|^\,]/', '', $format);
        } else {
            $date = \DateTime::createFromFormat(self::DEFAULT_DATE_FORMAT, $this->date);
        }

        return $date->format($format);
    }

    /**
     * @param string $input
     *
     * @throws \Exception
     *
     * @return string
     */
    private static function normalizeDateFromString($input)
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

        throw new UnrecognizedDateFormatException($input);
    }
}
