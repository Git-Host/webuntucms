<?php

class Kernel_Bobr extends Object
{

    public function run()
    {
        $this->debug()->connectToDatabase()->getBobr();
    }

    /**
     * Spoji se z databzi.
     *
     * @return Kernel_Bobr
     * @throws DibiDriverException
     */
    private function connectToDatabase()
    {
        // @todo odchytavat vyjimku pri nepovedenem spojeni.
        $config = new Kernel_Config_Config;
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
     * @return Kernel_Bobr
     */
    private function debug()
    {
        $config = new Kernel_Config_Config;
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
            $process = new Kernel_Process;
            print_Re($process);

            if (0 < $process->getPageId()) {
                $page = new Kernel_Page_Page($process->pageId);

                // Nastavime jazyk pro popiskovac a dame mu i informaci o pageId kvuli cachovani
                $description = Kernel_DescriptionList::getInstance($process->getLang(), $process->getPageId());

                // Nastavime jazyk generatoru linku
                Lib_LinkCreator::setLang($process->getLang());

                // To co se do ted vypsalo vypisem pod html kodem.
                $errorOutput = ob_get_contents();
                ob_end_clean();

                $config = new Kernel_Config_Config;
                
                $template = Kernel_Page_Template::getInstance();
                $template->setContainerColection($page->getContainerColection())
                    ->setCommand($process->getCommand());
                Kernel_Page_Template::add('title', 'Vitej');
                $template->addCssLink($page->getCss());
                $template->load(__WEB_ROOT__ . $config->share . $page->getTemplate(), FALSE);

                echo $template;

                
            } else {
                throw new Kernel_BobrException('Z nejakeho duvodu se nepovedlo nacist stranku.');
            }
            
        } catch (Kernel_Page_PageException $e) {
            // Nemuze se vytvorit stranka, vyhodime nejvissi vyjimku.
            throw new Kernel_BobrException($e->getMessage());
        } catch (Kernel_Page_TemplateException $e) {
            throw new Kernel_BobrException($e->getMessage());
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
        new Kernel_SessionValidator();
        $validator = new Kernel_User_UserValidator();
        // Zvalidujem uzivatele v session
        if(FALSE === $validator->validate()){
            // Uzivatel nebyl validni nastavime anonymouse
            $user = Kernel_Session::getInstance()->user = new Kernel_User_User(2);
            echo '<p>Nastavil jsem <b>' . $user->nick .'</b>.</p>';
        }else{
            $user = Kernel_Session::getInstance()->user;
            echo '<p>Uzivatel <b>' . $user->nick .'</b> mel jiz vytvorenou session.</p>';
        }
        $user = Kernel_Session::getInstance()->user;

        $webInstanceValidatdor = new Kernel_WebInstanceValidator();
        if (TRUE === $webInstanceValidatdor->validate(Lib_Tools::getWebInstance())) {
            echo '<p>Uzivatel ma pristup na tuto web instanci</p>';
        } else {
            Lib_Messanger::addError('Nemate pristup na tuto stranku.');
            //@todo tato hlaska se pri presmerovani vymaze!!
            Kernel_Request_HttpRequest::redirect('/');
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
        $config = new Kernel_Config_Config;
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