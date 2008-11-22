<?php
if (isset($_GET['count'])) {
	$count = $_GET['count'];
}else{
	$count = 15;
}
?>

<form method="get" action="">
	<fieldset>
		<legend>Zakladni info o objektu</legend>
		<table>
			<tbody>
				<tr>
					<th>Nazev:</th>
					<td><input type="text" name="className" value="<?php echo @$_GET['className'] ?>"></td>
				</tr>
				<tr>
					<th>Rozsiruje tridu:</th>
					<td><input type="text" name="classExtends" value="<?php echo @$_GET['classExtends'] ?>"></td>
				</tr>
				<tr>
					<th>Implementuje rozhrani:</th>
					<td><input type="text" name="classImplements" value="<?php echo @$_GET['classImplements'] ?>"></td>
				</tr>
			</tbody>
		</table>
		<table>
			<tbody>
				<tr><th>Popis tridy</th></tr>
				<tr>
					<td>
						<textarea name="classDescription" cols="55" rows="8"><?php echo @$_GET['classDescription'] ?></textarea>
					</td>
				</tr>
			</tbody>
		</table>
	</fieldset>
	<fieldset>
		<legend>Vlastnosti objektu</legend>
		<table>
			<thead>
				<tr>
					<th>Jmeno promene</th>
					<th>Datovy typ</th>
				</tr>
			</thead>
			<tbody>
				<?php
					for($i=0;$i <= $count; $i++){
						echo '
						<tr>
							<td><input type="text" name="propertyName[]" value="' . @$_GET['propertyName'][$i] . '"></td>
							<td><input type="text" name="propertyType[]" value="' . @$_GET['propertyType'][$i] . '"></td>
						</tr>
						';
					}
				?>
			</tbody>
		</table>
	</fieldset>
	<fieldset>
		<legend>Vytvorit</legend>
		<input type="submit" value="Vytvorit">
	</fieldset>
</form>

<pre>
<?php
require_once 'classgenerator.php';

$class = new ClassGenerator($_GET);
echo $class;