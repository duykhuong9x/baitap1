<?php
class EM_Multidealpro_Block_Adminhtml_Multidealpro_Edit_Tab_General extends Mage_Adminhtml_Block_Widget_Form
{
  protected function _prepareForm()
  {
      $form = new Varien_Data_Form();
      $this->setForm($form);
      $fieldset = $form->addFieldset('multidealpro_general', array('legend'=>Mage::helper('multidealpro')->__('Deal Information')));

      $fieldset->addField('product_id', 'text', array(
          'label'     => Mage::helper('multidealpro')->__('Product Name'),
          'class'     => 'required-entry em_product',
          'required'  => true,
          'name'      => 'data[product_id]',
		  'style'	  => 'display:none'
      ))->setAfterElementHtml($this->namehtml());

	  $fieldset->addField('product_name', 'hidden', array(
          'name'      => 'product_name'
      ));

      $fieldset->addField('price', 'text', array(
          'label'     => Mage::helper('multidealpro')->__('Deal Price'),
          'class'     => 'required-entry',
          'required'  => true,
          'name'      => 'data[price]',
	  ));

	  $fieldset->addField('qty', 'text', array(
          'label'     => Mage::helper('multidealpro')->__('Deal Qty'),
          'class'     => 'required-entry',
          'required'  => true,
          'name'      => 'data[qty]',
	  ));

	  $fieldset->addField('date_from', 'date', array(
          'label'     => Mage::helper('multidealpro')->__('Data/Time From'),
		  'class'     => 'required-entry em_date',
          'required'  => true,
          'name'      => 'data[date_from]',
		  'time'      =>    true,
		  'style' 	  => 'width: 150px;', 
          'format'    =>    "MM/dd/yyyy H:mm:ss",
		  //'format'    =>    Mage::app()->getLocale()->getDateTimeFormat(Mage_Core_Model_Locale::FORMAT_TYPE_SHORT),
          'image'     =>    $this->getSkinUrl('images/grid-cal.gif')
	  ));
	  
	  $fieldset->addField('date_to', 'date', array(
          'label'     => Mage::helper('multidealpro')->__('Data/Time To'),
		  'class'     => 'required-entry',
          'required'  => true,
          'name'      => 'data[date_to]',
		  'time'      =>    true,
		  'style' 	  => 'width: 150px;',
          'format'    =>    "MM/dd/yyyy H:mm:ss",
          //'format'    =>    Mage::app()->getLocale()->getDateTimeFormat(Mage_Core_Model_Locale::FORMAT_TYPE_SHORT),
          'image'     =>    $this->getSkinUrl('images/grid-cal.gif')
	  ));

	  /**
         * Check is single store mode
         */
        if (!Mage::app()->isSingleStoreMode()) {
            $field =$fieldset->addField('store_id', 'multiselect', array(
                'name'      => 'data[stores][]',
                'label'     => Mage::helper('multidealpro')->__('Store View'),
                'title'     => Mage::helper('multidealpro')->__('Store View'),
                'required'  => true,
                'values'    => Mage::getSingleton('adminhtml/system_store')->getStoreValuesForForm(false, true),
            ));
            $renderer = $this->getLayout()->createBlock('adminhtml/store_switcher_form_renderer_fieldset_element');
            $field->setRenderer($renderer);
        }
        else {
            $fieldset->addField('store_id', 'hidden', array(
                'name'      => 'data[stores][]',
                'value'     => Mage::app()->getStore(true)->getId()
            ));
            $model->setStoreId(Mage::app()->getStore(true)->getId());
        }

	  $fieldset->addField('recent', 'select', array(
          'label'     => Mage::helper('multidealpro')->__('Show Recent Deal Sidebar'),
          'name'      => 'data[recent]',
          'values'    => array(
			  array(
                  'value'     => 0,
                  'label'     => Mage::helper('multidealpro')->__('No'),
              ),
              array(
                  'value'     => 1,
                  'label'     => Mage::helper('multidealpro')->__('Yes'),
              ),
          ),
      ));

	  $fieldset->addField('status', 'hidden', array(
          'name'      => 'data[status]'
      ));

	  $fieldset->addField('qty_sold', 'hidden', array(
          'name'      => 'data[qty_sold]'
      ));
	  
	   $fieldset->addField('has_new', 'hidden', array(
          'name'      => 'data[has_new]'
      ));
	  
	   $fieldset->addField('has_end', 'hidden', array(
          'name'      => 'data[has_end]'
      ));

	  $fieldset->addField('is_active', 'select', array(
          'label'     => Mage::helper('multidealpro')->__('Deal Status'),
          'name'      => 'data[is_active]',
          'values'    => array(
              array(
                  'value'     => 1,
                  'label'     => Mage::helper('multidealpro')->__('Enabled'),
              ),
              array(
                  'value'     => 2,
                  'label'     => Mage::helper('multidealpro')->__('Disabled'),
              ),
          ),
      ));

      if ( Mage::getSingleton('adminhtml/session')->getMultidealproData() )
      {
          $form->setValues(Mage::getSingleton('adminhtml/session')->getMultidealproData());
          Mage::getSingleton('adminhtml/session')->setMultidealproData(null);
      } elseif ( Mage::registry('multidealpro_data') ) {
          $form->setValues(Mage::registry('multidealpro_data')->getData());
      }
      return parent::_prepareForm();
  }
 
	public function namehtml(){
		$html = '<a href="javascript:void(0)" class="redir_p" ><span><span>'.Mage::helper('multidealpro')->__('Select a products').'</span></span></a>';
		$html .= '<div class="show_name" style="display:none"></div>';

		return $html;
	}
}