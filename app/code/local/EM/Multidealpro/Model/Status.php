<?php
class EM_Multidealpro_Model_Status extends Varien_Object
{
    const STATUS_QUEUED		= 0;
    const STATUS_RUNNING	= 1;
    const STATUS_END		= 2;

    static public function getOptionArray()
    {
        return array(
            self::STATUS_QUEUED   	=> Mage::helper('multidealpro')->__('Queued'),
            self::STATUS_RUNNING    => Mage::helper('multidealpro')->__('Running'),
            self::STATUS_END   		=> Mage::helper('multidealpro')->__('End')
        );
    }
}