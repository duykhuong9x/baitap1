<?php
class EM_Multidealpro_Model_Deal extends Mage_Core_Model_Abstract
{
 	public function toOptionArray()
    {
		$options = array(
			array('value' => 0, 'label' => Mage::helper("multidealpro")->__('Coming soon deal')),
			array('value' => 1, 'label' => Mage::helper("multidealpro")->__('Deal running')),
			array('value' => 2, 'label' => Mage::helper("multidealpro")->__('Past deal'))
		);
        return $options;
    }
}
?>