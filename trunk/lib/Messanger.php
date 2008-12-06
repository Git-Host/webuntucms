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

    public static function flush($return = FALSE)
    {
       $output = '';
       foreach(self::$notes as $message) {
            $output .= "\n<p><b>$message</b><p>\n";
        }
        //unset(Session::getInstance()->messanger);

       if (TRUE === $output) {
           return $output;
       } else {
           echo $output;
       }
    }

    private static function store()
    {
        Session::getInstance()->messanger  = new self;
    }
}