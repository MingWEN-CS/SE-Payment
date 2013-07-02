<?php
class OrderGoodsModel extends Model{

        public function searchbyname($constraint){
            $keywordArray = split(" +", $constraint['keywords']);
            
			for($i = 0; $i < count($keywordArray); $i++) {
                if(count($keywordArray)==1||$keywordArray[$i]!='')
				$keywordArray[$i] = '%' . $keywordArray[$i] . '%';
			}
            $condition['OID']=array('IN',$constraint['userorders']);
            
            $condition['NAME'] = array('LIKE', $keywordArray, 'OR');
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
        public function changestate($oid,$newstate){
            $condition['OID']=$oid;
            $data['STATE']=$newstate;
            return $this->where($condition)->save($data);
            
        }
}
