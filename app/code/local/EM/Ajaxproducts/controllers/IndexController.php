<?php
class EM_Ajaxproducts_IndexController extends Mage_Core_Controller_Front_Action
{
    public function indexAction()
    {
		$params = unserialize(base64_decode($this->getRequest()->getParam('params')));
		$featuredBlock = $this->getLayout()->createBlock('ajaxproducts/list')->setData($params);
		$this->getResponse()->setBody($featuredBlock->toHtml());
    }
}