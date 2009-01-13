<?php
/**
 * Description of Login
 *
 * @author rbas
 */
class Modules_Login_Exec extends Lib_AbstractModule
{
    /**
     * Ukaze prihlasovaci formular.
     *
     * @return string
     */
    protected function defaultAction()
    {
        return $this->loginForm();
    }

    protected function showAction()
    {
        $data = '';
        if (FALSE === Kernel_User_User::isLoged()) {
            $this->addToTemplate('loginForm', $this->loginForm());
            $data .= $this->loadTemplate('/template.phtml');
        } else {
            $data .= $this->showUserPersonal();
        }
        return $data;
    }

    protected function loginAction()
    {
        $output = '';
        $request = Kernel_Request_HttpRequest::getInstance();
        $post = $request->getPost();
        if ($request->isPost() && isset($post['userLogin']) && isset($post['userPassword'])) {
            $post = $request->getPost();
            try {
                $userLogin = new Kernel_User_UserLogin($post['userLogin'], $post['userPassword']);
                if (TRUE === $userLogin->logIn() ){
                    Lib_Messanger::addNote('Uzivatele se podarilo zalogovat.');
                    Kernel_Request_HttpRequest::redirect('/');
                } else {
                    Lib_Messanger::addError('Uzivatele se nepodarilo zalogovat.');
                }
            } catch (UserNotExistException $e) {
                Lib_Messanger::addError('Uzivatelske jmeno neexistuje.');
            }
        }
        return  $output;
    }

    protected function logoutAction()
    {
        try {
            $userLogin = new Kernel_User_UserLogin;
            $userLogin->logOut();
            Lib_Messanger::addNote('Uzivatel byl odhlasen.');
            Kernel_Request_HttpRequest::redirect(Link::build('login/login'));
        } catch (UserLoginException $e) {
            echo $e->getMessage();
        }
        return '';
    }

    private function loginForm()
    {
        $form = new Form;
        $form->setAction(Lib_Link::build('login/login'));

        $form->addText('userLogin', 'Jmeno:', 10)
            ->addRule(Form::FILLED, 'Vloz svoje uzivatelske jmeno.');

        $form->addPassword('userPassword', 'Heslo:', 10)
            ->addRule(Form::FILLED, 'Vloz tvoje heslo.')
            ->addRule(Form::MIN_LENGTH, 'Heslo musi byt dlouhe minimalne %d znaku.', 3);

        $form->addSubmit('login', 'Prihlasit');

        return $form .'';
    }

    private function showUserPersonal()
    {
        $user = Kernel_Session::getInstance()->user;
        $output = '<p>Uzivatel: <b>' . $user->nick  . '</b> je zalogovan.<p>';
        $output .= '<a href="' . Lib_Link::build('odhlaseni', TRUE) . '" title="Odhlasit se">Odhlasit se</a>';
        return $output;
    }
}