<?php
class EM_Multidealpro_Block_Multidealpro extends Mage_Core_Block_Template
{
	public function _prepareLayout()
    {
		return parent::_prepareLayout();
    }
    
     public function getMultidealpro()     
     { 
        if (!$this->hasData('multidealpro')) {
            $this->setData('multidealpro', Mage::registry('multidealpro'));
        }
        return $this->getData('multidealpro');
        
    }
}