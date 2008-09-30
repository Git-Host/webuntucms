<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
<link rel="stylesheet" type="text/css" href="<?php echo $this->css ?>" />
<title><?php  echo $this->title; ?></title>
</head>
<body>
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
</body>
</html>