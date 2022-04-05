<?php
/**
 * Created by PhpStorm.
 * User: starwox
 * Date: 07/03/2022
 * Time: 17:25
 */

namespace App\Controller;

use Symfony\Component\HttpFoundation\JsonResponse;
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
        $token = $_ENV['SPOTIFY_TOKEN'];
        $response = $this->client->request(
            'GET',
            'https://api.spotify.com/v1/me/playlists',
            [
                "auth_bearer" => $token
            ]
        );

        $array = json_decode($response->getContent(), true);

        $result = [];

        foreach($array["items"] as $track) {
            $result[] = [
                "id_playlist" => $track["id"],
                "name" => $track["name"],
                "spotify_href" => $track["external_urls"]["spotify"],
                "api_href" => $track["href"],
                "img_music" => $track["images"],
                "description" => $track["description"],
                "total_music" => $track["tracks"]["total"],
                "uri" => $track["uri"]
            ];
        }

        return new JsonResponse($result);
    }
}