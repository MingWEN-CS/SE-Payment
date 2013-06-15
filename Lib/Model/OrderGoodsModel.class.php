<?php
class OrderGoodsModel extends Model{

        public function searchbyname($constraint){
            $keywordArray = split(" +", $constraint['keywords']);

			for($i = 0; $i < count($keywordArray); $i++) {
				$keywordArray[$i] = '%' . $keywordArray[$i] . '%';
			}
            $condition['name'] = array('like', $keywordArray, 'OR');
            $condition['oid']=array('in',$constraint['userorders']);
            $selectCause=$this->where($condition)->group('oid');
			$result=$selectCause->select();
			return $result;
        }

        public function searchbyid($id){
            $condition['oid']=$id;
            return $this->where($condition)->select();
        }

		public function getOrderListbyName($constraint){
			$goodsList = $this->searchbyname($constraint);
			$result = array();
			for($i = 0; $i < count($goodsList); ++$i){
				$result[] = $goodsList[$i]['oid'];
			}
			return $result;
		}
}
