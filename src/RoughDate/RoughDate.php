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

namespace Mareg\RoughDate;

use Mareg\RoughDate\Helper\StringDateNormalizer;
use Mareg\RoughDate\Helper\StringDateFormatter;

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
    private function __construct(string $dateString)
    {
        $this->date = $dateString;
    }

    /**
     * @param string $string
     *
     * @return RoughDate
     */
    public static function fromString(string $string): RoughDate
    {
        $noramlizer = new StringDateNormalizer();

        return new RoughDate($noramlizer->normalize($string));
    }

    /**
     * @param \DateTimeInterface $dateTime
     *
     * @return RoughDate
     */
    public static function fromDateTime(\DateTimeInterface $dateTime): RoughDate
    {
        return new RoughDate($dateTime->format(self::DEFAULT_DATE_FORMAT));
    }

    /**
     * @return string
     */
    public function __toString(): string
    {
        return $this->format();
    }

    /**
     * @param string $format
     *
     * @return string
     */
    public function format(string $format = self::DEFAULT_DATE_FORMAT): string
    {
        $formatter = StringDateFormatter::fromString($this->date);

        return $formatter->format($format);
    }
}
