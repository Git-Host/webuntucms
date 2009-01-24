<?php

class Lib_Tools
{
	private static $bootWebInstance;


	/**
	 * Vyrizne ze script name nazev
	 * @todo provadet kontrolu oproti databazovym webInstancim jestli vubec takova existuje
	 *
	 * @return string $matches[1] vrati nazev scriptu oriznute o php
     * @throws Exception
	 */
	public static function getWebInstance()
	{
		if( empty( self::$bootWebInstance ) ){
			preg_match( "/(\w*)\.php/", $_SERVER['SCRIPT_NAME'], $matches );
			if( isset ( $matches[1] ) ){
				self::$bootWebInstance = $matches[1];
				return self::$bootWebInstance;
			}else {
				throw new Exception("Nelze identifikovat spoustejici script.");
			}
		}else {
			return self::$bootWebInstance;
		}

	}

    /**
     * Funkce vlozi do commandu argumenty.
     * @todo nekontroluje se spravnost argumentu.
     *
     * @param string $command Command ktery se ma naplnit
     * @param array $arguments Argumenty
     * @return string
     */
    public static function mergeCommand($command, array $arguments)
    {
        // Rozzerem si command.
        $eCommand = explode('/', $command);
        $argumentCounter = 0;
        $commandCounter = 0;
        // Projdem command a budem v nem hledat misto na vlozeni argumentu
        foreach ($eCommand as $value) {
            // Pokud jsme na necem kde je zavorka je to promenlivy argument.
            if (0 < preg_match('@^\(.*@', $value)) {
                // Zmenime jeho hodnotu.
                $eCommand[$commandCounter] = $arguments[$argumentCounter];
                $argumentCounter ++;
            }
            $commandCounter ++;
        }
        return implode('/', $eCommand);
    }
}