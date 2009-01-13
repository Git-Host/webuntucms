<?php
/**
 * Description of Messanger
 *
 * @author rbas
 */
class Lib_Messanger
{

    /**
     * Vlastni instance ulozena v session.
     *
     * @var Messanger
     */
    private static $instance = NULL;

    /**
     * Poznamky pro uzivatele.
     * Pouzivaji se pro oznameni nejake akce.
     *
     * @var array
     */
    private $notes = array();
    
    /**
     * Chyby na ktere chcem upozornit uzivatele.
     *
     * @var array
     */
    private $errors = array();

    /**
     * Vraci instanci objektu Messanger, ktera je ulozena v session.
     *
     * @return Messanger
     */
    public static function getInstance()
    {
        if (Kernel_Session::getNamespace('Messanger') instanceof Lib_Messanger) {
            return Kernel_Session::getNamespace('Messanger');
        } else {
            Kernel_Session::setNamesapce('Messanger', new Lib_Messanger);
            self::$instance = Kernel_Session::getNamespace('Messanger');
            return self::$instance;
        }
    }


    /**
     * Pridani hlasky pro uzivatele.
     *
     * @param string $note
     */
    public static function addNote($note)
    {
        self::getInstance()->notes[] = $note;
    }

    /**
     * Pridani upozorneni na chybu.
     *
     * @param string $errors 
     */
    public static function addError($errors)
    {
        self::getInstance()->errors[] = $errors;
    }

    /**
     * Vrati hlasky ktere jeste nebyly zobrazeny.
     *
     * @param string $type all|notes|errors
     * @return string
     */
    public static function flush($type = 'all')
    {
        $messageTypes = array('notes', 'errors');
        $output = '';
        $messanger = self::getInstance();
        if ($type === 'all') {
            foreach ($messageTypes as $type) {
                $output .= $messanger->flushMessages($type);
            }
        } else {
            $output .= $messanger->flushMessages($type);
        }
        return $output;
    }
    
    /**
     * Vrati konkretni typ hlasek.
     *
     * @param string $type
     * @return string
     */
    private function flushMessages($type)
    {
        $messanger = self::getInstance();
        $output = '';
        $attr['class'] = $type;
        foreach ($messanger->{'get' . $type}() as $text) {
            $output .= HTML::el('div', $attr)->setText($text);
        }
        unset($this->{$type});
        return $output;
    }

    /**
     * Vrati vlastnost notes;
     *
     * @return array
     */
    private function getNotes()
    {
        return $this->notes;
    }

    /**
     * Vrati vlastnost errors.
     *
     * @return array
     */
    private function getErrors()
    {
        return $this->errors;
    }

}