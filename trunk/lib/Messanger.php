<?php
/**
 * Description of Messanger
 *
 * @author rbas
 */
class Messanger
{

    private static $notes = array();

    public static function addNote($note)
    {
        self::$notes[] = $note;
        self::store();
    }

    public static function flush()
    {
       foreach(self::$notes as $message) {
            self::printMessage($message, 'Note');
            unset(Session::getInstance()->messanger);
        }
       
    }

    private static function store()
    {
        Session::getInstance()->messanger  = new self;
    }

    private static function printMessage($message, $type)
    {
        echo "\n<p class=\"$type\"><b>$type: $message</b><p>\n";
    }
}