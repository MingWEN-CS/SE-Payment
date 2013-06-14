<?php
class OrderGoodsModel extends Model{

        public function searchbyname($OrderAction){
            	$keywords=$OrderAction->_get('keywords');
            	$keywordArray = split(" +", $keywords);
		for($i = 0; $i < count($keywordArray); $i++) {
			$keywordArray[$i] = '%' . $keywordArray[$i] . '%';
}
            $condition['name'] = array('like', $keywordArray, 'OR');
            $selectCause=$this->where($condition)->group('oid');;
		$result=$selectCause->select();
		return $result;
        }

        public function searchbyid($id){
            $condition['oid']=$id;
            return $this->where($condition)->select();
        }

}
