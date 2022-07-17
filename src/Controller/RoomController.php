<?php
/**
 * Created by PhpStorm.
 * User: starwox
 * Date: 21/03/2022
 * Time: 09:48
 */

namespace App\Controller;


use App\Entity\MusicVote;
use App\Entity\Room;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Response;
use function Symfony\Config\Monolog\token;
use Symfony\Contracts\HttpClient\HttpClientInterface;

class RoomController extends AbstractController
{
    private $client;

    public function __construct(HttpClientInterface $client)
    {
        $this->client = $client;
    }


    /**
     * @Route("/fr/api/spotify-playlist", name="playlist_spotify", methods={"POST"})
     */
    public function playlistSpotify(Request $request, EntityManagerInterface $em)
    {
        $token = $request->query->get('spotifyToken');
        $idPlaylist = $request->query->get('idPlaylist');


        try {
            $response = $this->client->request(
                'GET',
                'https://api.spotify.com/v1/playlists/' . $idPlaylist,
                [
                    "auth_bearer" => $token
                ]
            );
        } catch (\Exception $e) {
            return $e;
        }
        $array = json_decode($response->getContent(), true);

        $result = [];



        foreach($array["tracks"]["items"] as $track) {
            $result[] = [
                "song" => $track["track"]["artists"][0]["name"] . " - " . $track["track"]["name"],
                "spotify_href" => $track["track"]["external_urls"]["spotify"],
                "api_href" => $track["track"]["href"],
                "img_music" => $track["track"]["album"]["images"],
                "duration" => $track["track"]["duration_ms"],
                "id_music" => $track["track"]["id"],
                "uri" => $track["track"]["uri"]
            ];
        }

        $room = new Room();
        $room->setEnable(true);
        $room->setLastUpdate(new \DateTime("NOW"));
        $room->setName($array["owner"]["id"]);
        $room->setCode(rand(10000,99999));

        $em->persist($room);
        $em->flush();

        return new JsonResponse([$result, [$room->getCode(), $room->getId()]]);
    }


    /**
     * @Route("/fr/api/spotify-user/{token}", name="playlist_user", methods={"POST"})
     */
    public function getPlaylist($token, Request $request)
    {


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
     * @Route("/fr/api/music-init", name="music_init", methods={"POST"})
     * @param Request $request
     */
    public function musicInit(Request $request, EntityManagerInterface $em)
    {
        $jsonContent = $request->getContent();
        $items = json_decode($jsonContent);
        $codeRound = rand(10000,99999);
        foreach ($items->{"musics"} as $item) {
            $music = new MusicVote();
            $music->setMusicName($item->{"song"});
            $music->setVote(0);
            $music->setCodeRound($codeRound);
            $music->setUri($item->{"uri"});
            $music->setIdMusic($item->{"id_music"});
            $music->setDuration($item->{"duration"});

            $room = $em->getRepository(Room::class)->find($items->{"roomId"});
            $music->setRoom($room);

            $em->persist($music);

        }

        $em->flush();

        return new JsonResponse($codeRound);
    }

    /**
     * @Route("/fr/api/vote-music", name="music_vote", methods={"POST"})
     * @param Request $request
     */
    public function voteMusic(Request $request, EntityManagerInterface $em)
    {
        $jsonContent = $request->getContent();
        $data = json_decode($jsonContent);


        $musicVote = $em->getRepository(MusicVote::class)->findOneBy([
            "codeRound" => $data->{"codeRoom"},
            "idMusic" => $data->{"idMusic"},
        ]);

        $musicVote->setVote($musicVote->getVote() + 1);
        $em->flush();

        return new JsonResponse(200);
    }


    /**
     * @Route("/fr/api/get-room", name="room")
     * @param Request $request
     */
    public function getRoom(Request $request, EntityManagerInterface $em, $test)
    {
        //$jsonContent = $request->getContent();
        //$data = json_decode($jsonContent)->{"roomId"};

        $musicRound = $em->getRepository(MusicVote::class)->findRoomMusic($test);

        return new JsonResponse($musicRound);
    }

    /**
     * @Route("/fr/api/get-winner-music", name="winner-music", methods={"POST"})
     * @param Request $request
     */
    public function getWinnerMusic(Request $request, EntityManagerInterface $em)
    {
        $jsonContent = $request->getContent();
        $data = json_decode($jsonContent)->{"codeRoom"};

        $musicRound = $em->getRepository(MusicVote::class)->findMusicByCode($data);

        $result[] = [
            "idMusic" => $musicRound[0]->getIdMusic(),
            "musicName" => $musicRound[0]->getMusicName(),
            "vote" => $musicRound[0]->getVote(),
            "uri" => $musicRound[0]->getUri(),
            "duration" => $musicRound[0]->getDuration(),
        ];

        return new JsonResponse([$result[0],200]);
    }

    /**
     * @Route("/fr/api/player", name="player", methods={"POST"})
     * @param Request $request
     */
    public function player(Request $request, EntityManagerInterface $em)
    {
        $jsonContent = $request->getContent();
        $data = json_decode($jsonContent);

        $this->client->request(
            'POST',
            'https://api.spotify.com/v1/me/player/queue?uri=' . $data->{"uri"},
            [
                "auth_bearer" => $data->{"token"},
            ]
        );

        $this->client->request(
            'POST',
            'https://api.spotify.com/v1/me/player/next',
            [
                "auth_bearer" => $data->{"token"},
            ]
        );

        return new JsonResponse(200);
    }
}