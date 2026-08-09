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

use Mareg\RoughDate\RoughDate;
use Mareg\RoughDate\Exception\UnrecognizedDateFormat;

final class StringDateFormatter
{
    const UNKNOWN = '00';
    const YEAR_PRECISION_CHARS = 'LYy';
    const MONTH_PRECISION_CHARS = 'FmMntLYy';

    /**
     * @var string
     */
    private $date;

    private function __construct() {}

    /**
     * @param string $date
     *
     * @throws UnrecognizedDateFormat
     *
     * @return StringDateFormatter
     */
    public static function fromString(string $date): StringDateFormatter
    {
        self::validateDateFormat($date);

        $stringDateFormatter = new StringDateFormatter();
        $stringDateFormatter->date = $date;

        return $stringDateFormatter;
    }

    /**
     * @param string $format
     *
     * @return string
     */
    public function format(string $format = RoughDate::DEFAULT_DATE_FORMAT): string
    {
        if ($format != RoughDate::DEFAULT_DATE_FORMAT) {
            return $this->formatDateToString($format);
        }

        return $this->date;
    }

    /**
     * @param string $format
     *
     * @return string
     */
    private function formatDateToString(string $format): string
    {
        list($year, $month, $day) = explode('-', $this->date);

        if (self::UNKNOWN === $month) {
            $date = $this->createDate($year, '01', '01');
            $format = $this->stripUnsupported($format, self::YEAR_PRECISION_CHARS);
        } elseif (self::UNKNOWN === $day) {
            $date = $this->createDate($year, $month, '01');
            $format = $this->stripUnsupported($format, self::MONTH_PRECISION_CHARS);
        } else {
            $date = $this->createDate($year, $month, $day);
        }

        return $date->format($format);
    }

    /**
     * @param string $year
     * @param string $month
     * @param string $day
     *
     * @return \DateTime
     */
    private function createDate(string $year, string $month, string $day): \DateTime
    {
        return \DateTime::createFromFormat('!Y-m-d', $year . '-' . $month . '-' . $day);
    }

    /**
     * @param string $format
     * @param string $allowed
     *
     * @return string
     */
    private function stripUnsupported(string $format, string $allowed): string
    {
        $result = '';
        $length = strlen($format);

        for ($position = 0; $position < $length; $position++) {
            $character = $format[$position];

            if ('\\' === $character) {
                $result .= $character;

                if (++$position < $length) {
                    $result .= $format[$position];
                }

                continue;
            }

            if (ctype_alpha($character) && false === strpos($allowed, $character)) {
                continue;
            }

            $result .= $character;
        }

        return $result;
    }

    /**
     * @param string $date
     *
     * @throws UnrecognizedDateFormat
     */
    private static function validateDateFormat(string $date)
    {
        if (!preg_match('/^\d{4}-\d{2}-\d{2}$/', $date)) {
            throw new UnrecognizedDateFormat($date);
        }
    }
}
