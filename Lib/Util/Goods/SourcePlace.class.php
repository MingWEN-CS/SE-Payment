<?php
class SourcePlace{
	static public function getSourcePlacesArray(){
		return array(
			'山东',
			'山西',
			'河南',
			'河北',
			'湖南',
			'湖北',
			'广东',
			'广西',
			'黑龙江',
			'辽宁',
			'浙江',
			'安徽',
			'江苏',
			'福建',
			'甘肃',
			'江西',
			'云南',
			'贵州',
			'四川',
			'青海',
			'陕西',
			'吉林',
			'宁夏',
			'海南',
			'西藏',
			'内蒙古',
			'新疆',
			'台湾',
			'北京',
			'天津',
			'上海',
			'重庆',
			'香港',
			'澳门'
		);
	}
	
	static public function getSourcePlaceObjectsArray(){
		$places = SourcePlace::getSourcePlacesArray();
		$objects = array();
		foreach($places as $place) {
			array_push($objects, new SourcePlace($place, $place));
		}
		return $objects;
	}
	
	var $display;
	var $value;
	function SourcePlace($display, $value) {
		$this->display = $display;
		$this->value = $value;
	}
}
?>
