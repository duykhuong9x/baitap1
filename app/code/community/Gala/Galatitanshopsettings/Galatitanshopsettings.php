<?php
/**
 * @deprecated use Mage::helper('galatitanshopsettings') instead
 * @methods:
 * - get[Section]_[ConfigName]($defaultValue = '')
 */
class Gala_Galatitanshopsettings_Galatitanshopsettings
{
	static function __callStatic($name, $args) {
		if (method_exists(self, $name))
			call_user_func_array(array(self, $name), $args);
			
		elseif (preg_match('/^get([^_][a-zA-Z0-9_]+)$/', $name, $m)) {
			$segs = explode('_', $m[1]);
			foreach ($segs as $i => $seg)
				$segs[$i] = strtolower(preg_replace('/([^A-Z])([A-Z])/', '$1_$2', $seg));

			$value = Mage::getStoreConfig('galatitanshop/'.implode('/', $segs));
			if (!$value) $value = @$args[0];
			return $value;
		}
		
		else 
			call_user_func_array(array(self, $name), $args);
	}

	
	/**
     * @return array
     * Get css config
    */    
    public function getAllCssConfig() {
        /** Mang luu tru bien duoi dang less */
        $configs = array();
        
        /** import less file */
		$variables_url = Mage::getDesign()->getSkinUrl('css/less/theme.less');
        $function_url = Mage::getDesign()->getSkinUrl('css/less/functions.less');		
		$configs['@variables_url'] = "\"{$variables_url}\"";
        $configs['@function_url'] = "\"{$function_url}\"";
        
        /** Lay bien tu file less.php. File less luu gia tri mac dinh cua bien. 
            Ko khai bao gia tri mac dinh cua bien trong file config.xml do co the ra gia tri null => less.js ko lay duoc bien
        */
        $typoArray = require_once(Mage::getSingleton('core/design_package')->getSkinBaseDir().DS.'css'.DS.'less/less.php');
		foreach($typoArray as $typo => $value){
            $configValue = Mage::getStoreConfig('galatitanshop/typography/'.$typo) ? Mage::getStoreConfig('galatitanshop/typography/'.$typo) : $value;
            if (preg_match("/\\s/",$configValue)) {
				$configs["@{$typo}"] = "~\"$configValue\"";
			}
			else{	
				$configs["@{$typo}"] = "{$configValue}";
			}
		}
        
		/** Backgroung Image */        
        /** Khai bao bien luu duong dan background image trong less */        
		$image_bg_url = Mage::getBaseUrl(Mage_Core_Model_Store::URL_TYPE_WEB);
        $configs['@image_bg_url'] = "~\"{$image_bg_url}\"";
        
        $page_bg_image = Mage::getStoreConfig('galatitanshop/typography/page_bg_file') ? 
			'media/background/'.Mage::getStoreConfig('galatitanshop/typography/page_bg_file')
			: (Mage::getStoreConfig('galatitanshop/typography/page_bg_image') ? 'skin/frontend/default/galatitanshop/images/stripes/'.Mage::getStoreConfig('galatitanshop/typography/page_bg_image') : 'skin/frontend/default/galatitanshop/images/stripes/blank.gif');
        
        $header_bg_image = Mage::getStoreConfig('galatitanshop/typography/header_bg_file') ? 
			'media/background/'.Mage::getStoreConfig('galatitanshop/typography/header_bg_file')
			: (Mage::getStoreConfig('galatitanshop/typography/header_bg_image') ? 'skin/frontend/default/galatitanshop/images/stripes/'.Mage::getStoreConfig('galatitanshop/typography/header_bg_image') : 'skin/frontend/default/galatitanshop/images/stripes/blank.gif');
        
        $body_bg_image = Mage::getStoreConfig('galatitanshop/typography/body_bg_file') ? 
			'media/background/'.Mage::getStoreConfig('galatitanshop/typography/body_bg_file')
			: (Mage::getStoreConfig('galatitanshop/typography/body_bg_image') ? 'skin/frontend/default/galatitanshop/images/stripes/'.Mage::getStoreConfig('galatitanshop/typography/body_bg_image') : 'skin/frontend/default/galatitanshop/images/stripes/blank.gif');
        
        $footer_bg_image = Mage::getStoreConfig('galatitanshop/typography/footer_bg_file') ? 
			'media/background/'.Mage::getStoreConfig('galatitanshop/typography/footer_bg_file')
			: (Mage::getStoreConfig('galatitanshop/typography/footer_bg_image') ? 'skin/frontend/default/galatitanshop/images/stripes/'.Mage::getStoreConfig('galatitanshop/typography/footer_bg_image') : 'skin/frontend/default/galatitanshop/images/stripes/blank.gif');
                            
		$configs['@page_bg_image'] = "~\"{$page_bg_image}\"";
        $configs['@header_bg_image'] = "~\"{$header_bg_image}\"";
		$configs['@body_bg_image'] = "~\"{$body_bg_image}\"";
		$configs['@footer_bg_image'] = "~\"{$footer_bg_image}\""; 
        
        /** custom css file */        
        if($additionalCssFilesString = Mage::getStoreConfig('galatitanshop/typography/additional_css_file')){
			$configs['custom_css_files'] = Mage::getDesign()->getSkinUrl('css/').$additionalCssFilesString;
		}
        /** return less variable array */       
		return $configs;
	}  
}