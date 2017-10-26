<?php
/*******************************************************************************
 * Copyright 2009-2016 Amazon Services. All Rights Reserved.
 * Licensed under the Apache License, Version 2.0 (the "License"); 
 *
 * You may not use this file except in compliance with the License. 
 * You may obtain a copy of the License at: http://aws.amazon.com/apache2.0
 * This file is distributed on an "AS IS" BASIS, WITHOUT WARRANTIES OR 
 * CONDITIONS OF ANY KIND, either express or implied. See the License for the 
 * specific language governing permissions and limitations under the License.
 *******************************************************************************
 * PHP Version 5
 * @category Amazon
 * @package  FBA Inventory Service MWS
 * @version  2010-10-01
 * Library Version: 2014-09-30
 * Generated: Wed May 04 17:14:15 UTC 2016
 */

namespace amazon\inventory\model;

use amazon\inventory\InventoryModel;
use DOMDocument;
use DOMXPath;
use Exception;
/**
 * ListInventorySupplyResponse
 * 
 * Properties:
 * <ul>
 * 
 * <li>ListInventorySupplyResult: InventoryModel_ListInventorySupplyResult</li>
 * <li>ResponseMetadata: ResponseMetadata</li>
 * <li>ResponseHeaderMetadata: ResponseHeaderMetadata</li>
 *
 * </ul>
 */

 class ListInventorySupplyResponse extends InventoryModel {

    public function __construct($data = null)
    {
    $this->_fields = array (
    'ListInventorySupplyResult' => array('FieldValue' => null, 'FieldType' => 'InventoryModel_ListInventorySupplyResult'),
    'ResponseMetadata' => array('FieldValue' => null, 'FieldType' => 'ResponseMetadata'),
    'ResponseHeaderMetadata' => array('FieldValue' => null, 'FieldType' => 'ResponseHeaderMetadata'),
    );
    parent::__construct($data);
    }

    /**
     * Get the value of the ListInventorySupplyResult property.
     *
     * @return ListInventorySupplyResult ListInventorySupplyResult.
     */
    public function getListInventorySupplyResult()
    {
        return $this->_fields['ListInventorySupplyResult']['FieldValue'];
    }

    /**
     * Set the value of the ListInventorySupplyResult property.
     *
     * @param InventoryModel_ListInventorySupplyResult listInventorySupplyResult
     * @return this instance
     */
    public function setListInventorySupplyResult($value)
    {
        $this->_fields['ListInventorySupplyResult']['FieldValue'] = $value;
        return $this;
    }

    /**
     * Check to see if ListInventorySupplyResult is set.
     *
     * @return true if ListInventorySupplyResult is set.
     */
    public function isSetListInventorySupplyResult()
    {
                return !is_null($this->_fields['ListInventorySupplyResult']['FieldValue']);
            }

    /**
     * Set the value of ListInventorySupplyResult, return this.
     *
     * @param listInventorySupplyResult
     *             The new value to set.
     *
     * @return This instance.
     */
    public function withListInventorySupplyResult($value)
    {
        $this->setListInventorySupplyResult($value);
        return $this;
    }

    /**
     * Get the value of the ResponseMetadata property.
     *
     * @return ResponseMetadata ResponseMetadata.
     */
    public function getResponseMetadata()
    {
        return $this->_fields['ResponseMetadata']['FieldValue'];
    }

    /**
     * Set the value of the ResponseMetadata property.
     *
     * @param ResponseMetadata responseMetadata
     * @return this instance
     */
    public function setResponseMetadata($value)
    {
        $this->_fields['ResponseMetadata']['FieldValue'] = $value;
        return $this;
    }

    /**
     * Check to see if ResponseMetadata is set.
     *
     * @return true if ResponseMetadata is set.
     */
    public function isSetResponseMetadata()
    {
                return !is_null($this->_fields['ResponseMetadata']['FieldValue']);
            }

    /**
     * Set the value of ResponseMetadata, return this.
     *
     * @param responseMetadata
     *             The new value to set.
     *
     * @return This instance.
     */
    public function withResponseMetadata($value)
    {
        $this->setResponseMetadata($value);
        return $this;
    }

    /**
     * Get the value of the ResponseHeaderMetadata property.
     *
     * @return ResponseHeaderMetadata ResponseHeaderMetadata.
     */
    public function getResponseHeaderMetadata()
    {
        return $this->_fields['ResponseHeaderMetadata']['FieldValue'];
    }

    /**
     * Set the value of the ResponseHeaderMetadata property.
     *
     * @param ResponseHeaderMetadata responseHeaderMetadata
     * @return this instance
     */
    public function setResponseHeaderMetadata($value)
    {
        $this->_fields['ResponseHeaderMetadata']['FieldValue'] = $value;
        return $this;
    }

    /**
     * Check to see if ResponseHeaderMetadata is set.
     *
     * @return true if ResponseHeaderMetadata is set.
     */
    public function isSetResponseHeaderMetadata()
    {
                return !is_null($this->_fields['ResponseHeaderMetadata']['FieldValue']);
            }

    /**
     * Set the value of ResponseHeaderMetadata, return this.
     *
     * @param responseHeaderMetadata
     *             The new value to set.
     *
     * @return This instance.
     */
    public function withResponseHeaderMetadata($value)
    {
        $this->setResponseHeaderMetadata($value);
        return $this;
    }

     /**
      * Construct ListInventorySupplyResponse from XML string
      *
      * @param $xml
      *        XML string to construct from
      * @return ListInventorySupplyResponse
      * @throws Exception
      */
    public static function fromXML($xml)
    {
        $dom = new DOMDocument();
        $dom->loadXML($xml);
        $xpath = new DOMXPath($dom);
        $response = $xpath->query("//*[local-name()='ListInventorySupplyResponse']");
        if ($response->length == 1) {
            return new ListInventorySupplyResponse(($response->item(0)));
        } else {
            throw new Exception ("Unable to construct ListInventorySupplyResponse from provided XML. 
                                  Make sure that ListInventorySupplyResponse is a root element");
        }
    }
    /**
     * XML Representation for this object
     * 
     * @return string XML for this object
     */
    public function toXML() 
    {
        $xml = "";
        $xml .= "<ListInventorySupplyResponse xmlns=\"http://mws.amazonaws.com/FulfillmentInventory/2010-10-01/\">";
        $xml .= $this->_toXMLFragment();
        $xml .= "</ListInventorySupplyResponse>";
        return $xml;
    }

}