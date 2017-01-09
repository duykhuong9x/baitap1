<?php
class EM_Multidealpro_Model_Datetime 
{
	/* Update custom layout */
    public function changeDatetimeEvent($observer)
	{
		$form = $observer->getForm();
		$special_date_form = $form->getElement('special_from_date');
		if ($special_date_form) {
			//$special_date_form->setFormat(Mage::app()->getLocale()->getDateTimeFormat(Mage_Core_Model_Locale::FORMAT_TYPE_SHORT));
			$special_date_form->setFormat("MM/dd/yyyy H:mm:ss");
			$special_date_form->setTime(true);
		}
		
		$special_date_to = $form->getElement('special_to_date');
		if ($special_date_to) {
			//$special_date_to->setFormat(Mage::app()->getLocale()->getDateTimeFormat(Mage_Core_Model_Locale::FORMAT_TYPE_SHORT));
			$special_date_to->setFormat("MM/dd/yyyy H:mm:ss");
			$special_date_to->setTime(true);
		}
    }
}