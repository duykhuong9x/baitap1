<?php
class EM_Multidealpro_Model_Active extends Varien_Object
{
    const STATUS_ENABLED		= 1;
    const STATUS_DISABLED		= 2;

    static public function getOptionArray()
    {
        return array(
            self::STATUS_ENABLED   		=> Mage::helper('multidealpro')->__('Enabled'),
            self::STATUS_DISABLED   	=> Mage::helper('multidealpro')->__('Disabled')
        );
    }
}