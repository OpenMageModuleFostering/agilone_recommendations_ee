<?php
/**
 * Recommendation Feed Model
 * 
 * @category    AgilOne
 * @package     AgilOne_Recommendations
 * @copyright   Copyright (c) 2014 AgilOne (http://www.agilone.com/)
 * @author      Richard Loerzel (rloerzel@lyonscg.com)
 * 
 * https://agilone.zendesk.com/hc/en-us/articles/201534597-OpenAPI-Recommendations
 */

class AgilOne_Recommendations_Model_Feed extends AgilOne_Recommendations_Model_Abstract
{
    /**
     * @var string $_apiKey
     *
     * SHA256 (AES) Public Key
     */
    protected $_apiKey = null;
    
    /**
     * @var string $_apiSecret
     *
     * SHA256 (AES) Secret Key
     */    
    protected $_apiSecret = null;
    
    /**
     * @var string $_limitP2P
     *
     * Number of product recommendations to return
     */
    protected $_limitP2P = null;
    
    /**
     * @var string $_cacheP2P
     *
     * Caching expiration seconds from current timestamp for product recommendations
     */
    protected $_cacheP2P = null;
    
    /**
     * @var string $_limitU2P
     *
     * Number of customer recommendations to return
     */
    protected $_limitU2P = null;
    
    /**
     * @var string $_cacheU2P
     *
     * Caching expiration seconds from current timestamp for customer recommendations
     */
    protected $_cacheU2P = null;
    
    /**
     * @var string $_defaultEmail
     *
     * Default email address to use for customers who aren't logged in.
     */
    protected $_defaultEmail = null;
    
    
    /**
     * Constructor. Set variables.
     */
    protected function _construct()
    {
        parent::_construct();
            
        try {
            $this->_apiKey = Mage::getStoreConfig('agilone/general/api_key',$this->_storeId);
            
            /**
             * Secret key encoding for query string authentication
             * https://agilone.zendesk.com/hc/en-us/articles/201534547-OpenAPI-Query-String-Authentication
             */
            $this->_apiSecret = base64_decode(mb_convert_encoding(Mage::getStoreConfig('agilone/general/api_secret',$this->_storeId), "auto", "ASCII"));
            
            $this->_limitP2P = Mage::getStoreConfig('agilone/pdp/limit',$this->_storeId);
            
            $this->_cacheP2P = Mage::getStoreConfig('agilone/pdp/cache_lifetime',$this->_storeId);
            
            $this->_limitU2P = Mage::getStoreConfig('agilone/widget/limit',$this->_storeId);
            
            $this->_cacheU2P = Mage::getStoreConfig('agilone/widget/cache_lifetime',$this->_storeId);
            
            /**
             * if guest checkout use email from quote
             */
            if (!$this->_defaultEmail = Mage::getSingleton('checkout/session')->getQuote()->getCustomerEmail()) {
                $this->_defaultEmail = Mage::getStoreConfig('agilone/widget/default_email',$this->_storeId);
            }
            
        } catch (Exception $e) {
            Mage::logException($e);
        }
    }
    
    /**
     * Convert JSON from API into array for template page
     * 
     * @param string $json
     * @return multitype:array
     */
    protected function _convertItemJson($json = null)
    {
        try {
            $items = new Varien_Data_Collection();
            
            if ($json) {
                
                $obj = json_decode($json);
                
                if ($obj && $obj->result && $obj->result->resultSet && $rows = $obj->result->resultSet->resultSetitem) {
                    
                    foreach($rows as $row) {
                        $product = new Varien_Object();
                        $product->setName(htmlentities($row->product->name, ENT_QUOTES, 'UTF-8'));
                        $product->setDescription(htmlentities($row->product->name, ENT_QUOTES, 'UTF-8'));
                        $product->setBrand(htmlentities($row->product->brand, ENT_QUOTES, 'UTF-8'));
                        $product->setSku($row->product->id);  
                        $product->setRank($row->rank);
                        $product->setImageUrl($row->product->imageUrl);
                        $product->setProductUrl($row->product->productUrl);
                        $product->setPrice($row->product->price);
                        $product->setSpecialPrice($row->product->salePrice);
                        $product->setAvailability($row->product->availability);
                        $product->setSaleCondition($row->product->saleCondition);
                        $product->setGtin($row->product->gtin);
                        $product->setMpn($row->product->mpn);
                        $items->addItem($product);
                    }
                }
            }
            
            return $items;
        } catch (Exception $e) {
            Mage::logException($e);
        }
    }
    
    /**
     * Send request for recommendations to AgilOne API
     * 
     * @param string $path The URL-encoded path including the leading slash. E.g. '/1.0/account/1/2'.
     * @param string $expires The time in seconds from the Epoch when the signature expires.
     * @param string $limit Number of recommendations to return
     */
    protected function _request($path,$expires,$limit = 4)
    {
        try {
            $canonical = mb_convert_encoding("GET\n\n\n" . $expires . "\n" . $path, "auto", 'UTF-8');
            $signature = urlencode(base64_encode(hash_hmac("sha256", $canonical, $this->_apiSecret, true)));
            $url = $this->_baseUrl . $path . '?limit=' . $limit . '&a1accesskeyid=' . $this->_apiKey . '&expires=' . $expires . '&signature=' . $signature;
            
            if ($data = @file_get_contents($url)) {
                $items = $this->_convertItemJson($data);
                $this->log(count($items) . " RECOMMENDATIONS: $url",Zend_Log::INFO);
                return $items;
            } else {
                $this->log("NO RECOMMENDATIONS: $url",Zend_Log::WARN);
                return false;
            }
       } catch (Exception $e) {
           Mage::logException($e);
       }
    }
    
    /**
     * Get JSON-formatted object of product recommendations
     * 
     * @return string 
     */
    public function getU2PItems()
    {
        try {

            if ($email = $this->_email ? $this->_email : $this->_defaultEmail) {
                if (is_numeric($this->_tenantId)) {
                    $path = '/1.0/tenant/' . $this->_tenantId . '/customer/' . $email . '/recommendations/product';
                } else {
                    $path = '/1.0/user/' . $email . '/recommendations/product';
                }
               
                $expires = time() + intval($this->_getCacheU2P());
                return $this->_request($path,$expires,$this->_limitU2P);
            } else {
                throw new Exception("U2P default email not set."); 
            }

        } catch (Exception $e) {
            Mage::logException($e);
        }
        return false;
    }
    
   /**
    * Get JSON-formatted object of user product recommendations
    *
    * @param string $productId 
    * @return string 
    */
    public function getP2PItems($productId = null)
    {
        try {
            if (is_numeric($productId)) {
                if (is_numeric($this->_tenantId)) {
                    $path = '/1.0/tenant/' . $this->_tenantId . '/product/' . $this->_sourceId . '/' . $productId . '/recommendations/product';
                } else {
                    $path = '/1.0/product/' . $this->_sourceId . '/' . $productId. '/recommendations/product';
                }
                
                $expires = time() + intval($this->_getCacheP2P());
                return $this->_request($path,$expires,$this->_limitP2P);
            } else {
                return;
            }    
        } catch (Exception $e) {
            Mage::logException($e);
            return false;
        }
    }
 }