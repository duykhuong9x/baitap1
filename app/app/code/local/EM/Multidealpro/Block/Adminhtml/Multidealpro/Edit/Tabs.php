<?php
class EM_Multidealpro_Block_Adminhtml_Multidealpro_Edit_Tabs extends Mage_Adminhtml_Block_Widget_Tabs
{
	public function __construct()
	{
		parent::__construct();
		$this->setId('multidealpro_tabs');
		$this->setDestElementId('edit_form');
		$this->setTitle(Mage::helper('multidealpro')->__('Deal Information'));
	}

	protected function _beforeToHtml()
	{
		$this->addTab('form_general', array(
		  'label'     => Mage::helper('multidealpro')->__('Deal Information'),
		  'title'     => Mage::helper('multidealpro')->__('Deal Information'),
		  'content'   => $this->getLayout()->createBlock('multidealpro/adminhtml_multidealpro_edit_tab_general')->toHtml(),
		));

		$this->addTab('products', array(
            'label'     => Mage::helper('multidealpro')->__('Select a Product'),
            'content'   => $this->getLayout()->createBlock('multidealpro/adminhtml_multidealpro_edit_tab_product','category.product.grid')->toHtml(),
        ));

		return parent::_beforeToHtml();
	}
}