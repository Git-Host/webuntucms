<?php
final class CreatePage extends Object
{
	
			// Objekty
	private $process,
			$block,
			$moduleDelegator;
		
	public 	$css,
			$title,
			$meta;
			
	protected $blockList;
	// Prijmame jen potomky ProcessWebu
	public function __construct( ProcessWeb $process )
	{
		$this->process = $process;
		$this->init();
	}
	
	private function init()
	{
		$this->block = Block::getSingleton();
		$this->moduleDelegator = ModuleDelegator::getSingleton( $this->process->webInstace, $this->process->command );

		// @todo lepe kontolovat cestu
		$fileName = __DIR__ . '/' . $this->process->pageTemplate;
		if( file_exists( $fileName ) ){
			$this->setPageHead();
			require_once $fileName;
		}else{
			throw new CreatePageException ('Nepodarilo se vlozit template: <b>' . $this->process->pageTemplate . '</b>');
		}
		
	}
	
	private function container( $container )
	{
		$blocks = $this->getBlockList();
		if( array_key_exists( $container, $blocks ) ){
			foreach( $blocks[$container] as $block ){
				$this->moduleDelegator->loadModule( $block );
			}
		}
	}
	
	private function setPageHead()
	{
		$this->css = BobrConf::SHARE_URL . $this->process->pageCss;
		$this->title = 'Defaultni titulek, zaridit aby se generoval!!!';
	}
	
	protected function getBlockList()
	{
		if( NULL === $this->blockList ){
			return $this->setBlockList();
		}else{
			return $this->blockList;
		}
	}
	
	protected function setBlockList()
	{
		return $this->blockList = $this->block->loadBlockById( $this->process->pageBlockIds );
	}
}