<?php
/**
 * Obsahuje ve vlastnosti $item pole objectu Block indexovanych podle jejich ID.
 *
 * @author rbas
 */
class BlocksList extends Object
{
	/**
     * Obsahuje pole objectu Block
     *
     * @var array
     */
    private $items = array();

	/**
     * Naimportuje blocky na ktere ma dany uzivatel pravo.
     *
     * @param array $blocks 
     */
    public function importBlocks(array $blocks)
	{
		$commandValidator = new CommandValidator;
		foreach ($blocks as $id => $block) {
            // Zjistime jestli ma uzivatel pravo na tento block.
			if(TRUE === $commandValidator->validate($block['command'])){
				// Vytvorime novy Block.
                $this->items[$id] = new Block;
                // Naimportujem do nej data.
				$this->items[$id]->importRecord($block);
			} else {
                print_re($block);
                echo 'Na tento block nema uzivatel pravo.';
            }
			// @todo udelat nejake logovani zakazanych blocku v debug modu bo tak nejak
		}
	}

    public function getItems()
    {
        return $this->items;
    }
}