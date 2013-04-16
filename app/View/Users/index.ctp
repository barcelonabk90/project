 <?php  
 if(AuthComponent::user('username'))
 {
 	echo "Hello ".AuthComponent::user('username')." what do u want?";
 }
else
{

	echo 'you have not logged in';
}

?>

