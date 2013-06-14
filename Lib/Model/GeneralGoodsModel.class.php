<?php
import("@.Util.Goods.SortField");
import("@.Util.Goods.SourcePlace");

class GeneralGoodsModel extends Model{
	protected $_validate = array(
		//array('USERNAME','require','Username is necessary',1),
		//array('USERNAME','','the username has been registered',1,'unique',1),
		//array('EMAIL','require','Email is necessary',1),
		//array('EMAIL','email','Email Format Error',1),	
		//array('PASSWD','require','Password is necessary',1),
	);
	
	protected $_auto = array(
		array('bought_count', '0'),
		array('score', '0'),
		array('score_count', '0'),
	);

	public function findGoodsWithId($id) {
		$condition['id'] = $id;
		return $this->where($condition)->find();
	}
	
	public function getGoodsWithPurchaseAction($purchaseAction) {
		$condition = $this->generateCondition($purchaseAction);
		$selectCause = $this->where($condition);
		if(($sort = $purchaseAction->_get('sort_field')) && ($sort != 'nothing')) {
			$availableSortArray = $this->getSortFieldArray();
			foreach($availableSortArray as $sortField) {
				if($sortField->field == $sort) {
					$selectCause = $selectCause->order($sort);
				}
			}
		}
		$result = $selectCause->select();
		return $result;		
	}
	
	static public function getDataName() {
		//本来想写general-goods的可是volist不认识。
		return 'general_goods';
	}
	
	static protected function generateCondition($purchaseAction) {
		$keywords = $purchaseAction->_get('keywords');
		$keywordArray = split(" +", $keywords);
		for($i = 0; $i < count($keywordArray); $i++) {
			$keywordArray[$i] = '%' . $keywordArray[$i] . '%';
		}
		$condition['name'] = array('like', $keywordArray, 'OR');
		$priceLowerBound = $purchaseAction->_get('price-lower-bound');
		$priceUpperBound = $purchaseAction->_get('price-upper-bound');
		//lower bound : upper bound or 0 : upper bound
		if($priceUpperBound == '0' || doubleval($priceUpperBound) > 0) {
			$condition['price'] = array('between',array(doubleval($priceLowerBound), doubleval($priceUpperBound)));
		}
		//lower bound : infinite
		else if($priceLowerBound == '0' || doubleval($priceLowerBound) > 0) {
			$condition['price'] = array('egt', doubleval($priceLowerBound));
		}
		//due to airplane has no place field.
		if(($place = $purchaseAction->_get('place')) && $place != "anyplace") {
			$condition['place'] = $place;
		}
		return $condition;
	}
	
   	static public function getSortFieldArray() {
		return array(new SortField('sort by nothing -', 'nothing'),
		new SortField('sort by price ↑', 'price asc'),
		new SortField('sort by sales ↓', 'bought_count desc'),
		new SortField('sort by score ↓', 'score desc'));
	}
	
	static public function getSourcePlaceObjectsArray() {
		$arrayContent = SourcePlace::getSourcePlaceObjectsArray();
		$arrayContent = array_merge(array(new SourcePlace("source place", "anyplace")), $arrayContent);
		return $arrayContent;
	}
	
}
?>