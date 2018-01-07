<?php

// I left these like this because I don't know if I will need them in other places
// should be changed to more standard variable names if only needed here. Also 
// affects the postg_connection function below. - Gretchen 2015-10-24
define ("PGHOST", "localhost");
define ("PGDATABASE", "controlsdb");
define ("PGUSER", "btg_admin");
define ("PGPASSWORD", "m9894gapm");

function postg_connect()
{  
	return pg_connect("host=" . PGHOST . " dbname=" . PGDATABASE . " user=" . PGUSER . " password=" . PGPASSWORD);  
	 
}  

function postg_query($query, $connection_id)  
{  
  return pg_exec($connection_id, $query);  
}

$db = postg_connect();
	if  (!$db){	
		$output='Unable to connect to the database';
		echo $output; 	
		exit();
 	}

 	/* The web site http://www.phppostgresql.com/site/problems-with-charset-never-again/ and GDI's
	PHP-MYSQL pages make it sound like this is important, but don't know how to make it work for 
	my database */
	/*
	if (!pg_set_client_encoding($db,'UTF8')) {	
		$output = 'Unable to set database connection encoding.';	
		echo $output; 	
		exit();
	exit();
	}
	*/

	/* Probably need to add something to verify that the tables it's trying to connect
	to exist along these lines... (See GDI class documents for more info 
	https://girldevelopit.github.io/gdi-featured-php-mysql/class3-codesample.txt) */
	/*
	if (!mysqli_select_db($link, 'coffee')){	
		$output = 'Unable to locate the database.';	
		echo $output; 	
	exit();
	*/
?>