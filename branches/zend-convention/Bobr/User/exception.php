<?php

/**
 * Vyjimka ktera se vyhazuje pokud dojde k fatalu ve slozce user
 */
class Bobr_User_UserException extends Bobr_BobrException {}

/**
 * Pokud uzivatel neexistuje vyhodi se tato vyjimka.
 */
class Bobr_User_UserNotExistException extends Bobr_User_UserException{}

class Bobr_User_UserLoginException extends Bobr_User_UserException{}