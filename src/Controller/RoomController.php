<?php
/**
 * Created by PhpStorm.
 * User: starwox
 * Date: 21/03/2022
 * Time: 09:48
 */

namespace App\Controller;


use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Response;


class RoomController extends AbstractController
{
    /**
     * @Route("/", name="homepage")
     */
    public function number(): Response
    {

        $number = substr(uniqid('', true), -8);


        return new Response(
            '<html><body>Lucky number: '.$number.'</body></html>'
        );
    }
}