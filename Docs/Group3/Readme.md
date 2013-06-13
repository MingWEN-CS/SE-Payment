* goods 数据库本来只有id 但是这样php的add方法插不了，所以加了个type。
type的含义：  
	1: general goods  
	2: hotel rooms
	3: airplane ticket

* 地名是中文，只有省份。

* 按照<pre>[{good_id: 1, good_count: 3}, {good_id: 2, good_count: 4}]</pre>的形式用good_pairs的名字post出去