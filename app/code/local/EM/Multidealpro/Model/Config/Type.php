<?php
class EM_Multidealpro_Model_Config_Type
{
	public function toOptionArray()
    {
		return array(
			array('label' => Mage::helper('multidealpro')->__('Coming soon deal'), 'value' => 0),
			array('label' => Mage::helper('multidealpro')->__('Deal running'), 'value' => 1),
			array('label' => Mage::helper('multidealpro')->__('Past deal'), 'value' => 2)
		);
    }
}
