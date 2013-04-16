<?php
/**
 *
 * PHP 5
 *
 * CakePHP(tm) : Rapid Development Framework (http://cakephp.org)
 * Copyright (c) Cake Software Foundation, Inc. (http://cakefoundation.org)
 *
 * Licensed under The MIT License
 * For full copyright and license information, please see the LICENSE.txt
 * Redistributions of files must retain the above copyright notice.
 *
 * @copyright     Copyright (c) Cake Software Foundation, Inc. (http://cakefoundation.org)
 * @link          http://cakephp.org CakePHP(tm) Project
 * @package       app.View.Layouts
 * @since         CakePHP(tm) v 0.10.0.1076
 * @license       MIT License (http://www.opensource.org/licenses/mit-license.php)
 */

?>

<html>
<head>
  <title><?php echo $title_for_layout; ?></title>

	<?php
		echo $this->Html->meta('icon');
		echo $this->Html->script('jquery-1.8.3.min');

		echo $this->Html->css('cake.generic');
		echo $this->Html->css('bootstrap');
		echo $this->Html->css('bootstrap.min');
		echo $this->Html->css('bootstrap-responsive');
		echo $this->Html->css('bootstrap-responsive.min');
		echo $this->Html->script('bootstrap'); 
		echo $this->Html->script('bootstrap.min'); 

	
		echo $this->fetch('meta');
		//echo $this->fetch('css');
		echo $this->fetch('script');
                //echo $this->Html->css('cake.generic');

                echo $this->Html->script('jquery');
	?>

</head>
<body>
	
	<?php include_once 'header.ctp'; ?>	
	<div class="container">
		<?php $flash = $this->Session->flash(); if ($flash) {?>
	<div class="alert">
	<?php echo $flash; ?>
	</div>
	<?php }?>
	
 	<?php echo $this->fetch('content'); ?>

	<?php include_once 'footer.ctp'; ?>	
	<?php echo $this->element('sql_dump'); ?>
</div>
</body>
</html>
