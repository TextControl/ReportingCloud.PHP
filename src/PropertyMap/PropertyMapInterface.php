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
 * @copyright © 2020 Text Control GmbH
 */

namespace TxTextControl\ReportingCloud\PropertyMap;

/**
 * Property map interface
 *
 * @package TxTextControl\ReportingCloud
 * @author  Jonathan Maron (@JonathanMaron)
 */
interface PropertyMapInterface
{
    /**
     * Return the property map
     *
     * @return array|null
     */
    public function getMap(): ?array;

    /**
     * Set the property map
     *
     * @param array $map Assoc array of property data
     *
     * @return AbstractPropertyMap
     */
    public function setMap(array $map): AbstractPropertyMap;
}
