<?php
import("@.Util.Search.SortField");

class GeneralGoodsModel extends Model{
	public function findGoodsWithId($id) {
		$condition['id'] = $id;
		return $this->where($condition)->find();
	}
	
	// public function getGoodsWithKeyWords($keywords) {
	// 	$keywordArray = split(" +", $keywords);
	// 	for($i = 0; $i < count($keywordArray); $i++) {
	// 		$keywordArray[$i] = '%' . $keywordArray[$i] . '%';
	// 	}
	// 	$condition['name'] = array('like', $keywordArray, 'OR');
	// 	$result =  $this->where($condition)->select();
	// 	return $result;
	// }
	
	public function getGoodsWithPurchaseAction($purchaseAction) {
		$keywords = $purchaseAction->_get('keywords');
		$keywordArray = split(" +", $keywords);
		for($i = 0; $i < count($keywordArray); $i++) {
			$keywordArray[$i] = '%' . $keywordArray[$i] . '%';
		}
		$condition['name'] = array('like', $keywordArray, 'OR');
		if(($sort = $purchaseAction->_get('sort_field')) && ($sort != 'nothing')) {
			$availableSortArray = $this->getSortFieldArray();
			foreach($availableSortArray as $sortField) {
				if($sortField->field == $sort) {
					//TODO
				}
			}
		}
		else {
		}
		// $condition[]
		$result =  $this->where($condition)->select();
		return $result;		
	}
	
	public function getDataName() {
		//本来想写general-goods的可是volist不认识。
		return 'general_goods';
	}
	
   	static public function getSortFieldArray() {
		return array(new SortField('sort by nothing -', 'nothing'),
		new SortField('sort by price ↑', 'price'),
		new SortField('sort by sales ↓', 'sales'),
		new SortField('sort by score ↓', 'score'));
	}
}
?>