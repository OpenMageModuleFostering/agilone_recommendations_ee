<?php
/**
 * @category    AgilOne
 * @package     AgilOne_Recommendations
 * @copyright   Copyright (c) 2014 AgilOne (http://www.agilone.com/)
 * @author      Richard Loerzel (rloerzel@lyonscg.com)
 */

class AgilOne_Recommendations_Block_Widget_U2p_Items_Item extends Mage_Catalog_Block_Product_Abstract
{
    protected $_item = null;
    
    protected $_position = null;
    
    protected function _construct()
    {
        parent::_construct();
    
        $this->setTemplate('recommendations/widget/u2p/items/item.phtml');
    }
    
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