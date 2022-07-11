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
     * @Route("/fr/api/spotify-playlist/", name="playlist_spotify", methods={"GET"})
     */
    public function playlistSpotify($playlist_id, Request $request)
    {
        $token = $request->request->get('spotifyToken');
        $idPlaylists = $request->request->get('idPlaylists');


        /*$response = $this->client->request(
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
                "api_href" => $track["track"]["href"],
                "img_music" => $track["track"]["album"]["images"]
            ];
        }
        */
        return new JsonResponse([$idPlaylists, $token]);
    }


    /**
     * @Route("/fr/api/spotify-user/{token}", name="playlist_user", methods={"POST"})
     */
    public function getPlaylist($token, Request $request)
    {
        //$token = $request->query->get('spotifyToken');

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

    /**
     * @Route("/callback/", name="callback")
     */
    public function loginSpotify()
    {
        return new Response("Hello World");
    }

    /**
     * @Route("/room/code", name="getCode", methods={"POST"})
     */
    public function getCode(Request $request)
    {
        $token = $request->query->get('spotifyToken');


        return new Response("Hello World");
    }

    /**
     * @Route("/test/room/{token}", name="create-test", methods={"GET"})
     */
    public function testRoom($token, Request $request) {

        dd($token);
        try {
            $call = $this->client->request(
                'GET',
                "https://api.spotify.com/v1/me",
                [
                    "auth_bearer" => $token
                ]
            );
        } catch (\Exception $e) {
            dd($e);
        }
        /*
        try {
            $call = $this->client->request(
                'GET',
                "https://api.spotify.com/v1/me",
                [
                    "auth_bearer" => "BQCN1-Kapk0Qu6LpwLeftQzQHI2GYZKSRuJwFae55xX_AToZ1c5VMtPVRHhyDMxYLxg5Rh7DDI0cLOaTCfxdUfh09Of_skTBca4lcs2t6Wt6TxUwb5aVkbb_MH0hwFLgbpbCDhqdGdmNAomIcENHXkewnCnNwI2txChTYfS8kE-xnA"
                ]
            );
        } catch (\Exception $e) {
            dd($e);
        }*/
        $array = json_decode($call->getContent(), true);

        dd($array);



    }
}