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

/**
 * Class Assert
 *
 * @package TxTextControl\ReportingCloud
 * @author  Jonathan Maron (@JonathanMaron)
 */
class Assert extends AbstractAssert
{
    use AssertApiKeyTrait;
    use AssertArrayTrait;
    use AssertBase64DataTrait;
    use AssertBaseUriTrait;
    use AssertBooleanTrait;
    use AssertCultureTrait;
    use AssertDateTimeTrait;
    use AssertDocumentDividerTrait;
    use AssertDocumentExtensionTrait;
    use AssertDocumentThumbnailExtensionTrait;
    use AssertFilenameExistsTrait;
    use AssertImageFormatTrait;
    use AssertIntegerTrait;
    use AssertLanguageTrait;
    use AssertOneOfTrait;
    use AssertPageTrait;
    use AssertRangeTrait;
    use AssertReturnFormatTrait;
    use AssertStringTrait;
    use AssertTemplateExtensionTrait;
    use AssertTemplateFormatTrait;
    use AssertTemplateNameTrait;
    use AssertTimestampTrait;
    use AssertZoomFactorTrait;
}
