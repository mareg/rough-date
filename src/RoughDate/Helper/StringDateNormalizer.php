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
    const UNKNOWN = '00';

    /**
     * @param string $input
     *
     * @throws UnrecognizedDateFormat
     *
     * @return string
     */
    public function normalize(string $input): string
    {
        $parts = $this->extractParts($input);

        if (null === $parts || !$this->isValidDate($parts)) {
            throw new UnrecognizedDateFormat($input);
        }

        return implode('-', $parts);
    }

    /**
     * @param string $input
     *
     * @return string[]|null
     */
    private function extractParts(string $input): ?array
    {
        if (preg_match('/^(\d{4})$/', $input, $matches)) {
            return [$matches[1], self::UNKNOWN, self::UNKNOWN];
        }

        if (preg_match('/^(\d{4})[-\/.](\d{2})$/', $input, $matches)) {
            return [$matches[1], $matches[2], self::UNKNOWN];
        }

        if (preg_match('/^(\d{4})([-\/.])(\d{2})\2(\d{2})$/', $input, $matches)) {
            return [$matches[1], $matches[3], $matches[4]];
        }

        if (preg_match('/^(\d{1,2})\.? ([a-zA-Z]{3,}) (\d{4})$/', $input, $matches)) {
            return $this->partsFromMonthName($matches[3], $matches[2], $matches[1]);
        }

        if (preg_match('/^([a-zA-Z]{3,}) (\d{1,2}), (\d{4})$/', $input, $matches)) {
            return $this->partsFromMonthName($matches[3], $matches[1], $matches[2]);
        }

        if (preg_match('/^([A-Z][a-z]*) (\d{4})$/', $input, $matches)) {
            return $this->partsFromMonthName($matches[2], $matches[1], self::UNKNOWN);
        }

        return null;
    }

    /**
     * @param string $year
     * @param string $monthName
     * @param string $day
     *
     * @return string[]|null
     */
    private function partsFromMonthName(string $year, string $monthName, string $day): ?array
    {
        $month = $this->monthNumberFromName($monthName);

        if (null === $month) {
            return null;
        }

        return [$year, $month, sprintf('%02d', $day)];
    }

    /**
     * @param string $monthName
     *
     * @return string|null
     */
    private function monthNumberFromName(string $monthName): ?string
    {
        $date = \DateTimeImmutable::createFromFormat('!j M Y', '1 ' . $monthName . ' 1993');
        $errors = \DateTimeImmutable::getLastErrors();

        if (false === $date || (is_array($errors) && ($errors['error_count'] > 0 || $errors['warning_count'] > 0))) {
            return null;
        }

        return $date->format('m');
    }

    /**
     * @param string[] $parts
     *
     * @return bool
     */
    private function isValidDate(array $parts): bool
    {
        list($year, $month, $day) = $parts;

        if (self::UNKNOWN === $month) {
            return self::UNKNOWN === $day;
        }

        if ((int) $month < 1 || (int) $month > 12) {
            return false;
        }

        if (self::UNKNOWN === $day) {
            return true;
        }

        return checkdate((int) $month, (int) $day, (int) $year);
    }
}
