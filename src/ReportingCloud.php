<?php

/**
 * ReportingCloud PHP Wrapper
 *
 * Official wrapper (authored by Text Control GmbH, publisher of ReportingCloud) to access ReportingCloud in PHP.
 *
 * @link      http://www.reporting.cloud to learn more about ReportingCloud
 * @link      https://github.com/TextControl/txtextcontrol-reportingcloud-php for the canonical source repository
 * @license   https://raw.githubusercontent.com/TextControl/txtextcontrol-reportingcloud-php/master/LICENSE.md
 * @copyright © 2016 Text Control GmbH
 */
namespace TxTextControl\ReportingCloud;

use GuzzleHttp\Psr7\Response;
use GuzzleHttp\RequestOptions;

use TxTextControl\ReportingCloud\Exception\InvalidArgumentException;

use TxTextControl\ReportingCloud\Filter\DateTimeToTimestamp  as DateTimeToTimestampFilter;
use TxTextControl\ReportingCloud\Filter\TimestampToDateTime  as TimestampToDateTimeFilter;

use TxTextControl\ReportingCloud\PropertyMap\MergeSettings   as MergeSettingsPropertyMap;
use TxTextControl\ReportingCloud\PropertyMap\AccountSettings as AccountSettingsPropertyMap;
use TxTextControl\ReportingCloud\PropertyMap\TemplateInfo    as TemplateInfoPropertyMap;
use TxTextControl\ReportingCloud\PropertyMap\TemplateList    as TemplateListPropertyMap;

use TxTextControl\ReportingCloud\Validator\StaticValidator;

/**
 * ReportingCloud
 *
 * @package TxTextControl\ReportingCloud
 * @author  Jonathan Maron (@JonathanMaron)
 */
class ReportingCloud extends AbstractReportingCloud
{

    /**
     * GET methods
     * =================================================================================================================
     */

    /**
     * Return an array of merge blocks and merge fields in a template file in template storage.
     *
     * @param string  $templateName Template name
     *
     * @throws InvalidArgumentException
     *
     * @return array|null
     */
    public function getTemplateInfo($templateName)
    {
        $ret = null;

        $propertyMap = new TemplateInfoPropertyMap();

        StaticValidator::execute($templateName, 'TemplateName');

        $query = [
            'templateName' => $templateName,
        ];

        $records = $this->get('/templates/info', $query);

        if (is_array($records) && count($records) > 0) {
            $ret = $this->normalizeArrayKeys($records, $propertyMap);
        }

        return $ret;
    }

    /**
     * Return an array of binary data.
     * Each record in the array is the binary data of a thumbnail image
     *
     * @param string  $templateName Template name
     * @param integer $zoomFactor   Zoom factor
     * @param integer $fromPage     From page
     * @param integer $toPage       To page
     * @param string  $imageFormat  Image format
     *
     * @throws InvalidArgumentException
     *
     * @return array|null
     */
    public function getTemplateThumbnails($templateName, $zoomFactor, $fromPage, $toPage, $imageFormat)
    {
        $ret = null;

        StaticValidator::execute($templateName, 'TemplateName');
        StaticValidator::execute($zoomFactor  , 'ZoomFactor');
        StaticValidator::execute($fromPage    , 'Page');
        StaticValidator::execute($toPage      , 'Page');
        StaticValidator::execute($imageFormat , 'ImageFormat');

        $query = [
            'templateName' => $templateName,
            'zoomFactor'   => $zoomFactor,
            'fromPage'     => $fromPage,
            'toPage'       => $toPage,
            'imageFormat'  => $imageFormat,
        ];

        $records = $this->get('/templates/thumbnails', $query);

        if (is_array($records) && count($records) > 0) {
            $ret = [];
            foreach ($records as $index => $data) {
                $ret[$index] = base64_decode($data);
            }
        }

        return $ret;
    }

    /**
     * Return the number of templates in template storage
     *
     * @return integer|null
     */
    public function getTemplateCount()
    {
        return $this->get('/templates/count');
    }

    /**
     * Return an array properties for the templates in template storage
     *
     * @return array|null
     */
    public function getTemplateList()
    {
        $ret = null;

        $propertyMap = new TemplateListPropertyMap();
        $filter      = new DateTimeToTimestampFilter();

        $records = $this->get('/templates/list');

        if (is_array($records) && count($records) > 0) {
            $ret = $this->normalizeArrayKeys($records, $propertyMap);
            foreach ($ret as $index => $record) {
                $key = 'modified';
                if (isset($record[$key])) {
                    $ret[$index][$key] = $filter->filter($record[$key]);
                }
            }
        }

        return $ret;
    }

    /**
     * Return the number of pages in a template in template storage
     *
     * @param string $templateName Template name
     *
     * @throws InvalidArgumentException
     *
     * @return bool
     */
    public function getTemplatePageCount($templateName)
    {
        StaticValidator::execute($templateName, 'TemplateName');

        $query = [
            'templateName' => $templateName,
        ];

        return (integer) $this->get('/templates/pagecount', $query);
    }

    /**
     * Return true, if the template exists in template storage
     *
     * @param string $templateName Template name
     *
     * @throws InvalidArgumentException
     *
     * @return bool
     */
    public function templateExists($templateName)
    {
        StaticValidator::execute($templateName, 'TemplateName');

        $query = [
            'templateName' => $templateName,
        ];

        return (boolean) $this->get('/templates/exists', $query);
    }

    /**
     * Return an array properties for the ReportingCloud account
     *
     * @return array|null
     */
    public function getAccountSettings()
    {
        $ret = null;

        $propertyMap = new AccountSettingsPropertyMap();
        $filter      = new DateTimeToTimestampFilter();

        $records = $this->get('/account/settings');

        if (is_array($records) && count($records) > 0) {
            $ret = $this->normalizeArrayKeys($records, $propertyMap);
            foreach ($ret as $index => $record) {
                $key = 'valid_until';
                if (isset($record[$key])) {
                    $ret[$index][$key] = $filter->filter($record[$key]);
                }
            }
        }

        return $ret;
    }

    /**
     * Download the binary data of a template from template storage
     *
     * @param string $templateName Template name
     *
     * @throws InvalidArgumentException
     *
     * @return null|resource
     */
    public function downloadTemplate($templateName)
    {
        $ret = null;

        StaticValidator::execute($templateName, 'TemplateName');

        $query = [
            'templateName' => $templateName,
        ];

        $data = $this->get('/templates/download', $query);

        if (null !== $data) {
            $ret = base64_decode($data);
        }

        return $ret;
    }

    /**
     * Execute a GET request via REST client
     *
     * @param string $uri   URI
     * @param array  $query Query
     *
     * @return mixed|null
     */
    protected function get($uri, $query = [])
    {
        $ret = null;

        $options = [
            RequestOptions::QUERY => $query,
        ];

        $response = $this->request('GET', $this->uri($uri), $options);

        if ($response instanceof Response) {
            if (200 === $response->getStatusCode()) {
                $body = (string) $response->getBody();
                $ret  = json_decode($body, true);
            }
        }

        return $ret;
    }


    /**
     * POST methods
     * =================================================================================================================
     */

    /**
     * Upload a template to template storage
     *
     * @param string $templateFilename Template name
     *
     * @throws InvalidArgumentException
     *
     * @return bool
     */
    public function uploadTemplate($templateFilename)
    {
        $ret = false;

        StaticValidator::execute($templateFilename, 'TemplateExtension');
        StaticValidator::execute($templateFilename, 'FileExists');

        $templateFilename = realpath($templateFilename);
        $templateName     = basename($templateFilename);

        $headers = [
            'Content-Type' => 'application/json',
        ];

        $query = [
            'templateName' => $templateName,
        ];

        $body = file_get_contents($templateFilename);
        $body = base64_encode($body);
        $body = json_encode($body);

        $options = [
            RequestOptions::HEADERS => $headers,
            RequestOptions::QUERY   => $query,
            RequestOptions::BODY    => $body,
        ];

        $response = $this->request('POST', $this->uri('/templates/upload'), $options);

        if ($response instanceof Response) {
            if (201 === $response->getStatusCode()) {
                $ret = true;
            }
        }

        return $ret;
    }

    /**
     * Convert a document on the local file system to a different format
     *
     * @param string $documentFilename Document filename
     * @param string $returnFormat     Return format
     *
     * @throws InvalidArgumentException
     *
     * @return null|resource
     */
    public function convertDocument($documentFilename, $returnFormat)
    {
        $ret = null;

        StaticValidator::execute($documentFilename, 'DocumentExtension');
        StaticValidator::execute($documentFilename, 'FileExists');
        StaticValidator::execute($returnFormat    , 'ReturnFormat');

        $headers = [
            'Content-Type' => 'application/json',
        ];

        $query = [
            'returnFormat' => $returnFormat,
        ];

        $documentFilename = realpath($documentFilename);

        $body = file_get_contents($documentFilename);
        $body = base64_encode($body);
        $body = json_encode($body);

        $options = [
            RequestOptions::HEADERS => $headers,
            RequestOptions::QUERY   => $query,
            RequestOptions::BODY    => $body,
        ];

        $response = $this->request('POST', $this->uri('/document/convert'), $options);

        if ($response instanceof Response) {
            if (200 === $response->getStatusCode()) {
                $body = (string) $response->getBody();
                $ret  = base64_decode($body);
            }
        }

        return $ret;
    }

    /**
     * Merge data into a template and return an array of binary data.
     * Each record in the array is the binary data of one document
     *
     * @param array   $mergeData         Array of merge data
     * @param string  $returnFormat      Return format
     * @param string  $templateName      Template name
     * @param string  $templateFilename  Template filename on local file system
     * @param boolean $append            Append flag
     * @param array   $mergeSettings     Array of merge settings
     *
     * @throws InvalidArgumentException
     *
     * @return null|string
     */
    public function mergeDocument($mergeData, $returnFormat, $templateName = null, $templateFilename = null,
                                    $append = null, $mergeSettings = [])
    {
        $ret = null;

        StaticValidator::execute($mergeData   , 'TypeArray');
        StaticValidator::execute($returnFormat, 'ReturnFormat');

        if (null !== $templateName) {
            StaticValidator::execute($templateName, 'TemplateName');
        }

        if (null !== $templateFilename) {
            StaticValidator::execute($templateFilename, 'TemplateExtension');
            StaticValidator::execute($templateFilename, 'FileExists');
            $templateFilename = realpath($templateFilename);
        }

        // This boolean value MUST be passed as a string to prevent Guzzle converting the
        // query parameter to ?append=0 or ?append=1, which the backend does not recognize.
        // The backend only recognizes query parameter ?append=true and ?append=false.
        if (null !== $append) {
            StaticValidator::execute($append, 'TypeBoolean');
            if (true === $append) {
                $append = 'true';
            } else {
                $append = 'false';
            }
        }

        StaticValidator::execute($mergeSettings, 'TypeArray');

        $headers = [
            'Content-Type' => 'application/json',
        ];

        $query = [
            'returnFormat' => $returnFormat,
            'append'       => $append,
        ];

        if (null !== $templateName) {
            $query['templateName'] = $templateName;
        }

        $mergeSettingsRc = null;

        if (count($mergeSettings) > 0) {

            $filter = new TimestampToDateTimeFilter();

            $mergeSettingsRc = []; // 'Rc' - this array is passed to ReportingCloud

            $propertyMap = new MergeSettingsPropertyMap();

            foreach ($propertyMap->getMap() as $property => $key) {
                if (isset($mergeSettings[$key])) {
                    $value = $mergeSettings[$key];
                    if ('remove_' == substr($key, 0, 7)) {
                        StaticValidator::execute($value, 'TypeBoolean');
                    }
                    if ('_date' == substr($key, -5)) {
                        StaticValidator::execute($value, 'Timestamp');
                        $value = $filter->filter($value);
                    }
                    $mergeSettingsRc[$property] = $value;
                }
            }
        }
        
        unset($mergeSettings);

        $mergeBody = [
            'mergeData' => $mergeData,
        ];

        if (null !== $templateFilename) {
            $template = file_get_contents($templateFilename);
            $template = base64_encode($template);
            $mergeBody['template'] = $template;
        }

        if (null !== $mergeSettingsRc) {
            $mergeBody['mergeSettings'] = $mergeSettingsRc;
        }

        $body = json_encode($mergeBody);

        $options = [
            RequestOptions::HEADERS => $headers,
            RequestOptions::QUERY   => $query,
            RequestOptions::BODY    => $body,
        ];

        $response = $this->request('POST', $this->uri('/document/merge'), $options);

        if ($response instanceof Response) {
            if (200 === $response->getStatusCode()) {
                $body = (string) $response->getBody();
                $body = json_decode($body);
                if (is_array($body) && count($body) > 0) {
                    $ret = [];
                    foreach ($body as $record) {
                        array_push($ret, base64_decode($record));
                    }
                }
            }
        }

        return $ret;
    }

    /**
     * Perform find and replace in template and return an array of binary data.
     *
     * @param array   $findAndReplaceData  Array of findAndReplaceData
     * @param string  $returnFormat        Return format
     * @param string  $templateName        Template name
     * @param string  $templateFilename    Template filename on local file system
     * @param array   $mergeSettings       Array of merge settings
     *
     * @throws InvalidArgumentException
     *
     * @return null|string
     */
    public function findAndReplace($findAndReplaceData, $returnFormat, $templateName = null, $templateFilename = null,
                                   $mergeSettings = [])
    {
        $ret = null;

        StaticValidator::execute($findAndReplaceData   , 'TypeArray');
        StaticValidator::execute($returnFormat, 'ReturnFormat');

        if (null !== $templateName) {
            StaticValidator::execute($templateName, 'TemplateName');
        }

        if (null !== $templateFilename) {
            StaticValidator::execute($templateFilename, 'TemplateExtension');
            StaticValidator::execute($templateFilename, 'FileExists');
            $templateFilename = realpath($templateFilename);
        }

        StaticValidator::execute($mergeSettings, 'TypeArray');

        $headers = [
            'Content-Type' => 'application/json',
        ];

        $query = [
            'returnFormat' => $returnFormat,
        ];

        if (null !== $templateName) {
            $query['templateName'] = $templateName;
        }

        $mergeSettingsRc = null;

        if (count($mergeSettings) > 0) {

            $filter = new TimestampToDateTimeFilter();

            $mergeSettingsRc = []; // 'Rc' - this array is passed to ReportingCloud

            $propertyMap = new MergeSettingsPropertyMap();

            foreach ($propertyMap->getMap() as $property => $key) {
                if (isset($mergeSettings[$key])) {
                    $value = $mergeSettings[$key];
                    if ('remove_' == substr($key, 0, 7)) {
                        StaticValidator::execute($value, 'TypeBoolean');
                    }
                    if ('_date' == substr($key, -5)) {
                        StaticValidator::execute($value, 'Timestamp');
                        $value = $filter->filter($value);
                    }
                    $mergeSettingsRc[$property] = $value;
                }
            }
        }

        unset($mergeSettings);

        $findAndReplaceBody = [
            'findAndReplaceData' => $findAndReplaceData,
        ];

        if (null !== $templateFilename) {
            $template = file_get_contents($templateFilename);
            $template = base64_encode($template);
            $findAndReplaceBody['template'] = $template;
        }

        if (null !== $mergeSettingsRc) {
            $findAndReplaceBody['mergeSettings'] = $mergeSettingsRc;
        }

        $body = json_encode($findAndReplaceBody);

        $options = [
            RequestOptions::HEADERS => $headers,
            RequestOptions::QUERY   => $query,
            RequestOptions::BODY    => $body,
        ];

        $response = $this->request('POST', $this->uri('/document/findandreplace'), $options);

        if ($response instanceof Response) {
            if (200 === $response->getStatusCode()) {
                $body = (string) $response->getBody();
                $ret = base64_decode($body);
            }
        }

        return $ret;
    }


    /**
     * DELETE methods
     * =================================================================================================================
     */

    /**
     * Delete a template in template storage
     *
     * @param string $templateName Template name
     *
     * @throws InvalidArgumentException
     *
     * @return bool
     */
    public function deleteTemplate($templateName)
    {
        $ret = false;

        StaticValidator::execute($templateName, 'TemplateName');

        $query = [
            'templateName' => $templateName,
        ];

        $options = [
            RequestOptions::QUERY => $query,
        ];

        $response = $this->request('DELETE', $this->uri('/templates/delete'), $options);

        if ($response instanceof Response) {
            if (204 === $response->getStatusCode()) {
                $ret = true;
            }
        }

        return $ret;
    }
}