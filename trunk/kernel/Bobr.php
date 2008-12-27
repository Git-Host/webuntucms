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
            ini_set('xdebug.extended_info', 0);
            ini_set('xdebug.auto_trace', 1);
            ini_set('xdebug.trace_output_dir', '/local/xdebug/');
            ini_set('xdebug.collect_includes' , 1);
            ini_set('xdebug.profiler_enable', 1);
            ini_set('xdebug.collect_params', 4);
            ini_set('xdebug.show_mem_delta', 'On');
            //xdebug_start_trace();
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

        $this->setUser();
                
        try {
            // Vytvorime si zaklad z url.
            $process = new Process;
            print_Re($process);

            if (0 < $process->getPageId()) {
                $page = new Page($process->pageId);

                // Nastavime jazyk pro popiskovac a dame mu i informaci o pageId kvuli cachovani
                $description = DescriptionList::getInstance($process->getLang(), $process->getPageId());

                // Nastavime jazyk generatoru linku
                LinkCreator::setLang($process->getLang());

                // To co se do ted vypsalo vypisem pod html kodem.
                $errorOutput = ob_get_contents();
                ob_end_clean();

                $config = new Config;
                
                $template = Template::getInstance();
                $template->setContainerColection($page->getContainerColection())
                    ->setCommand($process->getCommand());
                Template::add('webTitle', $config->webTitle);
                Template::add('title', 'Vitej');
                Template::addCss($page->getCss());
                Template::addCss('themes/default/css/console.css');
                $template->load(__WEB_ROOT__ . $config->share . $page->getTemplate(), FALSE);

                echo $template;

                
            } else {
                echo 'Z nejakeho duvodu se nepovedlo nacist stranku.';
            }
            
        } catch (PageException $e) {
            // Nemuze se vytvorit stranka, vyhodime nejvissi vyjimku.
            throw new BobrException($e->getMessage());
        } catch (TemplateExceptino $e) {
            throw new BobrException($e->getMessage());
        }
        

        if (FALSE) {
            // Vytvorime si stranku
            $pageBuilder = new PageBuilder($process->pageId);
            $pageBuilder->createPage($process->getCommand());
            
            echo $pageBuilder;
        } else {
            Messanger::addNote('Strasna chyba z nejakeho duvodu jsem nenasel pageID a proto nic neudelam. Oprav me prosiiim ;)');
        }

        
        echo $this->getErrorOutput($errorOutput);

    }

    /**
     * Zvaliduje session a nastavi uzivatele.
     *
     * @return Bobr
     */
    private function setUser()
    {
        // Zvalidujem platnost Session
        new SessionValidator();
        $validator = new UserValidator();
        // Zvalidujem uzivatele v session
        if(FALSE === $validator->validate()){
            // Uzivatel nebyl validni nastavime anonymouse
            $user = Session::getInstance()->user = new User(2);
            echo '<p>Nastavil jsem <b>' . $user->nick .'</b>.</p>';
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
        return $this;
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