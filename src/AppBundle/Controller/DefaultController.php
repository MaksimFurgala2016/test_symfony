<?php

namespace AppBundle\Controller;

use Doctrine\ORM\Event\LifecycleEventArgs;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Doctrine\ORM\EntityManagerInterface;
use AppBundle\Service\FileUploader;
use Symfony\Component\HttpFoundation\File\File;

class DefaultController extends Controller
{
    /**
     * @Route("/", name="homepage")
     */
    public function indexAction(EntityManagerInterface $em)
    {
        /*
         * выводим всех пользователей
         */
        $users = $em
            ->getRepository('AppBundle:User')
            ->findAll();
        $a = "gvchbwjdjwiw";
        return $this->render('default/index.html.twig', array('users' => $users, 'var' => $a ));
    }
}
