<?php
/**
 * Ajax Controller
 * 
 * @category    AgilOne
 * @package     AgilOne_Recommendations
 * @copyright   Copyright (c) 2014 AgilOne (http://www.agilone.com/)
 * @author      Richard Loerzel (rloerzel@lyonscg.com)
 */

class AgilOne_Recommendations_AjaxController extends Mage_Core_Controller_Front_Action
{
    public function webtagAction()
    {
        $this->loadLayout();
        $webtags = $this->getLayout()->createBlock('recommendations/webtag')->setTemplate('recommendations/webtag.phtml')->toHtml();
        $content = '<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">';
        $content .= '<html xmlns="http://www.w3.org/1999/xhtml">';
        $content .= '<head><title>' . html_entity_decode(Mage::getStoreConfig('design/head/default_description')) . '</title>';
        $content .= '</head><body>' . $webtags . '</body></html>';
        $this->getResponse()->setBody($content);
    }
}