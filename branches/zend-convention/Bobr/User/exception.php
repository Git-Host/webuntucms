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

class Bobr_UserException extends Bobr_BobrException {}
class Bobr_UserIAException extends Bobr_UserException {}

class Bobr_User_GroupColectionException extends Bobr_UserException {}
class Bobr_User_GroupColectionIAException extends Bobr_UserException {}

class Bobr_User_Group_FunctionColectionException extends Bobr_UserException {}
class Bobr_User_Group_FunctionColectionIAException extends Bobr_User_Group_FunctionColectionException {}
/**
 * Nove vyjimky
 * @author rbas
 *
 */
class Bobr_User_Exception extends Bobr_Exception {}
class Bobr_User_Group_Exception extends Bobr_User_Exception {}
class Bobr_User_Group_IAException extends Bobr_User_Group_Exception {}