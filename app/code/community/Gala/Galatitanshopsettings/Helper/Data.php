<?php
/**
 * @methods:
 * - get[Section]_[ConfigName]($defaultValue = '')
 */
class Gala_Galatitanshopsettings_Helper_Data extends Mage_Core_Helper_Abstract
{
	public function __call($name, $args) {
		if (method_exists($this, $name))
			call_user_func_array(array($this, $name), $args);
			
		elseif (preg_match('/^get([^_][a-zA-Z0-9_]+)$/', $name, $m)) {
			$segs = explode('_', $m[1]);
			foreach ($segs as $i => $seg){
				//$segs[$i] = strtolower(preg_replace('/([^A-Z])([A-Z])/', '$1_$2', $seg));
				$seg = preg_replace('/([^A-Z])([A-Z])/', '$1_$2', $seg);
				$seg = preg_replace('/([A-Z])([A-Z])/', '$1_$2', $seg);
				$segs[$i] = strtolower(preg_replace('/([A-Z])([A-Z])/', '$1_$2', $seg));
			}
			$value = Mage::getStoreConfig('galatitanshop/'.implode('/', $segs));
			if (!$value) $value = @$args[0];
			return $value;
		}
		
		else 
			call_user_func_array(array($this, $name), $args);
	}
    
    /**
     * @return array
     * Get css config
    */    
    public function getAllCssConfig() {
        /** Mang luu tru bien duoi dang less */
        $configs = array();
        $skinUrl = 'frontend/default/galatitanshop/css/';
        $stripesUrl = 'skin/frontend/default/galatitanshop/images/stripes/';
        
        /** import less file */
		$variables_url = Mage::getDesign()->getSkinUrl('css/less/theme.less');
        $function_url = Mage::getDesign()->getSkinUrl('css/less/functions.less');		
		$configs['@variables_url'] = "\"{$variables_url}\"";
        $configs['@function_url'] = "\"{$function_url}\"";
        
        /** Lay bien tu file less.php. File less luu gia tri mac dinh cua bien. 
            Ko khai bao gia tri mac dinh cua bien trong file config.xml do co the ra gia tri null => less.js ko lay duoc bien
            Chi config bien google font va bien google font weight
        */        
        $typoArray = require_once(Mage::getBaseDir('skin') . DS . $skinUrl . 'less/less.php');
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
			: (Mage::getStoreConfig('galatitanshop/typography/page_bg_image') ? $stripesUrl.Mage::getStoreConfig('galatitanshop/typography/page_bg_image') : $stripesUrl.'blank.gif');
        
        $header_bg_image = Mage::getStoreConfig('galatitanshop/typography/header_bg_file') ? 
			'media/background/'.Mage::getStoreConfig('galatitanshop/typography/header_bg_file')
			: (Mage::getStoreConfig('galatitanshop/typography/header_bg_image') ? $stripesUrl.Mage::getStoreConfig('galatitanshop/typography/header_bg_image') : $stripesUrl.'blank.gif');
        
        $body_bg_image = Mage::getStoreConfig('galatitanshop/typography/body_bg_file') ? 
			'media/background/'.Mage::getStoreConfig('galatitanshop/typography/body_bg_file')
			: (Mage::getStoreConfig('galatitanshop/typography/body_bg_image') ? $stripesUrl.Mage::getStoreConfig('galatitanshop/typography/body_bg_image') : $stripesUrl.'blank.gif');
        
        $footer_bg_image = Mage::getStoreConfig('galatitanshop/typography/footer_bg_file') ? 
			'media/background/'.Mage::getStoreConfig('galatitanshop/typography/footer_bg_file')
			: (Mage::getStoreConfig('galatitanshop/typography/footer_bg_image') ? $stripesUrl.Mage::getStoreConfig('galatitanshop/typography/footer_bg_image') : $stripesUrl.'blank.gif');
                            
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
	
	public function insertStaticBlock($dataBlock) {
		// insert a block to db if not exists
		$block = Mage::getModel('cms/block')->getCollection()->addFieldToFilter('identifier', $dataBlock['identifier'])->getFirstItem();
		if (!$block->getId())
			$block->setData($dataBlock)->save();
		return $block;
	}
	
	public function insertPage($dataPage) {
		$page = Mage::getModel('cms/page')->getCollection()->addFieldToFilter('identifier', $dataPage['identifier'])->getFirstItem();
		if (!$page->getId())
			$page->setData($dataPage)->save();
		return $page;
	}
	
    public function checkMobilePhp() {
		require_once(Mage::getBaseDir('lib') . DS . 'em/Mobile_Detect.php');
		$detect = new Mobile_Detect();
        $checkmobile = $detect->isMobile();
        $checktablet = $detect->isTablet();
        if($checkmobile){
            if($checktablet){
                return false;
            }else{
                return true;
            }
            
        }else{
            return false;
        }
	}
	public function isShowOfferPrice($productPrice){
		if(!Mage::registry('current_product'))
			return false;
		return Mage::registry('current_product')->getId() == $productPrice->getId();
	}
    
    public function checkMobile() {
		require_once(Mage::getBaseDir('lib') . DS . 'em/Mobile_Detect.php');
		$detect = new Mobile_Detect();
        $checkmobile = $detect->isMobile();
        if($checkmobile){
            return 'true';            
        }else{
            return 'false';
        }
	}
    
    public function checkTabletPhp() {
		require_once(Mage::getBaseDir('lib') . DS . 'em/Mobile_Detect.php');
		$detect = new Mobile_Detect();
        $checktablet = $detect->isTablet();
        if($checktablet){
            return 'true';
        }else{
            return 'false';
        }
	}
    
    /**
     * fix review
     **/
	 /*
    public function getActionReview(){
		$url = Mage::helper('core/url')->getCurrentUrl();
		$url_check_update_wishlist = 'wishlist/index/configure';
        $url_check_edit_cart = 'checkout/cart/configure';
		if(stripos($url,$url_check_update_wishlist) || stripos($url,$url_check_edit_cart)):
			$id = Mage::registry('current_product')->getId();
			return Mage::getUrl('review/product/post/', array('id' => $id));
		else:
			$productId = Mage::app()->getRequest()->getParam('id', false);
			return Mage::getUrl('review/product/post', array('id' => $productId));
		endif;
	}*/
	
	public function getActionReview(){
		$url = Mage::helper('core/url')->getCurrentUrl();
		$url_check = 'wishlist/index/configure';
		$url_check2 = 'checkout/cart/configure';
		if(stripos($url,$url_check)){
			$id = Mage::registry('current_product')->getId();
			return Mage::getUrl('review/product/post/', array('id' => $id,'_secure' => true));
		} else {
			if(stripos($url,$url_check2)){
				$id = Mage::getSingleton('catalog/session')->getLastViewedProductId();
				return Mage::getUrl('review/product/post/', array('id' => $id,'_secure' => true));
			}else{
				$productId = Mage::app()->getRequest()->getParam('id', false);
				return Mage::getUrl('review/product/post', array('id' => $productId,'_secure' => true));
			}
		}
	}
    
    /**
     *  multi deal
     **/
    public function getPercentOff($_product) {
		$specialPrice = $_product->getSpecialPrice();
		$regularPrice = $_product->getPrice();
		if($specialPrice > 0 && $regularPrice != 0){
			$off	=	 number_format(100*(float)($regularPrice-$specialPrice)/$regularPrice,0);
			$html	=	"<span class='sale_off'>off <span>".$off.$this->__("%")."</span></span>";
			return $html;
		}
		else
			return 0;
	}
    
    public function getCategoriesCustom($parent,$curId){
				
		try{
			$children = $parent->getChildrenCategories();
						
		}
		catch(Exception $e){
			return '';
		}
		return $children;
	}
    
    public function checkWindowsMobileOS() {
		require_once(Mage::getBaseDir('lib') . DS . 'em/Mobile_Detect.php');
		$detect = new Mobile_Detect();
        $checkWP = $detect->isWindowsMobileOS();
        if($checkWP){
            return true;
        }else{
            return false;
        }
	}
}
