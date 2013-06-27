<?php
class OrderGoodsModel extends Model{

        public function searchbyname($constraint){
            $keywordArray = split(" +", $constraint['keywords']);

			for($i = 0; $i < count($keywordArray); $i++) {
				$keywordArray[$i] = '%' . $keywordArray[$i] . '%';
			}
            $condition['NAME'] = array('LIKE', $keywordArray, 'OR');
            $condition['OID']=array('IN',$constraint['userorders']);
            
            if($constraint!=null)
            $selectCause=$this->order('OID DESC')->where($condition)->group('OID');
			$result=$selectCause->select();
			return $result;
        }

        public function searchbyid($id){
            $condition['OID']=$id;
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
        public function insertnewgood($good){
            $this->create($good);
            return $this->add();
        }
}
