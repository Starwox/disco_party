<?php
/**
 * Created by PhpStorm.
 * User: starwox
 * Date: 21/03/2022
 * Time: 09:48
 */

namespace App\Controller;


use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Contracts\HttpClient\HttpClientInterface;
use Symfony\Component\Serializer\Encoder\JsonEncoder;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;
use Symfony\Component\Serializer\Serializer;
use Symfony\Component\Serializer\Encoder\XmlEncoder;

class RoomController extends AbstractController
{
    private $client;

    public function __construct(HttpClientInterface $client)
    {
        $this->client = $client;
    }


    /**
     * @Route("/fr/api/spotify-playlist/{playlist_id}", name="playlist_spotify", methods={"GET"})
     */
    public function playlistSpotify($playlist_id)
    {
        $token = $this->getParameter('spotify_token');
        //$client_id= $this->getParameter('spotify_token');
        //$clientSecret = $this->getParameter('spotify_token');

        $response = $this->client->request(
            'GET',
            'https://api.spotify.com/v1/playlists/' . $playlist_id,
            [
                "auth_bearer" => $token
            ]
        );

        $array = json_decode($response->getContent(), true);

        $result = [];

        foreach($array["tracks"]["items"] as $track) {
            $result[] = [
                "song" => $track["track"]["artists"][0]["name"] . " - " . $track["track"]["name"],
                "spotify_href" => $track["track"]["external_urls"]["spotify"],
                "api_href" => $track["track"]["href"]
            ];
        }
        //dd($result);
        return new JsonResponse($result);
    }




    /**
     * @Route("/callback/", name="callback")
     */
    public function loginSpotify()
    {
        return new Response("Hello World");
    }

}