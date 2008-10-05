<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
<link rel="stylesheet" type="text/css" href="<?php echo $this->css ?>" />
<link rel="icon" href="share/kubuntu.png" type="image/png" />
<title><?php  echo $this->title; ?></title>
</head>
<body>
	<h1>Toto je defaultni sablona, vse pod timto nadpisem je generovane.</h1>
<?php $message = new Message();
	echo $message->show();
?>
	
	<div id="content">
		<?php $this->container('header'); ?>
	</div>
	<div id="left">
		<?php $this->container('left'); ?>
	</div>
</body>
</html>