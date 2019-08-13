<?php
/**
 * Observer Model
 * 
 * @category    AgilOne
 * @package     AgilOne_Recommendations
 * @copyright   Copyright (c) 2014 AgilOne (http://www.agilone.com/)
 * @author      Richard Loerzel (rloerzel@lyonscg.com)
 */

class AgilOne_Recommendations_Model_Observer extends Mage_Core_Model_Abstract
{

    /**
     * Capture Add To Cart/Remove From Cart events as a session 
     * variable to limit output of cart contents in Webtag.php
     */
    public function updateProductIdCart()
    {
        Mage::getSingleton('checkout/session')->setA1Cart(true);
    }
    
    
    /**
     * Capture search keywords
     * @param Varien_Event_Observer $event
     */
    public function captureSearch(Varien_Event_Observer $event)
    {
        try {
            Mage::getSingleton('checkout/session')->setA1Search($event->getData('catalogsearch_query')->getData('query_text'));
        } catch (Mage_Core_Exception $e) {
            Mage::logException($e);
        }
    }
    
}