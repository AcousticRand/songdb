<?php
/**
 * Created by PhpStorm.
 * User: randt
 * Date: 12/11/2018
 * Time: 7:02 PM
 */

namespace App\Controller;

use App\Document\Song;
use Doctrine\ODM\MongoDB\DocumentManager;
use Doctrine\ODM\MongoDB\Repository\DocumentRepository as Repository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/songs")
 */
class SongController extends AbstractController
{
    /**
     * @var Repository $repository
     */
    protected $repository;

    protected function getRepository(DocumentManager $dm): Repository
    {
        if ($this->repository === null) {
            $this->repository = $dm->getRepository(Song::class);
        }

        return $this->repository;
    }

    /**
     * @Route("/", name="song_index")
     * @param DocumentManager $dm
     *
     * @return Response
     */
    public function list(DocumentManager $dm): Response
    {
        $repository = $this->getRepository($dm);
        $song_list = $repository->findAll();

        return $this->render(
            'songs/song_index.html.twig', [
           'song_list' => $song_list,
        ]);
    }

    /**
     * @Route("/edit/{id}", name="song_edit")
     * @param string          $id
     * @param DocumentManager $dm
     *
     * @return Response
     * @throws \Doctrine\ODM\MongoDB\LockException
     * @throws \Doctrine\ODM\MongoDB\Mapping\MappingException
     */
    public function edit($id, DocumentManager $dm): Response
    {
        $repository = $this->getRepository($dm);
        $song = $repository->find(['id' => $id]);
        return $this->render(
            'songs/song_show.html.twig', [
            'song' => $song,
        ]);
    }

    /**
     * @Route("/new", name="song_new")
     * @param Request         $request
     * @param DocumentManager $dm
     *
     * @return Response
     * @throws \Doctrine\ODM\MongoDB\MongoDBException
     */
    public function new(Request $request, DocumentManager $dm): Response
    {
        // creates a song and gives it some dummy data for this example
        $song = new Song();

        $form = $this->createFormBuilder($song)
            ->add('artist', TextType::class)
            ->add('title', TextType::class)
            ->add('save', SubmitType::class, array('label' => 'Create Song'))
            ->getForm();

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $song = $form->getData();
            $response = $this->createSong($song, $dm);
        } else {
            $response = $this->render('songs/song_new.html.twig', array(
                'form' => $form->createView(),
            ));
        }

        return $response;
    }

    /**
     * @param Song            $passed_song
     * @param DocumentManager $dm
     *
     * @return Response
     * @throws \Doctrine\ODM\MongoDB\MongoDBException
     */
    public function createSong(Song $passed_song, DocumentManager $dm): Response
    {
        $song = new Song();
        $song->setArtist($passed_song->getArtist());
        $song->setTitle($passed_song->getTitle());

        $dm->persist($song);
        $dm->flush();

        return new Response('Created Song: id '.$song->getId());
    }
}