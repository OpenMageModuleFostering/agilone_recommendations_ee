<?php
/**
 * Abstract Model
 * 
 * @category    AgilOne
 * @package     AgilOne_Recommendations
 * @copyright   Copyright (c) 2014 AgilOne (http://www.agilone.com/)
 * @author      Richard Loerzel (rloerzel@lyonscg.com)
 * 
 * https://agilone.zendesk.com/hc/en-us/articles/201534597-OpenAPI-Recommendations
 */

abstract class AgilOne_Recommendations_Model_Abstract extends Mage_Core_Model_Abstract
{
    /**
     * @var string $_storeId
     * 
     * Id of current active store
     */
    protected $_storeId = null;
    
    /**
     * @var Mage_Core_Model_Store $_store
     * 
     * Current active store object
     */
    protected $_store = null;
    
    /**
     * @var boolean $_isEnabled 
     * 
     * Is module enabled?
     */
    protected $_isEnabled = null;
    
    /**
     * @var Mage_Checkout_Model_Session $_checkoutSession
     * 
     * Checkout session singleton object
     */
    protected $_checkoutSession = null;
    
    /**
     * @var Mage_Customer_Model_Session $_customerSession
     * 
     * Customer session singleton object
     */
    protected $_customerSession = null;
    
    /**
     * @var string  $_sourceId
     * 
     * The SourceID is an ID specific to your data sources. You may have several of them if 
     * AgilOne manage different customer or product databases for you. The {sourceid} parameter
     * makes sure that the {customerkey} or {productkey} you're supplying is unique.
     */
    protected $_sourceId = null;
    
    /**
     * @var string $_tenantId
     * 
     * The TenantID is an ID specific to your workspace. If on app.agilone.com, you only have 
     * access to one workspace (the most common case) the tenant value is optional in API calls.
     */
    protected $_tenantId = null;
    
    /**
     * @var string $_webtagId
     *
     * ID provided with AgilOne WebTag account
     */
    protected $_webtagId = null;
    
    /**
     * @var string $_brandAttribute
     *
     * Magento product attribute to represent the AgilOne 'brand' attribute in API calls
     */
    protected $_brandAttribute = null;
    
    /**
     * @var string $_logLevel
     * 
     * Debug Logging Level
     * 
     * 0: None
     * 1: Errors Only
     * 2: All Traffic
     */
    protected $_logLevel = null;
    
    /**
     * @var string $_baseUrl
     * 
     * Base AgilOne API URL (i.e. "api.agilone.com")
     */
    protected $_baseUrl = null;
    
    /**
     * @var string $_handle
     * 
     * Layout Handle Identifier (i.e. "catalog_product_view")
     */
    protected $_handle = null;
    
    /**
     * @var string $_action
     *
     * Controller action
     */
    protected $_action = null;
    
    /**
     * @var string $_userId
     * 
     * Logged-in customer user id
     */
    protected $_userId = null;
    
    /**
     * @var string $_email
     *
     * Logged-in customer user email address
     */
    protected $_email = null;
    
    /**
     * Constructor. Set variables.
     */
    protected function _construct()
    {
        parent::_construct();
        
        try {
            $this->_storeId = Mage::app()->getStore()->getId();
            
            $this->_store = $store = Mage::app()->getStore();
            
            $this->_checkoutSession = Mage::getSingleton('checkout/session');
            
            $this->_customerSession = Mage::getSingleton('customer/session');
            
            $this->_isEnabled = Mage::getStoreConfig('agilone/general/enabled',$this->_storeId);
            
            $this->_sourceId = Mage::getStoreConfig('agilone/general/source_id',$this->_storeId);
            
            $this->_tenantId = Mage::getStoreConfig('agilone/general/tenant_id',$this->_storeId);
            
            $this->_webtagId = Mage::getStoreConfig('agilone/general/webtag_id',$this->_storeId);
            
            $this->_brandAttribute = Mage::getStoreConfig('agilone/general/brand_attribute',$this->_storeId);
            
            $this->_logLevel = Mage::getStoreConfig('agilone/general/log_level',$this->_storeId);
            
            if (Mage::getStoreConfig('agilone/general/force_https',$this->_storeId)) {
                $protocol = 'https';
            } else {
                $protocol = 'http';
            }
            
            $this->_baseUrl = $protocol . '://' . Mage::getStoreConfig('agilone/general/base_url',$this->_storeId);
            
            $req  = Mage::app()->getRequest();
            
            $this->_handle = $req->getRequestedRouteName() . '_' . $req->getRequestedControllerName() . '_' . $req->getRequestedActionName();
            
            $this->_action = $req->getRequestedActionName();
            
            if($this->_customerSession->isLoggedIn()){
                $customer = $this->_customerSession->getCustomer();
                $this->_userId = $customer->getId();
                $this->_email = $customer->getEmail();
            } else {
                $this->_userId = null;
                $this->_email = $this->_checkoutSession->getQuote()->getCustomerEmail();
            }
            
        } catch (Exception $e) {
            Mage::logException($e);
        }
    }
    
    /**
     * Is module enabled?
     * 
     * @return boolean
     */
    public function isEnabled()
    {
        return (boolean) $this->_isEnabled;
    }
    
    /**
     * Get A1 Api Key
     * 
     *@return string
     */
    protected function _getApiKey()
    {
        return $this->_apiKey;
    }
    
    /**
     * Get A1 Api Secret Key
     * 
     *@return string
     */
    protected function _getApiSecret()
    {
        return $this->_apiSecret;
    }
    
    /**
     * Get layout handle for current page
     * 
     * @return string
     */
    public function getHandle()
    {
        return $this->_handle;
    }
    
    /**
     * Get Website's WebTagId
     * 
     *@return string|boolean
     */
    public function getWebTagId()
    {
        return $this->_webtagId;
    }
    
    /**
     * Get Logged-In User's Magento ID
     * 
     * @return string
     */
    public function getUserId()
    {
        return $this->_userId;
    }
     
    /**
     * Get Logged-In User's Email Address
     * 
     * @return string|boolean
     */
    public function getEmail()
    {
        return $this->_email;
    }
    
    /**
     * Get product viewed
     * 
     * @return string|boolean
     */
    public function getProduct()
    {
        try {
            if ($this->_handle === 'catalog_product_view') {
                if ($productId = Mage::registry('current_product')->getId()) {
                    return Mage::getModel('catalog/product')->load($productId);
                }
            }
        } catch (Exception $e) {
            Mage::logException($e);
        }
         
        return false;
    }
    
    /**
     * Get cache lifetime for P2P data
     * 
     * @return string
     */
    protected function _getCacheP2P()
    {
        return $this->_cacheP2P;
    }
    
    /**
     * Get cache lifetime for U2P data
     * 
     * @return string
     */
    protected function _getCacheU2P()
    {
        return $this->_cacheU2P;
    }
    
    /**
     * Log debug information and errors
     * 
     * @param string $message
     * @param number $level
     */
    
    public function log($message = '',$level = 7)
    {
        switch ($this->_logLevel) {
            case AgilOne_Recommendations_Helper_Data::LOG_LEVEL_ALL:
            Mage::log($message,$level,'agilone_recommendations.log');
            break;
            
            case AgilOne_Recommendations_Helper_Data::LOG_LEVEL_ERRORS:
            case $level > 5:
            Mage::log($message,$level,'agilone_recommendations.log');
            
            break;
            default:
            break;
        }
    }
}