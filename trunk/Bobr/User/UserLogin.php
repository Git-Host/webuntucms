<?php

/**
 * Description of UserLogin
 *
 * @author rbas
 */
class Bobr_User_UserLogin extends Object
{
    private $nick = '';
    private $pass = '';

    public function  __construct($nick = '', $pass = '')
    {
        $this->nick = $nick;
        $this->pass = $pass;
    }

    /**
     * Zaloguje uzivatele.
     * Pokud nejsou shodne hesla vyhodi False o nezdaru.
     * Pokud neexistuje uzivatelske jemno vyhodi vyjimku.
     *
     * @return boolean
     * @throws UserNotExistException Pokud uzivatelske jemno neexistuje.
     */
    public function logIn()
    {
        try {
            $user = new Bobr_User_User;
            $user->loadByNick($this->nick);
        } catch (Bobr_User_UserNotExistException $e) {
            // Vyjimku zde odchytavame protoze dalsi kus kodu by nefungoval
            // a zaroven ji vyvarime aby se odchytila vejs.
            throw new Bobr_User_UserNotExistException($e->getMessage());
        }

        $pass = Bobr_User_UserValidator::generatePassword($this->pass);
        if ($pass === $user->getPass()) {
            $this->unsetUserFromSession()
                ->setUserToSession($user);
            return TRUE;
        } else {
            return FALSE;
        }
    }

    public function logOut()
    {
        $this->unsetUserFromSession();
        $user = new Bobr_User_User;
        $user->setAnonymous();
        $this->setUserToSession($user);
    }

    /**
     * Nastavi usera do session
     *
     * @param User $user
     * @return UserLogin
     */
    private function setUserToSession(Bobr_User_User $user)
    {
        Bobr_Session::getInstance()->user = $user;
        return $this;
    }

    /**
     * Odnastavi usera v session.
     *
     * @return UserLogin
     */
    private function unsetUserFromSession()
    {
        unset(Bobr_Session::getInstance()->user);
        return $this;
    }
}