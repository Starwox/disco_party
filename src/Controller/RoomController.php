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
    //$number = strtoupper(bin2hex(random_bytes(4)));


    /**
     * @Route("/api/spotify-playlist/{playlist_id}", name="playlist_spotify")
     */
    public function playlistSpotify($playlist_id): Response
    {
        $token = $this->getParameter('spotify_token');
        $client_id= $this->getParameter('spotify_token');
        $clientSecret = $this->getParameter('spotify_token');

        $response = $this->client->request(
            'GET',
            'https://api.spotify.com/v1/playlists/' . $playlist_id,
            [
                "auth_bearer" => $token
            ]
        );

        $encoders = [new XmlEncoder(), new JsonEncoder()];
        $normalizers = [new ObjectNormalizer()];

        $serializer = new Serializer($normalizers, $encoders);
        $json = $serializer->serialize($response->getContent(), 'json');

        return new JsonResponse($json);
    }


    /**
     * @Route("/fr/api/spotify-login", name="login_spotify")
     */
    public function loginSpotify(): Response
    {
        $redirect_uri = $this->getParameter('redirect_uri');
        $client_id= $this->getParameter('spotify_token');

        $response = $this->client->request(
            'GET',
            'https://accounts.spotify.com/authorize?redirect_uri='.  $redirect_uri.
            '&response_type=code&client_id=' . $client_id, []
        );

        return new Response($response->getContent());
    }

}