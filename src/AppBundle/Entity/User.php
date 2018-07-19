<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Validator\Mapping\ClassMetadata;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Constraints\Length;

/**
 * User
 *
 * @ORM\Table(name="user")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\UserRepository")
 * @UniqueEntity(fields="login", message="Пользователь с таким логином уже есть!")
 */
class User
{
    /**
     * @var int
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @var string
     *
     * @ORM\Column(name="login", type="string", length=255, unique=true)
     * @Assert\NotBlank(message="Логин не может быть пустым!")
     * @Assert\Length(min="4", max="15", minMessage="Длина логина должна быть минимум 4 символа!", maxMessage="Длина логина не должна превышать 15 символов!")
     * @Assert\Regex(pattern="/^[a-zA-Z0-9]+$/", message="Логин должен содержать только буквы и цыфры!")
     */
    private $login;

    /**
     * @var string
     *
     * @ORM\Column(name="password", type="string", length=255)
     * @Assert\NotBlank(message="Пароль не может быть пустым!")
     * @Assert\Length(min="5", max="25", minMessage="Длина пароля должна быть минимум 5 символов!", maxMessage="Длина пароля не должна превышать 25 символов!")
     * @Assert\Regex(pattern="/^(?=.*\d)(?=.*\w)(?!.*\s).*$/" ,message="Пароль должен содержать как минимум одну цыфру!")
     */
    private $password;

    /**
     * @var string
     *5242880
     * @ORM\Column(name="file", type="string", length=255)
     * @Assert\File(maxSize="5242880", maxSizeMessage="Размер файла не должен превышать 5Мб", mimeTypes = {"image/png", "image/jpeg", "image/jpg","image/gif"}, mimeTypesMessage="Файл должен иметь расширение .jpeg, .jpg, .gif или .png")
     */
    private $file;

    /**
     * @var int
     *
     * @ORM\Column(name="gender", type="integer")
     */
    private $gender;

    /**
     * @var int
     *
     * @ORM\Column(name="karma", type="integer")
     */
    private $karma;


    /**
     * Get id
     *
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set login
     *
     * @param string $login
     *
     * @return User
     */
    public function setLogin($login)
    {
        $this->login = $login;

        return $this;
    }

    /**
     * Get login
     *
     * @return string
     */
    public function getLogin()
    {
        return $this->login;
    }

    /**
     * Set password
     *
     * @param string $password
     *
     * @return User
     */
    public function setPassword($password)
    {
        $this->password = $password;

        return $this;
    }

    /**
     * Get password
     *
     * @return string
     */
    public function getPassword()
    {
        return $this->password;
    }

    /**
     * Set file
     *
     * @param string $file
     *
     * @return User
     */
    public function setFile($file)
    {
        $this->file = $file;

        return $this;
    }

    /**
     * Get file
     *
     * @return string
     */
    public function getFile()
    {
        return $this->file;
    }

    /**
     * Set gender
     *
     * @param integer $gender
     *
     * @return User
     */
    public function setGender($gender)
    {
        $this->gender = $gender;

        return $this;
    }

    /**
     * Get gender
     *
     * @return int
     */
    public function getGender()
    {
        return $this->gender;
    }

    /**
     * Set karma
     * @param integer $karma
     *
     * @return User
     */
    public function setKarma($karma)
    {
        $this->karma= $karma;
        return $this;
    }

    /**
     * Get karma
     *
     * @return int
     */
    public function getKarma()
    {
        return $this->karma;
    }
}

