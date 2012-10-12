<?php
include("dataAccess/ActivityDataAccess.inc");

//Class to hold and Perform  Activities
class Activity {

	public $idActivity;
	public $name;
	public $rating;
	public $requirements;
	public $image;
	public $start_date;
	public $end_date;
	public $status;
	public $description;
	public $list;
	
	function PrepareActivity(){
		
		$activityArray = ActivityDataAccess::GetActivities();
		if(isset($activityArray)){
		
			foreach($activityArray as $activity){
				
				$itemActivity  = new Activity;// Create aActivity object
				$itemActivity->idActivity = $activity["idActivity"]; 
				$itemActivity->name = $activity["name"];
				$itemActivity->rating = $activity["rating"];  
				$itemActivity->requirements = $activity["requirements"];  
				$itemActivity->image = $activity["image"];  
				$itemActivity->start_date = $activity["start_date"];
				$itemActivity->end_date = $activity["end_date"]; 
				$itemActivity->status = $activity["status"];  
				$itemActivity->description = $activity["description"]; 
				
				$this->list[] = $itemActivity;// add to array
			}
		}
	
	}
}

?>

