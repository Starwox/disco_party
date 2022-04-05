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
    public function home()
    {
        $client_id = $this->getParameter('SPOTIFY_CLIENT_ID');
        $redirect_uri = $this->getParameter('REDIRECT_URI');
        $token = $this->getParameter('SPOTIFY_TOKEN');

        $response = $this->client->request(
            'GET',
            'https://accounts.spotify.com/authorize?client_id=' . $client_id .
            '&redirect_uri=' . $redirect_uri .
            '&response_type=code&scope=user-modify-playback-state user-read-playback-state user-read-currently-playing playlist-read-private user-read-private streaming app-remote-control',
            [
                "auth_bearer" => $token
            ]
        );

        return $this->render($response->getResult());
    }
}