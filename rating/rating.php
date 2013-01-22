<?php
class rating{
	public 	$id, $query, $result, $insert_result, $sql,
			$total_votes, $total_value, $used_ips, $numbers,
			$current_rating, $rating;
	private $ip, $rating_unit, $rating_dec;

	
	function __construct($aid){
		$this->id = $aid;
//			//set some variables
		$this->ip = $_SERVER['REMOTE_ADDR'];
////		if (!$units) {$units = 10;}
////		if (!$static) {$static = FALSE;}
		
//		// get votes, values, ips for the current rating bar
		$this->result=mysql_query("SELECT total_votes, total_value, used_ips FROM ratings WHERE id='$this->id' ")or die(" Error: ".mysql_error());
//		
//		
//		// insert the id in the DB if it doesn't exist already
//		// see: http://www.masugadesign.com/the-lab/scripts/unobtrusive-ajax-star-rating-bar/#comment-121

		if (mysql_num_rows($this->result) == 0) {
			$this->sql = "INSERT INTO ratings (`id`,`total_votes`, `total_value`, `used_ips`) VALUES ('$this->id', '0', '0', '')";
			$this->insert_result = mysql_query($this->sql);
			// since there's no previous record of this id then we set this vars to 0
			$this->numbers['total_votes'] = 0;
			$this->numbers['total_value'] = 0;
		}else{
			$this->numbers=mysql_fetch_assoc($this->result);
		}
	}
	
	public function get_total_votes(){ //how many votes total
		if ($this->numbers['total_votes'] < 1) {
			return 0;
		} else {
			return $this->numbers['total_votes'];
		}
	}
	
	public function get_rating(){//total number of rating added together and stored
		$this->current_rating = $this->numbers['total_value']; //total number of rating added together and stored
		if ($this->numbers['total_votes'] < 1) {
			return 0;
		} else {
			$this->rating = number_format($this->current_rating/$this->numbers['total_votes'],1);
			//now we have to deliver the decimal in either as 0 or 5
			list($this->rating_unit,$this->rating_dec) = split('.',$this->rating);
			if($this->rating_dec > 0 && $this->rating_dec<=5){
				$this->rating_dec = 5;
			}else{
				$this->rating_dec = 0;
			}
			$this->rating_unit = number_format($this->current_rating/$this->numbers['total_votes'],0);
			return $this->rating_unit.'.'.$this->rating_dec;

		}
		//return number_format($current_rating/$this->get_total_votes(),1);
	}
}
?>