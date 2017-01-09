<?php
class EM_Multidealpro_Block_Adminhtml_Multidealpro extends Mage_Adminhtml_Block_Widget_Grid_Container
{
  public function __construct()
  {
    $this->_controller = 'adminhtml_multidealpro';
    $this->_blockGroup = 'multidealpro';
    $this->_headerText = Mage::helper('multidealpro')->__('Deal Manager');
    $this->_addButtonLabel = Mage::helper('multidealpro')->__('Add New Deal');
    parent::__construct();
  }
}