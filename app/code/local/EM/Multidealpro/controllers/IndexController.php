<?php
class EM_Multidealpro_IndexController extends Mage_Core_Controller_Front_Action
{
    public function indexAction()
    {	
		$setting 	=	Mage::helper('multidealpro'); 
		if($setting->checkEnable() == 1){
			$this->loadLayout();
			$this->initLayoutMessages(array('catalog/session', 'tag/session', 'checkout/session'));

			$layout = Mage::helper("multidealpro")->getMainDeal_Layout();
			switch ($layout) {
				case 'one_column': $template="page/1column.phtml";break;
				case 'two_columns_left': $template="page/2columns-left.phtml";break;
				case 'two_columns_right': $template="page/2columns-right.phtml";break;
				default: $template="page/3columns.phtml";break;
			}
			$this->getLayout()->getBlock('root')->setTemplate($template);

			$this->renderLayout();
		}else $this->_redirectUrl(Mage::getBaseUrl());
    }
	
	public function editAction()
	{
		$setting 	=	Mage::helper('multidealpro');
		$post		=	Mage::app()->getRequest()->getPost();
		$data		=	$setting->html_decode($post['id']);
		$deal_id 		= $data['deal_id'];
		$deal_status 	= $data['deal_status'];

		$deal = Mage::getModel('multidealpro/multidealpro')->load($deal_id);
		$deal = $setting->checkDeal($deal);
		if($deal->getStatus() == 1 )			$result['check'] = 1;
		elseif($deal->getStatus() == 2 )		$result['check'] = 2;
		else								$result['check'] = 0;

		if($result['check'] == 1){
			$where = 'multidealpro.status = 1 AND multidealpro.deal_id = '.$data['deal_id'];
			$collection = Mage::getModel('multidealpro/multidealpro')->getDealCollection($where);

			$_product	=	$collection->getFirstItem();
			$theProductBlock = new Mage_Catalog_Block_Product;

			$result['price'] 	= $theProductBlock->getPriceHtml($_product);
			$result['qty']		= $setting->getQtyleft($_product);
			$result['label']	= $setting->getLabel($_product);

			$result['clock']	=		$setting->getClock($_product);

			$result['btn_cart']	.= 	'<p><button type="button" title="'.$this->__('Add to Cart').'" class="button btn-cart" onclick="setLocation(\''.$theProductBlock->getAddToCartUrl($_product).'\')"><span><span>'.$this->__('Add to Cart').'</span></span></button></p>';

			$result['addto']	= '<ul class="add-to-links">';
			if (Mage::helper('wishlist')->isAllow())
				$result['addto']	.=		'<li><a href="'.Mage::helper('wishlist')->getAddUrl($_product).'" class="link-wishlist" title="'.$this->__('Add to Wishlist').'">'.$this->__('Add to Wishlist').'</a></li>';
			if($_compareUrl=Mage::helper('catalog/product_compare')->getAddUrl($_product))
				$result['addto']	.=		'<li><span class="separator">|</span> <a href="'.$_compareUrl.'" class="link-compare" title="'.$this->__('Add to Compare').'">'.$this->__('Add to Compare').'</a></li>';
			$result['addto']	.=	'</ul>';

		}else{
			$result['html'] = '<div class="msg_soldout">'.$this->__('Time Out').'</div>';
		}

		if(preg_match('/MSIE/i',$_SERVER['HTTP_USER_AGENT']))
		{	// if IE
			echo json_encode($result, JSON_HEX_TAG);exit;
		}else{	// other browser
			echo json_encode($result);exit;
		}
	}
	
	public function testcronAction(){
		Mage::getModel('multidealpro/multidealpro')->refeshData();
	}
}