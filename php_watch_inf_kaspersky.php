<?php 
function fFicheros(){
	$source = "/mnt/inf_kaspersky/";
	$destination = "/root/watch/inf_kaspersky/input/";	
	$files = glob($source."*");
	foreach($files as $filename){
    		//Use the is_file function to make sure that it is not a directory.
    		if(is_file($filename)){
        		echo $filename."\n";
        		$des=str_replace($source,$destination,$filename);
        		echo $des."\n";
				if (copy($filename, $des)) {
    				unlink($filename);      
    				  		 
    				}   
			}
		}
	}
$status=TRUE;

do { 

   fFicheros(); // Call your function
   sleep(2);   //wait for 5 sec for next function call

   //you can set $status as FALSE if you want get out of this loop.

   //if(somecondition){
   //    $status=FALSE:
   //}

} while($status==TRUE); //loop will run infinite

?>