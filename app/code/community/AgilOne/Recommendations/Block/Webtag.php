<?php
/**
 * WebTab Block: Provides data for a1as.push javascript variables
 * 
 * var _a1as = _a1as || [];
 * _a1as.push(["init","1234567"]);
 * _a1as.push(["setvar","userid","142"]);
 * _a1as.push(["setvar","email","rloerzel@lyonscg.com"]);
 * _a1as.push(["track"]);
 * 
 * @category    AgilOne
 * @package     AgilOne_Recommendations
 * @copyright   Copyright (c) 2014 AgilOne (http://www.agilone.com/)
 * @author      Richard Loerzel (rloerzel@lyonscg.com)
 */

class AgilOne_Recommendations_Block_Webtag extends Mage_Core_Block_Template
{

    protected $_webtagModel;

    
    
    protected function _construct()
    {
        parent::_construct();
        
       $this->_webtagModel = Mage::getModel('recommendations/webtag');
       
   }
   
   public function isEnabled()
   {
       return $this->_webtagModel->isEnabled();
   }
   
   public function getHandle()
   {
       return $this->_webtagModel->getHandle();
   }
   /**
    * Get Website's WebTagId
    *@return string|boolean
    */
   public function getWebTagId()
   {
       return $this->_webtagModel->getWebTagId();
   }

   /**
    * Get Logged-In User's Magento ID
    * @return string 
    */
   public function getUserId()
   {
       return $this->_webtagModel->getUserId();
   }
   
   /**
    * Get Logged-In User's Email Address
    * @return string|boolean
    */
   public function getEmail()
   {
       return $this->_webtagModel->getEmail();
   }
   
   /**
    * Get SKU and brand of product viewed
    * @return string|boolean
    */
   public function getProductView()
   {
       return $this->_webtagModel->getProductView();
   }
   
   /**
    * Get URL-Key of category viewed
    * @return string|boolean
    */
    public function getCategoryIdView()
    {
       return $this->_webtagModel->getCategoryIdView();
   }
   
   /**
    * Get contents of cart if item is added or removed
    * @return string|boolean
    */   
    public function getProductIdCart()
    {
        return $this->_webtagModel->getProductIdCart();
   }
   
    /**
     * Get order information on success page
     * @return Varien_Object|boolean
     */
    public function getOrder()
    {
         return $this->_webtagModel->getOrder();
    }
    
    /**
     * Get search keywords
     * @return string|boolean
     */
    public function getOnsiteSearch()
    {
       return $this->_webtagModel->getOnsiteSearch();
    }

}