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
     * @param DocumentManager $dm
     *
     * @return Response
     * @throws \Doctrine\ODM\MongoDB\LockException
     * @throws \Doctrine\ODM\MongoDB\Mapping\MappingException
     */
    public function edit($id, DocumentManager $dm): Response
    {
        $repository = $this->getRepository($dm);

        /**
         * @var Song $song
         */
        $song = $repository->find(['id' => $id]);
        return $this->setupForm($song, 'Update Song');
    }

    /**
     * @Route("/new", name="song_new")
     *
     * @return Response
     */
    public function new(): Response
    {
        return $this->setupForm(new Song(), 'Create Song');
    }

    /**
     * @Route("/create", name="song_create")
     * @param Request         $request
     * @param DocumentManager $dm
     *
     * @return Response
     * @throws \Doctrine\ODM\MongoDB\MongoDBException
     * @throws \Doctrine\ODM\MongoDB\Mapping\MappingException
     */
    public function create(Request $request, DocumentManager $dm): Response
    {
        $repository = $this->getRepository($dm);
        $formSong = $request->request->get('song');
        /**
         * @var Song $song
         */
        $song = $repository->find(['id' => $formSong['id']]);

        $form = $this->createForm(SongType::class, $song);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $song = $form->getData();

            $dm->persist($song);
            $dm->flush();

            return $this->redirect('/songs/');
        }
    }

    /**
     * @Route("/delete/{id}", name="song_delete")
     * @param string          $id
     * @param DocumentManager $dm
     *
     * @return Response
     * @throws \Doctrine\ODM\MongoDB\MongoDBException
     * @throws \Doctrine\ODM\MongoDB\Mapping\MappingException
     */
    public function delete(string $id, DocumentManager $dm): Response
    {
        $repository = $this->getRepository($dm);
        /**
         * @var Song $song
         */
        $song = $repository->find(['id' => $id]);

        $dm->remove($song);
        $dm->flush();

        return $this->redirect('/songs/');
    }
    /* Utility functions here vvvvv **********************************************************************************/

    /* Route functions here ^^^^^ ************************************************************************************/

    /**
     * @param Song   $song
     * @param string $label
     *
     * @return Response
     */
    private function setupForm(Song $song, string $label): Response
    {
        $form = $this->createForm(SongType::class, $song);

        return $this->render(
            '/songs/new.html.twig',
            [
                'form' => $form->createView(),
                'label' => $label
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