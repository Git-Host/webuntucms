<?php

class Bobr extends Object
{

    public function run()
    {
        $this->debug()->connectToDatabase()->getBobr();
    }

    /**
     * Spoji se z databzi.
     *
     * @return Bobr
     * @throws DibiDriverException
     */
    private function connectToDatabase()
    {
        // @todo odchytavat vyjimku pri nepovedenem spojeni.
        $config = new Config;
        $connect = dibi::connect(array(
            'driver'     => 'postgre',
            'string'     => ' host='	. $config->dbHost .
                            ' port=' 	. $config->dbPort .
                            ' dbname='	. $config->dbName .
                            ' user='	. $config->dbUser .
                            ' password='. $config->dbPassword . '',
            'persistent' => $config->dbPersistent,
            ), $config->dbConnectionName );
        return $this;
    }

    /**
     * Pokud je v Cofigu zapnuty debub mode zapne ladenku.
     *
     * @return Bobr
     */
    private function debug()
    {
        $config = new Config;
        if( TRUE === $config->debugMode ){
            Debug::enable( E_ALL | E_STRICT | E_NOTICE , FALSE );
        }
        return $this;
    }

    /**
     * Spusti bobra.
     *
     */
    private function getBobr()
    {
        // veskery odeslany obsah zacnem bufferovat
        ob_start();
        echo '<p>Tyto blahy se daji vypnout v configu. Jedna se o debugMode</p>';

        // Zvalidujem platnost Session
        new SessionValidator();
        $validator = new UserValidator();
        // Zvalidujem uzivatele v session
        if(FALSE === $validator->validate()){
            // Uzivatel nebyl validni nastavime anonymouse
            Session::getInstance()->user = new User(1);
            echo '<p>Nastavil jsem Admina.</p>';
        }else{
            $user = Session::getInstance()->user;
            echo '<p>Uzivatel <b>' . $user->nick .'</b> mel jiz vytvorenou session.</p>';
        }
        $user = Session::getInstance()->user;

        $webInstanceValidatdor = new WebInstanceValidator();
        if (TRUE === $webInstanceValidatdor->validate(Tools::getWebInstance())) {
            echo '<p>Uzivatel ma pristup na tuto web instanci</p>';
        } else {
            echo '<p>Uzivatel NEMA pristup na tuto web instanci</p>';
            // @todo presmerovavat nekam s nejakou hlaskou.
        }

        $process = new Process;
        print_re($process);
        // To co se do ted vypsalo vypisem pod html kodem.
        $errorOutput = ob_get_contents();
        ob_end_clean();

        if (0 < $process->pageId) {

            $description = DescriptionList::getInstance($process->getLang(), $process->getPageId());
            
            $pageBuilder = new PageBuilder($process->pageId);
            $pageBuilder->createPage($process->getCommand());
            echo $pageBuilder;
        } else {
            Messanger::addNote('Strasna chyba z nejakeho duvodu jsem nenasel pageID a proto nic neudelam. Oprav me prosiiim ;)');
        }
        

        echo $this->getErrorOutput($errorOutput);

    }

    /**
     * Pokud je v configu nastaven debugMode
     * vrati vse co bylo pred nactenim stranky vyhozeno do bufferu.
     *
     * @param string $errorOutput
     * @return string
     */
    private function getErrorOutput($errorOutput)
    {
        $output = '';
        $config = new Config;
        if( TRUE === $config->debugMode ){
            // @todo toto logovat
           $output .= "<div id=\"console-header\"><h1>BOBR rika:</h1></div>\n";
           $output .= "\n<div id=\"console\">\n";
           $output .= $errorOutput;
           $output .= "\n</div>\n";
        }

        return $output;
    }
}