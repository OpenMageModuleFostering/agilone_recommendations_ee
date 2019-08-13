<?php
/**
 * @category    AgilOne
 * @package     AgilOne_Recommendations
 * @copyright   Copyright (c) 2014 AgilOne (http://www.agilone.com/)
 * @author      Richard Loerzel (rloerzel@lyonscg.com)
 */

class AgilOne_Recommendations_Model_Adminhtml_System_Config_Source_Log
{
    public function toOptionArray()
    {
        return array(
            array(
                'value' => AgilOne_Recommendations_Helper_Data::LOG_LEVEL_NONE,
                'label' => Mage::helper('recommendations')->__('None')
            ),
            array(
                'value' => AgilOne_Recommendations_Helper_Data::LOG_LEVEL_ERRORS,
                'label' => Mage::helper('recommendations')->__('Errors Only')
            ),
            array(
                    'value' => AgilOne_Recommendations_Helper_Data::LOG_LEVEL_ALL,
                    'label' => Mage::helper('recommendations')->__('All Traffic')
            ),
        );
    }
}
