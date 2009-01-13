<?php

/**
 * Vyjimka ktera se vyhazuje pokud dojde k fatalu ve slozce user
 */
class Kernel_User_UserException extends Kernel_BobrException {}

/**
 * Pokud uzivatel neexistuje vyhodi se tato vyjimka.
 */
class Kernel_User_UserNotExistException extends Kernel_User_UserException{}

class Kernel_User_UserLoginException extends Kernel_User_UserException{}