<?php
/**
 * Iframe Block
 * 
 * @category    AgilOne
 * @package     AgilOne_Recommendations
 * @copyright   Copyright (c) 2014 AgilOne (http://www.agilone.com/)
 * @author      Richard Loerzel (rloerzel@lyonscg.com)
 */

class AgilOne_Recommendations_Block_Iframe extends Mage_Core_Block_Template
{
    
    protected function _getUrl()
    {
        return Mage::getUrl('recommendations/ajax/webtag',array('_secure'=> Mage::app()->getStore()->isCurrentlySecure()));
    }
}