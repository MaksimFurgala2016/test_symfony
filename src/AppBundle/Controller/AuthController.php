<?php

namespace AppBundle\Controller;

use AppBundle\AppBundle;
use AppBundle\Entity\User;
use AppBundle\Models;
use AppBundle\Repository\UserRepository;
use Doctrine\Bundle\DoctrineBundle\DoctrineBundle;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\ORMException;
use Gregwar\Captcha\CaptchaBuilder;
use Gregwar\Captcha\CaptchaBuilderInterface;
use Gregwar\CaptchaBundle\GregwarCaptchaBundle;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Cache\Adapter\DoctrineAdapter;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Security\Core\Authentication\Token\UsernamePasswordToken;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;
use Symfony\Component\Security\Http\Event\InteractiveLoginEvent;

/**
 * Class AuthController
 * @package AppBundle\Controller
 */
class AuthController extends Controller
{
    private $repository_users;//репозиторий пользователей

    /**
     * AuthController constructor.
     * @param EntityManagerInterface $entityManager
     */
    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->repository_users = $entityManager->getRepository('AppBundle:User');
    }

    /**
     * @param $name
     * @return \Symfony\Component\HttpFoundation\Response
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
    public function loginAction(Request $request, AuthenticationUtils $authenticationUtils)
    {
        $logUser = new Models\LoginUser();
        $form = $this->createFormBuilder($logUser)
            ->add('login', TextType::class)
            ->add('password', PasswordType::class)
            ->add('captcha', TextType::class)
            ->add('save', SubmitType::class, array('label' => 'Войти'))
            ->getForm();
        $form->handleRequest($request);
        $user = $form->getViewData();

        //если отправлена форма, то
        if ($form->isSubmitted()) {
            $errors = array();//массив ошибок
            if ($this->repository_users->checkUserDataBase($user->getLogin(), $user->getPassword()) != true) {
                $error_auth = "Неверный логин/пароль";
                array_push($errors, $error_auth);
            }
            if ($_SESSION['phrase'] != $user->getCaptcha()) {
                $error_captcha = "Текст с картинки введен неверно";
                array_push($errors, $error_captcha);
            }
            if (count($errors) > 0) {
                $captcha = $this->CookieSetCaptcha();
                //выводим новую капчу, форму и сообщения об ошибках
                return $this->render('auth/login.html.twig', array('form_login' => $form->createView(), 'captcha' => $captcha, 'errors' => $errors));
            }
            $token = new UsernamePasswordToken($user, null, 'main', $logUser->getRoles());
            $this->get('session')->set('_security_main', serialize($token));
            $event = new InteractiveLoginEvent($request, $token);
            $this->get("event_dispatcher")->dispatch("security.interactive_login", $event);
            return $this->redirectToRoute('homepage');
        }
        $captcha = $this->CookieSetCaptcha();
        //выводим форму и капчу
    return $this->render('auth/login.html.twig', array('form_login' => $form->createView(),'captcha' => $captcha));
    }

    /**
     * @Route("/logout", name="logout")
     */
    public function logoutAction()
    {

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

    /**
     * оздание объекта капчи
     * сохраняем в SESSION код капчи
     * @return CaptchaBuilder - капча
     */
    public function CookieSetCaptcha ()
    {
        $captcha = new CaptchaBuilder();
        $captcha->build()->save('out.jpg');
        $_SESSION['phrase'] = $captcha->getPhrase();
        return $captcha;
    }
}
