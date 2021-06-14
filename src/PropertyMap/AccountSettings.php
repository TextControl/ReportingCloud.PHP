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

namespace TxTextControl\ReportingCloud\PropertyMap;

/**
 * AccountSettings property map
 *
 * @package TxTextControl\ReportingCloud
 * @author  Jonathan Maron (@JonathanMaron)
 */
class AccountSettings extends AbstractPropertyMap
{
    /**
     * Set the property map of AccountSettings
     */
    public function __construct()
    {
        $map = [
            'serialNumber'            => 'serial_number',
            'createdDocuments'        => 'created_documents',
            'uploadedTemplates'       => 'uploaded_templates',
            'maxDocuments'            => 'max_documents',
            'maxTemplates'            => 'max_templates',
            'validUntil'              => 'valid_until',
            'proofingTransactions'    => 'proofing_transactions',
            'maxProofingTransactions' => 'max_proofing_transactions',
        ];

        $this->setMap($map);
    }
}
