<?php

namespace RoughDate\Helper;

use RoughDate\RoughDate;
use RoughDate\Exception\UnrecognizedDateFormat;

class StringDateFormatter
{
    /**
     * @var string
     */
    private $date;

    private function __construct() {}

    /**
     * @param string $date
     *
     * @return StringDateFormatter
     */
    public static function fromString($date)
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
    public function format($format = RoughDate::DEFAULT_DATE_FORMAT)
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
            $date = \DateTime::createFromFormat(RoughDate::DEFAULT_DATE_FORMAT, $this->date);
        }

        return $date->format($format);
    }

    /**
     * @param string $date
     *
     * @throws UnrecognizedDateFormat
     */
    private static function validateDateFormat($date)
    {
        if (!preg_match('/^\d{4}[\-|\/]\d{2}[\-|\/]\d{2}$/', $date)) {
            throw new UnrecognizedDateFormat($date);
        }
    }
}
