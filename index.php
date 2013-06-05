<?php
	/*
		-	Wordpress table update for Bitemark Tech
		-	Author: 	Aadil Prabhakar
		-	Email:		aadilprabhakar@tdn.pw
	*/
	
	if(!empty( $_POST )):                        
	

	
	//*/	VARIABLES
		$host		=	$_POST['host'];
		$user		=	$_POST['username'];
		$pass		=	$_POST['password'];
		$db			=	$_POST['database'];
		$prefix		=	$_POST['prefix'];
		
		$old_root	=	$_POST['old_root'];
		$new_root	=	$_POST['new_root'];
		
		$old_link	=	$_POST['old_link'];
		$new_link	=	$_POST['new_link'];
				
		$table['options']	=	'`'.$db.'`.`'.$prefix.'options`';
		$table['posts']	=	'`'.$db.'`.`'.$prefix.'posts`';
		$table['post_meta']	=	'`'.$db.'`.`'.$prefix.'postmeta`';		
			
		$connection	=	NULL;	
			
		try{
			$connection	=	mysql_connect	( $host, $user, $pass, $db) or die(':: Error Connecting to Database');
			mysql_select_db	( $db, $connection ) or die (':: Error selecting database ');
		} catch (Exception $E) {
			echo $E;
			die('Please correct the connection parameters!');
		}

	//*/ 	QUERIES
		$q		=	array();
		$q[]	=	"UPDATE ". $table['options'] ." SET option_value = replace(option_value, '". $old_link ."', '". $new_link ."') WHERE option_name = 'home' OR option_name = 'siteurl';";
		$q[]	=	"UPDATE ". $table['posts'] ." SET guid = REPLACE (guid, '". $old_link ."', '". $new_link ."');";
		$q[]	=	"UPDATE ". $table['posts'] ." SET post_content = REPLACE (post_content, '". $old_link ."', '". $new_link ."');";
		$q[]	=	"UPDATE ". $table['posts'] ." SET post_content = REPLACE (post_content, 'src=\"". $old_link ."', 'src=\"". $new_link ."');";
		$q[]	=	"UPDATE ". $table['posts'] ." SET guid = REPLACE (guid, '". $old_link ."', '". $new_link ."') WHERE post_type = 'attachment';";
		$q[]	=	"UPDATE ". $table['post_meta'] ." SET meta_value = REPLACE (meta_value, '". $old_link ."', '". $new_link ."');";
		
		foreach( $q as $query ):
			$r		=	mysql_query( $query, $connection ); //or echo( mysql_error() . ' -=> ' . $query );
			$aff	=	(mysql_affected_rows( $connection ) > 0) ? mysql_affected_rows( $connection ) : 0 ;
			print $query . '(';
			$i	= 	0;
			while( $i < $aff ):
				print '|';
				$i++;
			endwhile;
		
			print $aff . ' entries) <br>';			
		endforeach;
	//*//
	
	endif;
?>
<form action="<?=$_SERVER['PHP_SELF'];?>" method="post">
Host : <input type="text" name="host" placeholder="hostname" value="<?php if(isset( $_POST['hostname'])): echo $_POST['hostname']; endif; ?>" /><br>
Database : <input type="text" name="database" placeholder="database" value="<?php if(isset( $_POST['database'])): echo $_POST['database']; endif; ?>" /><br>
Username : <input type="text" name="username" placeholder="username" value="<?php if(isset( $_POST['username'])): echo $_POST['username']; endif; ?>" /><br>
Password : <input type="text" name="password" placeholder="password" value="<?php if(isset( $_POST['password'])): echo $_POST['password']; endif; ?>" /><br>
Table Prefix : <input type="text" name="prefix" placeholder="Table Prefix" value="<?php if(isset( $_POST['prefix'])): echo $_POST['prefix']; endif; ?>" /><br>
Old root : <input type="text" name="old_root" placeholder="/home/<old_root>/" value="<?php if(isset( $_POST['old_root'])): echo $_POST['old_root']; endif; ?>" /><br>
New root : <input type="text" name="new_root" placeholder="/hone/<new_root>/" value="<?php if(isset( $_POST['new_root'])): echo $_POST['new_root']; endif; ?>" /><br>
Old URL : <input type="text" name="old_link" placeholder="http://old_site.com" value="<?php if(isset( $_POST['old_link'])): echo $_POST['old_link']; endif; ?>" /><br>
New URL : <input type="text" name="new_link" placeholder="http://new_site.com" value="<?php if(isset( $_POST['new_link'])): echo $_POST['new_link']; endif; ?>" /><br>
<input type="submit" name="submit" value="Submit" />
</form>