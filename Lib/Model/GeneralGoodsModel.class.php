<?php
class GeneralGoodsModel extends Model{
	public function getGoodsWithKeyWords($keywords) {
		$keywordArray = split(" +", $keywords);
		for($i = 0; $i < count($keywordArray); $i++) {
			$keywordArray[$i] = '%' . $keywordArray[$i] . '%';
		}
		$condition['name'] = array('like', $keywordArray, 'OR');
		$result =  $this->where($condition)->select();
		return $result;
	}
}
?>