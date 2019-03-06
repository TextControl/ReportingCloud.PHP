<?php
declare(strict_types=1);

/**
 * ReportingCloud PHP SDK
 *
 * PHP SDK for ReportingCloud Web API. Authored and supported by Text Control GmbH.
 *
 * @link      https://www.reporting.cloud to learn more about ReportingCloud
 * @link      https://github.com/TextControl/txtextcontrol-reportingcloud-php for the canonical source repository
 * @license   https://raw.githubusercontent.com/TextControl/txtextcontrol-reportingcloud-php/master/LICENSE.md
 * @copyright © 2019 Text Control GmbH
 */

namespace TxTextControl\ReportingCloud\Assert;

use TxTextControl\ReportingCloud\Exception\InvalidArgumentException;

/**
 * Trait AssertTimestampTrait
 *
 * @package TxTextControl\ReportingCloud
 * @author  Jonathan Maron (@JonathanMaron)
 */
trait AssertTimestampTrait
{
    /**
     * @param int    $value
     * @param int    $min
     * @param int    $max
     * @param string $message
     */
    abstract public static function range(int $value, int $min, int $max, string $message = ''): void;

    /**
     * Minimum timestamp (EPOC)
     *
     * @var int
     */
    private static $timestampMin = 0;

    /**
     * Maximum timestamp (PHP_INT_MAX)
     *
     * @var int
     */
    private static $timestampMax = PHP_INT_MAX;

    /**
     * Check value is a valid timestamp
     *
     * @param int    $value
     * @param string $message
     *
     * @return void
     * @throws InvalidArgumentException
     */
    public static function assertTimestamp(int $value, string $message = ''): void
    {
        $format  = $message ?: 'Timestamp ("%d") must be in the range [%d..%d]';
        $message = sprintf($format, $value, self::$timestampMin, self::$timestampMax);

        self::range($value, self::$timestampMin, self::$timestampMax, $message);
    }
}
