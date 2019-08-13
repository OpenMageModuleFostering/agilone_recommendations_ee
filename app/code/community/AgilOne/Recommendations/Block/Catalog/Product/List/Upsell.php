<?php
/**
 * @category    AgilOne
 * @package     AgilOne_Recommendations
 * @copyright   Copyright (c) 2014 AgilOne (http://www.agilone.com/)
 * @author      Richard Loerzel (rloerzel@lyonscg.com)
 */

class AgilOne_Recommendations_Block_Catalog_Product_List_Upsell extends Mage_Catalog_Block_Product_Abstract
{
    
    protected $_items = null;
    
    protected $_title = null;
        
    protected $_columnCount = null;
    
    protected function _construct()
    {
        parent::_construct();
        
        $this->setTemplate('recommendations/catalog/product/list/upsell.phtml');
        
        if (!$this->_items =  Mage::getModel('recommendations/feed')->getP2PItems($this->getProduct()->getId())) {
            $this->_items = new Varien_Data_Collection();
        }
        
        $this->_title =  Mage::getStoreConfig('agilone/pdp/title');
        
        $this->_columnCount =  Mage::getStoreConfig('agilone/pdp/limit');
        
        $product = Mage::registry('product');
    }
        
    public function getTitle()
    {
        return $this->__($this->_title);
    }
    
    public function hasItems()
    {
        return $this->getItemsCount() > 0;
    }
    
    public function getItems()
    {
        return $this->getItemCollection();
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