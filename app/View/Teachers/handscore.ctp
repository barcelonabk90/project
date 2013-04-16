<?php if (count($check)!=0)
{
	?>
<?php
echo 'Automatic scored:';
echo '<BR>';
foreach ($Scoreds as $scored) {
	# code...
	echo 'question'.$scored['testinfo']['questionID']." : ".$scored['testinfo']['question'];
	echo '<BR>';
	echo 'answer : '.$scored['testinfo']['answer'];
	echo '<BR>';
	echo 'grade : '.$scored['testinfo']['grade'];
	echo '<BR>';
}
?>

<?php
echo 'Hand score :';
echo '<BR>';
?>
<?php echo $this->Form->create('Testinfo'); ?>
	<fieldset>
<?php 
//echo "<pre>";
//header('Content-type: text/html; charset=utf-8');

foreach ($TestInfos as $testinfo) {
	# code...
	echo 'question '.$testinfo['testinfo']['questionID']." : ".$testinfo['testinfo']['question'];
	echo '<BR>';
	echo 'answer : '.$testinfo['testinfo']['answer'];
	echo '<BR>';
	echo $this->Form->input('grade',array('name' => $testinfo['testinfo']['questionID']));
	echo '<BR>';
}
?>
    </fieldset>
<?php echo $this->Form->end(__('Submit')); ?>

<?php }

else
echo "Sinh Vien chua lam bai" ;

?>

