<?php
class EM_Multidealpro_Helper_Data extends Mage_Core_Helper_Abstract
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
			$value = Mage::getStoreConfig('multidealpro/'.implode('/', $segs));
			if (!$value) $value = @$args[0];
			return $value;
		}
		else 
			call_user_func_array(array($this, $name), $args);
	}

	public function checkEnable(){
		$check = 0;	// disabled
		if($this->getGeneral_EnableMultideal() == 1) $check = 1;		// enabled
		return $check;
	}

	public function getQtyleft($product){
		$result = 0;
		if($product->isSaleable()){
			$qty 			= Mage::getModel('cataloginventory/stock_item')->loadByProduct($product)->getQty();
			$result = $qty;
		}

		$html = "<div class=\"deal_qty\">";
		if($product->getDealStatus() == 0)
			$html .=	"<span class=\"qty_left soon\">".$this->__("Time left to buy !")."</span>";
		else
			$html .=	"<span class=\"qty_left soldin\">".$this->__("QTY : %d item(s) left !",$result)."</span>";
		$html .= "</div>";

		return $html;
	}

	public function getLabel($_product) {
		$specialPrice = $_product->getSpecialPrice();
		$regularPrice = $_product->getPrice();
		$status = $_product->getDealStatus();
		$html = "";

		if($status == 0){
			$html	.=	"<span class='sale_off queued'>".$this->__("Coming <span>Soon</span>")."</span>";
		}elseif($status == 1){
			if($specialPrice > 0 && $regularPrice != 0){
				if($specialPrice < $regularPrice){
					$off	=	 number_format(100*(float)($regularPrice-$specialPrice)/$regularPrice,0);
					$html	.=	"<span class='sale_off'>".$this->__("off <span>%d%s</span>",$off,"%")."</span>";
				}
			}
		}

		return $html;
	}

	public function getTimeClock($_product){
		$time = 0;
		if($_product){
			$status = $_product->getDealStatus();

			if($status == 0){
				$str = explode("tmp",base64_decode($_product->getDealHasNew()));
				$from 	= strtotime($str[1]);
				$now 	= strtotime(Mage::app()->getLocale()->date());

				if($from > $now)
					$time = $str[1];

			}elseif($status == 1){
				if($_product->getSpecialToDate()){
					$to 	= strtotime($_product->getSpecialToDate());
					$now 	= strtotime(Mage::app()->getLocale()->date());

					if($to > $now)
						$time = $_product->getSpecialToDate();
				}
			}
		}

		return date_format(date_create($time), 'Y/m/d H:i:s');
	}
	
	public function getClock($_product)
	{
		$date = $this->getTimeClock($_product);
		$time = strtotime($date);
		$now 	= strtotime(Mage::app()->getLocale()->date());

		$html = '			<div class="show_clock clock_style_1">';
		$html .= '				<div class="deal_data" style="display:none">'.$this->html_encode($_product->getData()).'</div>';
		$html .= '				<div class="time" style="display:none">'.$date.'</div>';
		if($_product->getDealStatus() == 0){
			$str = explode("tmp",base64_decode($_product->getDealHasNew()));
			$html .=				'<div class="price-box"><p class="special-price"><span class="price-label">'.$this->__("Special Price :").'</span>&nbsp;'.'<span class="price">'.Mage::helper('core')->currency($str[0]).'</span></p></div>';
		}
		$html .= '				<ul class="clock">';
		if($time-$now >= 86400){
			$html .= '				<li>';
			$html .= '					<span class="days">00</span>';
			$html .= '					<p class="timeRefDays">days</p>';
			$html .= '				</li>';
		}
			$html .= '				<li>';
			$html .= '					<ul class="clock_sub">';
			$html .= '						<li>';
			$html .= '							<span class="hours">00</span>';
			$html .= '							<p class="timeRefHours">hours</p>';
			$html .= '						</li>';
			$html .= '						<li>';
			$html .= '							<span class="minutes">00</span>';
			$html .= '							<p class="timeRefMinutes">minutes</p>';
			$html .= '						</li>';
			$html .= '						<li>';
			$html .= '							<span class="seconds">00</span>';
			$html .= '							<p class="timeRefSeconds">seconds</p>';
			$html .= '						</li>';
			$html .= '					</ul>';
			$html .= '				</li>';
		$html .= '				</ul>';
		$html .= '			</div>';
	
		return $html;
	}

	public function getHtmlClock($data,$type=3){
		if($type == 'short') $type = 2;
		else	$type = 3;

		$deal = Mage::getModel('multidealpro/multidealpro')->getCollection();
		$deal->addFieldToFilter("product_id",$data->getId());

		$html = '';
		if($this->checkEnable() == 1){
			if($deal->getSize() > 0){
				$deal	=	$deal->getFirstItem();
				$deal	=	$this->checkDeal($deal);

				$where = 'multidealpro.product_id = '.$data->getId().' AND multidealpro.is_active = 1 ';
				$collection = Mage::getModel('multidealpro/multidealpro')->getDealCollection($where);

				$_product	=	$collection->getFirstItem();
				if($_product){
					$time = $this->getTimeClock($_product);
					if($time > 0){
						if($type == 2){
							$html .=	'<div>';
							$html .=		$this->getQtyleft($_product);
							$html .=		'<div class="deal_info" style="display:none">'.$this->getInfo($type).'</div>';
							$html .=		$this->getClock($_product);
							$html .=	'</div>';
						}elseif($type == 3){
							$html .=	'<div class="show_details">';
							if($_product->getDealStatus() == 0)
								$html .=		'<div class="title"><span style="display:none">'.$this->__("Get it before it's gone !").'</span><span class="qty_left soon">'.$this->__("Time left to buy !").'</span></div>';
							else
								$html .=		'<div class="title"><span>'.$this->__("Get it before it's gone !").'</span></div>';
							$html .=		'<div class="deal_info" style="display:none">'.$this->getInfo($type).'</div>';
							$html .=		$this->getClock($_product);
							$html .=	'</div>';
							if($_product->getDealStatus() == 0)
								$html .=	'<div class="deal_qty"></div>';
							else
								$html .=		$this->getQtyleft($_product);
							
						}
					}
				}
			}
		}
		return $html;
	}

	public function checkAllDeals(){
		$collection = Mage::getModel('multidealpro/multidealpro')->getCollection()->addFieldToFilter("is_active",1);
		foreach($collection as $item)
			$this->checkDeal($item);
	}

	public function checkDeal($deal){
		if($deal->getIsActive() == 1){
			$_product = Mage::getModel('catalog/product')->load($deal->getProductId());
			$tmp_status = 	$deal->getStatus();

			$from 	= strtotime($_product->getSpecialFromDate());
			$to 	= strtotime($_product->getSpecialToDate());
			$now 	= strtotime(Mage::app()->getLocale()->date());

			if(!$from or !$to ){
				if($tmp_status == 0){
					$str = base64_decode($deal->getHasNew());
					$plit = explode("tmp",$str);
					$from = strtotime($plit[1]);
					$to   = strtotime($plit[2]);
				}else return $deal;
			}

			if($from > $now ){	// queued
				$status	=	0;

				$has_new = base64_encode($_product->getSpecialPrice().'tmp'.$_product->getSpecialFromDate().'tmp'.$_product->getSpecialToDate());
				$has_end = "";
				$_product->setSpecialPrice("")
						->setSpecialFromDate("")
						->setSpecialToDate("");
			}elseif($from <= $now ){
				if($to > $now){	// running
					$status	=	1;

					if($deal->getHasNew() != ""){
						$plit = explode("tmp",base64_decode($deal->getHasNew()));
						$_product->setSpecialPrice($plit[0])
							->setSpecialFromDate($plit[1])
							->setSpecialFromDateIsFormated(true)
							->setSpecialToDate($plit[2])
							->setSpecialToDateIsFormated(true);
					}
					$has_new = "";
					$has_end = "";
				}else{	// end
					$status	=	2;

					$has_new = "";
					$has_end = base64_encode($_product->getSpecialPrice().'tmp'.$_product->getSpecialFromDate().'tmp'.$_product->getSpecialToDate());
					$_product->setSpecialPrice("")
							->setSpecialFromDate("")
							->setSpecialToDate("");
				}
			}

			if($tmp_status === "" or $status != $tmp_status){
				$resource = Mage::getSingleton('core/resource'); // Get the resource model
				$writeConnection = $resource->getConnection('core_write'); // Retrieve the write connection
				$table = $resource->getTableName('multidealpro/deal'); // Retrieve our table name

				$query = "UPDATE {$table} SET status = '".$status."'";
				$query .= ", has_new = '".$has_new."'";
				$query .= ", has_end = '".$has_end."'";
				$query .= " WHERE deal_id = ". (int)$deal->getId();

				$writeConnection->query($query); // Execute the query
				$_product->save();
				
				$deal->setHasNew($has_new);
				$deal->setHasEnd($has_end);
				$deal->setStatus($status);
			}
		}
		return $deal;
	}

	public function html_encode($data){
		if(!$data) return false;
		else{
			unset($data['stock_item']);
			unset($data['downloadable_links']);
			return base64_encode(serialize($data));
		}
	}

	public function html_decode($data){
		if(!$data) return false;
		else{
			return unserialize(base64_decode($data));
		}
	}

	public function getInfo($type=0,$label=0,$price=0,$cart=0,$addto=0){
		$data['type'] 	= $type;
		$data['label'] 	= $label;
		$data['price'] 	= $price;
		$data['cart'] 	= $cart;
		$data['addto'] 	= $addto;
		
		return json_encode($data);
	}

}