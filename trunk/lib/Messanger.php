<?php
/**
 * Description of Messanger
 *
 * @author rbas
 */
class Messanger
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
        if (Session::getNamespace('Messanger') instanceof Messanger) {
            return Session::getNamespace('Messanger');
        } else {
            Session::setNamesapce('Messanger', new Messanger);
            self::$instance = Session::getNamespace('Messanger');
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
        Messanger::getInstance()->notes[] = $note;
    }

    /**
     * Pridani upozorneni na chybu.
     *
     * @param string $errors 
     */
    public static function addError($errors)
    {
        Messanger::getInstance()->errors[] = $errors;
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
        $messanger = Messanger::getInstance();
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
        $messanger = Messanger::getInstance();
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