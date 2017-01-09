<?php
class EM_Multidealpro_Block_Adminhtml_Renderer_Status extends Mage_Adminhtml_Block_Widget_Grid_Column_Renderer_Text
{
	 /**
     * Render a grid cell as options
     *
     * @param Varien_Object $row
     * @return string
     */
    public function render(Varien_Object $row)
    {
        $options = $this->getColumn()->getOptions();
        $showMissingOptionValues = (bool)$this->getColumn()->getShowMissingOptionValues();
        if (!empty($options) && is_array($options)) {
            $value = $row->getData($this->getColumn()->getIndex());
            if (isset($options[$value])) {
                return '<span class="stt_'.strtolower($options[$value]).'"><span>'.$options[$value].'</span></span>';
            } elseif (in_array($value, $options)) {
                return $value;
            }
        }
    }
}
?>