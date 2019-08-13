<?php
/**
 * @category    AgilOne
 * @package     AgilOne_Recommendations
 * @copyright   Copyright (c) 2014 AgilOne (http://www.agilone.com/)
 * @author      Richard Loerzel (rloerzel@lyonscg.com)
 */

class AgilOne_Recommendations_Block_Widget_U2p extends Mage_Core_Block_Template implements Mage_Widget_Block_Interface 
{ 
       
    protected function _toHtml() 
    { 
        return $this->getLayout()->createBlock('recommendations/widget_u2p_items')->toHtml();
    }
}