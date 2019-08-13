<?php
/**
 * @category    AgilOne
 * @package     AgilOne_Recommendations
 * @copyright   Copyright (c) 2014 AgilOne (http://www.agilone.com/)
 * @author      Richard Loerzel (rloerzel@lyonscg.com)
 */

class AgilOne_Recommendations_Block_Catalog_Product_List_Upsell_Item extends Mage_Catalog_Block_Product_Abstract
{
    protected $_item = null;
    
    protected $_position = null;
    
    public function getItem()
    {
        return $this->_item;
    }
    
    public function setItem($item)
    {
        $this->_item = $item;
        return $this;
    }
    
    public function setPosition($position)
    {
        $this->_position = $position;
        return $this;
    }
}