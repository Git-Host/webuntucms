<?php
class BlocksList extends Object
{
	private $items = array();

	public function importBlocks(array $blocks)
	{
		$commandValidator = new CommandValidator;
		foreach ($blocks as $id => $block) {
			if(TRUE === $commandValidator->validate($block['command'])){
				$this->items[$id] = new Block;
				$this->items[$id]->importRecord($block);
			}
			// @todo udelat nejake logovani zakazanych blocku v debug modu bo tak nejak
		}
	}
}
?>