<?php
/**
 * Created by PhpStorm.
 * User: olmounir
 * Date: 31/12/2018
 * Time: 10:33
 */

namespace App\Entity;
use FOS\UserBundle\Model\User as BaseUser;
use Doctrine\ORM\Mapping as ORM;

class User extends BaseUser
{

    /**
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */

    protected $id;

    public function __construct()
    {
        parent::__construct();

    }
}