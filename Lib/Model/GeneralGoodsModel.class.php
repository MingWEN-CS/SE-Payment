<?php
import("@.Util.Goods.SortField");

class GeneralGoodsModel extends Model{
	public function findGoodsWithId($id) {
		$condition['id'] = $id;
		return $this->where($condition)->find();
	}
	
	protected function generateCondition($purchaseAction) {
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
		return $condition;
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
	
	public function getDataName() {
		//本来想写general-goods的可是volist不认识。
		return 'general_goods';
	}
	
   	static public function getSortFieldArray() {
		return array(new SortField('sort by nothing -', 'nothing'),
		new SortField('sort by price ↑', 'price asc'),
		new SortField('sort by sales ↓', 'bought_count desc'),
		new SortField('sort by score ↓', 'score desc'));
	}
}
?>