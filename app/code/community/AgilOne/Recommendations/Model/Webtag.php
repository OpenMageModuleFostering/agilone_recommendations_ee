<?php
/**
 * WebTab Model: Provides data for a1as.push javascript variables
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

class AgilOne_Recommendations_Model_Webtag extends AgilOne_Recommendations_Model_Abstract
{
   /**
    * Get SKU and brand of product viewed
    * @return string|boolean
    */
   public function getProductView()
   {
       try {
           if ($product = $this->getProduct()) {
               $a1Product = new Varien_Object();
               $a1Product->setProductId($product->getId());
               $a1Product->setBrandId($product->getAttributeText($this->_brandAttribute));
               return $a1Product;
           }
       } catch (Exception $e) {
           Mage::logException($e);
       }
       
       return false;
   }
   
   /**
    * Get URL-Key of category viewed
    * @return string|boolean
    */
    public function getCategoryIdView()
    {
        try {
            if ($this->_handle === 'catalog_category_view') {
               return Mage::registry('current_category')->getId();
            }
        } catch (Exception $e) {
            Mage::logException($e);
        }
        
        return false;
   }
   
   /**
    * Get contents of cart if item is added or removed
    * @return string|boolean
    */   
    public function getProductIdCart()
    {
        try {
            if ( $this->_checkoutSession->getA1Cart()) {
                $ids = array();
                $quote = $this->_checkoutSession->getQuote();
                
                foreach($quote->getAllVisibleItems() as $item) {
                   $ids[] = $item->getProductId();
                }
                
                $this->_checkoutSession->setA1Cart(false);
                
                /**
                 * Return " " if cart has been emptied.
                 * Trim on webtag.phtml will result in:
                 * _a1as.push(["setvar","productid_cart",""]);
                 */
                return $ids ? implode(',',$ids) : ' ';
            }
        } catch (Exception $e) {
            Mage::logException($e);
        }
        
        return false;
   }
   
    /**
     * Get order information on success page
     * @return Varien_Object|boolean
     */
    public function getOrder()
    {
        try {
            $a1Order =  new Varien_Object();
            if ($this->_action == 'success') {
                $_lastOrderId = Mage::getSingleton('checkout/session')->getLastOrderId();
                if (is_numeric($_lastOrderId) && $order = Mage::getModel('sales/order')->load($_lastOrderId)) {
                    $a1Order->setOrderId($order->getIncrementId());
                    $a1Order->setOrderTotal($order->getGrandTotal());
                    $ids = array();
                    foreach($order->getAllVisibleItems() as $item) {
                        $ids[] = $item->getProductId();
                    }
                    $a1Order->setProductIdOrder(implode(',',$ids));
                    $a1Order->setCustomerEmail($order->getCustomerEmail());
                    $a1Order->setCustomerId($order->getCustomerId() ? $order->getCustomerId() : 0);
                }
                return $a1Order;
            }
           
        } catch (Exception $e) {
            Mage::logException($e);
        }
        return false;
    }
    
    /**
     * Get search keywords
     * @return string|boolean
     */
    public function getOnsiteSearch()
    {
        try {
            $a1Search = Mage::getSingleton('checkout/session')->getA1Search();
            Mage::getSingleton('checkout/session')->setA1Search();
            return $a1Search;
        } catch (Exception $e) {
            Mage::logException($e);
        }
        return false;
    }
}