<?php

namespace AppBundle\Controller;

use AppBundle\Entity\User;
use AppBundle\Models;
use Gregwar\Captcha\CaptchaBuilder;
use Gregwar\Captcha\CaptchaBuilderInterface;
use Gregwar\CaptchaBundle\GregwarCaptchaBundle;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;

/*
 * класс регистрации и авторизации пользователя
 */
class AuthController extends Controller
{
    /*
     * index
     */
    public function indexAction($name)
    {
        return $this->render('', array('name' => $name));
    }

    /**
     * авторизация
     * @Route("/login" , name="login")
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function loginAction(Request $request)
    {
        $logUser = new Models\LoginUser();
        $form = $this->createFormBuilder($logUser)
            ->add('login', TextType::class)
            ->add('password', PasswordType::class)
            ->add('captcha', TextType::class)
            ->add('save', SubmitType::class)
            ->getForm();
        $form->handleRequest($request);
        $user = $form->getViewData();
        //если отправлена форма, то
        if ($form->isSubmitted()) {
                //проверяем логин и пароль
                if ($this->checkUserDataBase($user->getLogin(), $user->getPassword()) == true) {
                    //совпадает ли код капчи с введенным кодом
                    if ($_SESSION['phrase'] == $user->getCaptcha()) {
                        //делаем редирект, сохраняем данный об успеной авторизации
                        return $this->redirectToRoute('homepage');
                    }
                    $captcha = $this->CookieSetCaptcha();
                    return $this->render('auth/login.html.twig', array('form_login' => $form->createView(), 'captcha' => $captcha));
                }
                $captcha = $this->CookieSetCaptcha();
                return $this->render('auth/login.html.twig', array('form_login' => $form->createView(), 'captcha' => $captcha));
        }
        $captcha = $this->CookieSetCaptcha();
    return $this->render('auth/login.html.twig', array('form_login' => $form->createView(),'captcha' => $captcha));
    }

    /**
     * регистрация нового пользователя
     * @Route("/register" , name="register")
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function registerAction(Request $request)
    {
        $user = new User();
        $form = $this->createFormBuilder($user)
            ->add('login', TextType::class)
            ->add('password', PasswordType::class)
            ->add('file', FileType::class)
            ->add('gender', ChoiceType::class, array('choices' => array('Женский' => true, 'Мужской' => false)))
            ->add('save', SubmitType::class, array('label' =>'Зарегистрироваться'))
            ->getForm();
        $form->handleRequest($request);
        if ($form->isSubmitted()) {
            $validator = $this->get('validator');
            $errors = $validator->validate($user);
            if ($form->isValid()) {
                $user = $form->getData();
                $em = $this->getDoctrine()->getManager();
                //если gender - false => мужской (1) : женский (0)
                $user->getGender() == false ? $user->setGender(1) : $user->setGender(0);
                $em->persist($user);
                $em->flush();
                return $this->redirectToRoute('homepage');
            }
            else {
                //если есть ошибки, то выводим
                if (count($errors) > 0) {
                    return $this->render('auth/register.html.twig', array('errors' => $errors, 'form_register' =>$form->createView()));
                }
            }
        }
        return $this->render('auth/register.html.twig', array('form_register' =>$form->createView()));
    }

    /*
     * проверка на наличие пользователя в БД по логину и паролю
     */
    public function checkUserDataBase($login, $password)
    {
        $user = $this->getDoctrine()->getRepository('AppBundle:User')
            ->findBy(array('login' => $login, 'password' => $password));
        if ($user != null)
            return true;//найден
        else return false;//не найден
    }

    /*
     *создание объекта капчи
     * сохраняем в SESSION код капчи
     */
    public function CookieSetCaptcha ()
    {
        $captcha = new CaptchaBuilder();
        $captcha->build()->save('out.jpg');
        $_SESSION['phrase'] = $captcha->getPhrase();
        return $captcha;
    }
}
