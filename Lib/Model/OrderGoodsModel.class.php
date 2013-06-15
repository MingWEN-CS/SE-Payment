<?php
class OrderGoodsModel extends Model{

        public function searchbyname($constraint){
            $keywordArray = split(" +", $constraint['keywords']);

		for($i = 0; $i < count($keywordArray); $i++) {
			$keywordArray[$i] = '%' . $keywordArray[$i] . '%';
}
            $condition['NAME'] = array('like', $keywordArray, 'OR');
            $condition['OID']=array('in',$constraint['userorders']);
            $selectCause=$this->where($condition)->group('OID');;
		$result=$selectCause->select();
		return $result;
        }

        public function searchbyid($id){
            $condition['OID']=$id;
            return $this->where($condition)->select();
        }

}
