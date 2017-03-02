<?php
//Function for processing data generated by an LTI consumer 
//Should accept both roster based section information using "lis_course_offering_sourcedid" and a course name parse

//Accepts an LTI data string generated by a LTI consumer
//Return both the 4 and 8 digit codes for the class
function translateLtiToBrock($lis_course_offering_sourcedid, $title){
	
	//----------First try using the roster approach----------//
	
	//Check if lis_course_offering_sourcedid or the custom custom_sourcedid are set	
	if(isset($lis_course_offering_sourcedid)){

		//First replace the colons with dashes
		$lis_course_offering_sourcedid = str_replace(":", "-", $lis_course_offering_sourcedid);
		
		//We need to account for multiple sections which are deliniated by a plus +
		$sourcedidArray = explode("+", $lis_course_offering_sourcedid);
		
		//Lets run through the sections
		foreach($sourcedidArray as $sourcedid){
		
			//Now lets make an array for each section exploiding on dashes
			$allSections[] = explode("-", $sourcedid);
				
			//The order of the array should be, assuming (2016-FW-D03:PCUL-3P21:S01-SEM-SS04)
			//Year(2016) - Session(FW) - Duration(D02) - Subject(PCUL) - Code(3P21) - Section(S01) - Type(SEM) - Secondary Section(SS04)		
		}
		
		//Which section are we using?
		//This needs work, allow user to pick which section will be default
		$defaultSection = 0;
		
		//Generate the four and eight code
		$fourCode = $allSections[$defaultSection][3];
		$eightCode = $allSections[$defaultSection][3].$allSections[$defaultSection][4];
		
		//Set status of sourcedid
		$status = "sourcedid";
	}
	//----------Fallback to name parsing----------//
	else{
		//Check if there is a dash (signifying multiple courses) and grab the four digit code
		$dashLoc = strrpos($title, "-");
		//If it does have a dash add 1 to start at the next character
		if ($dashLoc !== 0){ $dashLoc++;}
		//Substring out the course name and add a space
		$fourCode = substr($title, 0, 4);
		$eightCode = $fourCode.substr($title, ($dashLoc+4), 4);	
		
		//Set status course name
		$status = "course name";
	}
	
	//Send the four and eight code representations back
	return array($fourCode, $eightCode, $status);	
}