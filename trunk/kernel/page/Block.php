<?php
/**
 * Description of Block
 *
 * @author rbas
 */
class Block extends DataObject
{

	/**
     * Unikatni id blocku
     *
     * @var integer
     */
    private $id = 0;

	/**
     * Prikaz pro modul.
     *
     * @var Command
     */
    private $command = NULL;

	/**
     * Popis blocku, slouzi k administraci.
     *
     * @var string
     */
    private $description = '';

    public function __construct($id = 0)
    {
        $importProperties = array('id' => 'id', 'command' => 'command', 'description' => 'description');
        $this->setImportProperties($importProperties);
        if (0 != $id) {
            $this->setId($id);
            $this->load();
        }
    }

    /**
     * Nacte block z cache nebo databaze.
     * Tento dotaz se nekesuje. Kesovani se provadi v objectu ContainerColection.
     *
     * @return Block
     * @throws InvalidArgumentException Pokud neni vyplnena vlastnost id.
     * @throws BlockException Pokud se nenejdou zadne data.
     */
    public function load()
    {
        if (0 > $this->id) {
            throw new InvalidArgumentException('Neni nastaveno id blocku, ktery se ma nacist.');
        }

        $query = 'SELECT `id`, `command`, `description` FROM `' . Config::DB_PREFIX . 'block` WHERE `id` = ' . $this->id . ' LIMIT 1';
        $record = dibi::query($query)->fetch();

        if (empty ($record)) {
            throw new BlockException('Block id ' . $this->id . ' neexistuje.');
        }

        // Naimportujem data a ulozime do kese.
        $this->importRecord($record);

        return $this;
    }

    public function getCacheId()
    {
        return '/kernel/page/' . $this->getClass() . '/' . $this->id;
    }
	/**
	 * Vrati hodnotu vlastnosti $id
	 *
	 * @return integer
	 */
	public function getId()
	{
		return $this->id;
	}

	/**
	 * Nastavi hodnotu vlastnosti $id
	 *
	 * @param integer
	 * @return Block
	 */
	public function setId($id)
	{
		$this->id = (integer)$id;
		return $this;
	}

	/**
	 * Vrati hodnotu vlastnosti $command
	 *
	 * @return Command
	 */
	public function getCommand()
	{
		return $this->command;
	}

	/**
	 * Nastavi hodnotu vlastnosti $command
	 *
	 * @param string
	 * @return Block
	 */
	public function setCommand($command)
	{
		$this->command = new Command($command);
		return $this;
	}

	/**
	 * Vrati hodnotu vlastnosti $description
	 *
	 * @return string
	 */
	public function getDescription()
	{
		return $this->description;
	}

	/**
	 * Nastavi hodnotu vlastnosti $description
	 *
	 * @param string
	 * @return Block
	 */
	public function setDescription($description)
	{
		$this->description = (string)$description;
		return $this;
	}

}