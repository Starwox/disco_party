<?php
/**
 * Created by PhpStorm.
 * User: starwox
 * Date: 07/03/2022
 * Time: 17:25
 */

namespace App\Controller;

use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;

class HomeController extends AbstractController
{


   /**
    * @Route("/", name="homepage")
    */
    public function number(): Response
    {

        return $this->render('spotify.html');
    }
}