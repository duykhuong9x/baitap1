<?php
class EM_Multidealpro_Block_Adminhtml_Multidealpro_Edit extends Mage_Adminhtml_Block_Widget_Form_Container
{
    public function __construct()
    {
        parent::__construct();
                 
        $this->_objectId = 'id';
        $this->_blockGroup = 'multidealpro';
        $this->_controller = 'adminhtml_multidealpro';
        
        $this->_updateButton('save', 'label', Mage::helper('multidealpro')->__('Save Deal'));
        $this->_updateButton('delete', 'label', Mage::helper('multidealpro')->__('Delete Item'));
		
        $this->_addButton('saveandcontinue', array(
            'label'     => Mage::helper('adminhtml')->__('Save And Continue Edit'),
            'onclick'   => 'saveAndContinueEdit()',
            'class'     => 'save',
        ), -100);

        $this->_formScripts[] = "
            function toggleEditor() {
                if (tinyMCE.getInstanceById('multidealpro_content') == null) {
                    tinyMCE.execCommand('mceAddControl', false, 'multidealpro_content');
                } else {
                    tinyMCE.execCommand('mceRemoveControl', false, 'multidealpro_content');
                }
            }

            function saveAndContinueEdit(){
                editForm.submit($('edit_form').action+'back/edit/');
            }
        ";
    }

    public function getHeaderText()
    {
        if( Mage::registry('multidealpro_data') && Mage::registry('multidealpro_data')->getId() ) {
            return Mage::helper('multidealpro')->__("Edit Deal '%s'", $this->escapeHtml(Mage::registry('multidealpro_data')->getProductName()));
        } else {
            return Mage::helper('multidealpro')->__('Add New Deal');
        }
    }
}