	<h1>Jsi v administraci tak si toho cen!!!</h1>
	<div id="message">
		<?php $message = new Message();	echo $message->show(); ?>
	</div>
	<div id="header">
		<?php $this->container('header'); ?>
	</div>
	<div id="left">
		<?php $this->container('left'); ?>
	</div>
	<div id="right">
		<?php $this->container('right'); ?>
	</div>
	<div id="content">
		<?php $this->container('center'); ?>
	</div>
	<div id="footer">
		<?php $this->container('footer'); ?>
	</div>