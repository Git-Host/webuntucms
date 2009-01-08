<?php
/**
 * Description of Login
 *
 * @author rbas
 */
class Login extends AbstractModule
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
        if (FALSE === User::isLoged()) {
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
        $request = HttpRequest::getInstance();
        $post = $request->getPost();
        if ($request->isPost() && isset($post['userLogin']) && isset($post['userPassword'])) {
            $post = $request->getPost();
            try {
                $userLogin = new UserLogin($post['userLogin'], $post['userPassword']);
                if (TRUE === $userLogin->logIn() ){
                    Messanger::addNote('Uzivatele se podarilo zalogovat.');
                    HttpRequest::redirect('/');
                } else {
                    Messanger::addError('Uzivatele se nepodarilo zalogovat.');
                }
            } catch (UserNotExistException $e) {
                Messanger::addError('Uzivatelske jmeno neexistuje.');
            }
        }
        return  $output;
    }

    protected function logoutAction()
    {
        try {
            $userLogin = new UserLogin;
            $userLogin->logOut();
            Messanger::addNote('Uzivatel byl odhlasen.');
            HttpRequest::redirect(Link::build('login/login'));
        } catch (UserLoginException $e) {
            echo $e->getMessage();
        }
        return '';
    }

    private function loginForm()
    {
        $form = new Form;
        $form->setAction(Link::build('login/login'));

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
        $user = Session::getInstance()->user;
        $output = '<p>Uzivatel: <b>' . $user->nick  . '</b> je zalogovan.<p>';
        $output .= '<a href="' . Link::build('odhlaseni', TRUE) . '" title="Odhlasit se">Odhlasit se</a>';
        return $output;
    }
}