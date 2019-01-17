<?php
/**
 * Created by PhpStorm.
 * User: randt
 * Date: 12/11/2018
 * Time: 7:02 PM
 */

namespace App\Controller;

use App\Document\Song;
use App\Form\Type\SongType;
use Doctrine\ODM\MongoDB\DocumentManager;
use Doctrine\ODM\MongoDB\Repository\DocumentRepository as Repository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
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

/* Route functions here vvvvv ************************************************************************************/

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
            '/songs/index.html.twig', [
           'song_list' => $song_list,
        ]);
    }

    /**
     * @Route("/edit/{id}", name="song_edit")
     * @param string          $id
     * @param Request         $request
     * @param DocumentManager $dm
     *
     * @return Response
     * @throws \Doctrine\ODM\MongoDB\LockException
     * @throws \Doctrine\ODM\MongoDB\Mapping\MappingException
     */
    public function edit($id, Request $request, DocumentManager $dm): Response
    {
        $repository = $this->getRepository($dm);

        /**
         * @var Song $song
         */
        $song = $repository->find(['id' => $id]);
        return $this->update($song, $request, $dm);
    }

    /**
     * @Route("/new", name="song_new")
     * @param Request         $request
     * @param DocumentManager $dm
     *
     * @return Response
     */
    public function new(Request $request, DocumentManager $dm): Response
    {
        return $this->update(new Song(), $request, $dm);
    }

    /**
     * @Route("/create", name="song_create")
     * @param Request         $request
     * @param DocumentManager $dm
     *
     * @return Response
     * @throws \Doctrine\ODM\MongoDB\MongoDBException
     */
    public function create(Request $request, DocumentManager $dm): Response
    {
        $form = $this->createForm(SongType::class, new Song());
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $song = $form->getData();

            $dm->persist($song);
            $dm->flush();

            return $this->redirect('/songs/');
        }
        /*
        $song = new Song();
        $song->setArtist($passed_song->getArtist());
        $song->setTitle($passed_song->getTitle());
        $song->setKey($passed_song->getKey());
        $song->setCamelot($passed_song->getCamelot());
        $song->setDuration($passed_song->getDuration());
        $song->setBpm($passed_song->getBpm());

        $dm->persist($song);
        $dm->flush();

        return $this->redirectToRoute('song_index');
        */
    }
    /* Utility functions here vvvvv **********************************************************************************/

    /* Route functions here ^^^^^ ************************************************************************************/

    /**
     * @param Song            $song
     * @param Request         $request
     * @param DocumentManager $dm
     *
     * @return Response
     */
    private function update(Song $song, Request $request, DocumentManager $dm): Response
    {
        $form = $this->createForm(SongType::class, $song);

        return $this->render(
            '/songs/new.html.twig',
            [
                'form' => $form->createView()
            ]
        );
    }

    /**
     * @param DocumentManager $dm
     *
     * @return Repository
     */
    protected function getRepository(DocumentManager $dm): Repository
    {
        if ($this->repository === null) {
            $this->repository = $dm->getRepository(Song::class);
        }

        return $this->repository;
    }

/* Utility functions here ^^^^^ **********************************************************************************/
}