<?php
/**
 * Created by PhpStorm.
 * User: furga
 * Date: 14.07.2018
 * Time: 22:28
 */

namespace AppBundle\Models;


class LoginUser
{
    private $login;

    private $password;

    private $captcha;

    private $captchaPic;

    public function setLogin($login)
    {
        $this->login = $login;
        return $this;
    }

    public function getLogin()
    {
        return $this->login;
    }

    public function setPassword($password)
    {
        $this->password = $password;
        return $this;
    }

    public function getPassword()
    {
        return $this->password;
    }

    public function setCaptcha($captcha)
    {
        $this->captcha = $captcha;
        return $this;
    }

    public function getCaptcha()
    {
        return $this->captcha;
    }

    public function setCaptchaPic($captchaPic)
    {
        $this->captchaPic = $captchaPic;
        return $this;
    }

    public function getCaptchaPic()
    {
        return $this->captchaPic;
    }
}