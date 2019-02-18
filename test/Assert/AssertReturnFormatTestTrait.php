<?php
declare(strict_types=1);

/**
 * ReportingCloud PHP Wrapper
 *
 * PHP wrapper for ReportingCloud Web API. Authored and supported by Text Control GmbH.
 *
 * @link      https://www.reporting.cloud to learn more about ReportingCloud
 * @link      https://github.com/TextControl/txtextcontrol-reportingcloud-php for the canonical source repository
 * @license   https://raw.githubusercontent.com/TextControl/txtextcontrol-reportingcloud-php/master/LICENSE.md
 * @copyright © 2019 Text Control GmbH
 */

namespace TxTextControlTest\ReportingCloud\Assert;

use TxTextControl\ReportingCloud\Exception\InvalidArgumentException;
use TxTextControl\ReportingCloud\Assert\Assert;

/**
 * Trait AssertReturnFormatTestTrait
 *
 * @package TxTextControlTest\ReportingCloud
 * @author  Jonathan Maron (@JonathanMaron)
 */
trait AssertReturnFormatTestTrait
{
    public function testAssertReturnFormat(): void
    {
        Assert::assertReturnFormat('DOC');
        Assert::assertReturnFormat('doc');

        Assert::assertReturnFormat('DOCX');
        Assert::assertReturnFormat('docx');

        Assert::assertReturnFormat('HTML');
        Assert::assertReturnFormat('html');

        Assert::assertReturnFormat('PDF');
        Assert::assertReturnFormat('pdf');

        Assert::assertReturnFormat('PDFA');
        Assert::assertReturnFormat('pdfa');

        Assert::assertReturnFormat('RTF');
        Assert::assertReturnFormat('rtf');

        Assert::assertReturnFormat('TX');
        Assert::assertReturnFormat('tx');

        $this->assertTrue(true);
    }

    /**
     * @expectedException InvalidArgumentException
     * @expectedExceptionMessage "xxx" contains an unsupported return format file extension
     */
    public function testAssertReturnFormatInvalid(): void
    {
        Assert::assertReturnFormat('xxx');
    }

    /**
     * @expectedException InvalidArgumentException
     * @expectedExceptionMessage Custom error message ("XXX")
     */
    public function testAssertReturnFormatInvalidWithCustomMessage(): void
    {
        Assert::assertReturnFormat('XXX', 'Custom error message ("%s")');
    }
}
