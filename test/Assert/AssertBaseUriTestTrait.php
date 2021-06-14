<?php
declare(strict_types=1);

/**
 * ReportingCloud PHP SDK
 *
 * PHP SDK for ReportingCloud Web API. Authored and supported by Text Control GmbH.
 *
 * @link      https://www.reporting.cloud to learn more about ReportingCloud
 * @link      https://git.io/Jejj2 for the canonical source repository
 * @license   https://git.io/Jejjr
 * @copyright © 2021 Text Control GmbH
 */

namespace TxTextControlTest\ReportingCloud\Assert;

use TxTextControl\ReportingCloud\Assert\Assert;
use TxTextControl\ReportingCloud\Exception\InvalidArgumentException;

/**
 * Trait AssertBaseUriTestTrait
 *
 * @package TxTextControlTest\ReportingCloud
 * @author  Jonathan Maron (@JonathanMaron)
 */
trait AssertBaseUriTestTrait
{
    // <editor-fold desc="Abstract methods">

    /**
     * @param mixed  $condition
     * @param string $message
     */
    abstract public static function assertTrue($condition, string $message = ''): void;

    /**
     * @param string $exception
     */
    abstract public function expectException(string $exception): void;

    /**
     * @param string $message
     */
    abstract public function expectExceptionMessage(string $message): void;

    // </editor-fold>

    public function testAssertBaseUri(): void
    {
        Assert::assertBaseUri('https://phpunit-api.reporting.cloud');

        $this->assertTrue(true);
    }

    public function testAssertBaseUriWithInvalidBaseUri(): void
    {
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('Expected base URI to end in "api.reporting.cloud". '
                                      . 'Got "https://api.example.com"');

        Assert::assertBaseUri('https://api.example.com');

        $this->assertTrue(true);
    }

    public function testAssertBaseUriInvalidBaseUriKnownHost(): void
    {
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('Expected base URI to end in "api.reporting.cloud". '
                                      . 'Got "https://api.reporting.cloud.de"');

        Assert::assertBaseUri('https://api.reporting.cloud.de');

        $this->assertTrue(true);
    }
}
