<?php
/**
 * @category    AgilOne
 * @package     AgilOne_Recommendations
 * @copyright   Copyright (c) 2014 AgilOne (http://www.agilone.com/)
 * @author      Richard Loerzel (rloerzel@lyonscg.com)
 */

class AgilOne_Recommendations_Block_Widget_U2p_Items extends Mage_Core_Block_Template
{
    protected $_items = null;
    
    protected $_title = null;
    
    protected $_columnCount = null;
    
    protected function _construct()
    {
        parent::_construct();
        
        $this->setLayout(Mage::getModel('core/layout'));
        
        $this->setTemplate('recommendations/widget/u2p/items.phtml');
       
        $this->setChild('widget.u2p.item',$this->getLayout()->createBlock('recommendations/widget_u2p_items_item'));
        
        if (!$this->_items =  Mage::getModel('recommendations/feed')->getU2PItems()) {
            $this->_items = new Varien_Data_Collection();
        }
        
        $this->_title =  Mage::getStoreConfig('agilone/widget/title');
        
        $this->_columnCount =  Mage::getStoreConfig('agilone/widget/limit');
    }
    
    public function getTitle()
    {
        return $this->__($this->_title);
    }
    
    public function hasItems()
    {
        return $this->getItemsCount() > 0;
    }
    
    public function getItemCollection()
    {
        return $this->_items;
    }
    
    public function getItemsCount()
    {
        return count($this->getItemCollection());
    }
    
    public function getRowCount()
    {
        return ceil($this->getItemsCount()/$this->getColumnCount());
    }
    
    public function setColumnCount($columns)
    {
        if (intval($columns) > 0) {
            $this->_columnCount = intval($columns);
        }
        return $this;
    }
    
    public function getColumnCount()
    {
        return $this->_columnCount;
    }

}