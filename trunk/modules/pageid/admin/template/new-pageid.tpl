<div title="<?php echo $this->description['description'] ?>">
	<form action="" method="post" name="pageIdForm">
		<fieldset>
			<legend><?php echo $this->description['title'] ?></legend>
			<table>
				<tbody>
					<tr>
						<th><label>Name</label>:</th>
						<td>
							<input type="text" name="name"/>
						</td>
					</tr>
					<tr>
						<th><label>Description</label>:</th>
						<td>
							<input type="text" name="description"/>
						</td>
					</tr>
					<tr>
						<th><label>Blocks</label>:</th>
						<td>
							<input type="text" name="blocks_id"/> ( tady bude strasnej BobrJS porek )
						</td>
					</tr>
					<tr>
						<th><label>Node</label></th>
						<td>
							<input type="text" name="node" /> ( node se bude vytvare primo odsud pomoci AJAXu )
						</td>
					</tr>
					<tr>
						<td>
							<input type="submit" id="submitLogin" class="button login" value="Ulozit"/>
						</td>
					</tr>
				</tbody>
			</table>
		</fieldset>
	</form>
</div>