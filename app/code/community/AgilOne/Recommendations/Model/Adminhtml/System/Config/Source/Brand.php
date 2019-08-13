<?php
/**
 * @category    AgilOne
 * @package     AgilOne_Recommendations
 * @copyright   Copyright (c) 2014 AgilOne (http://www.agilone.com/)
 * @author      Richard Loerzel (rloerzel@lyonscg.com)
 */

class AgilOne_Recommendations_Model_Adminhtml_System_Config_Source_Brand
{
    protected $_options;
    
    public function toOptionArray()
    {
        if (!$this->_options) {
            $this->_options = array();
            $attributes = Mage::getResourceModel('catalog/product_attribute_collection')
                            ->addVisibleFilter()
                            ->getSelect()
                            ->order(array('frontend_label ASC'))
                            ->query();
            
            foreach($attributes as $attribute) {
                $this->_options[] =  array(
                    'value'=> $attribute['attribute_code'],
                    'label'=> $attribute['frontend_label'] . ' (' . $attribute['attribute_code'] . ')'
                );
            }
        }
        return $this->_options;
    }
}
