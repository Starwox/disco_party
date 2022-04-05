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
use Symfony\Contracts\HttpClient\HttpClientInterface;

class HomeController extends AbstractController
{
    private $client;

    public function __construct(HttpClientInterface $client)
    {
        $this->client = $client;
    }

   /**
    * @Route("/", name="homepage")
    */
    public function home()
    {
        $client_id = $_ENV['SPOTIFY_CLIENT_ID'];
        $redirect_uri = $_ENV['REDIRECT_URI'];
        $token = $_ENV['SPOTIFY_TOKEN'];


        return $this->redirectToRoute('callback', ["playlist_id" => "5zS7xJKWhgAkCOd4VaV7N9"]);
    }
}