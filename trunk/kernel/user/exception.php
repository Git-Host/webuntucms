<?php

/**
 * Vyjimka ktera se vyhazuje pokud dojde k fatalu ve slozce user
 */
class UserException extends BobrException {}

/**
 * Pokud uzivatel neexistuje vyhodi se tato vyjimka.
 */
class UserNotExistException extends UserException{}

class UserLoginException extends UserException{}