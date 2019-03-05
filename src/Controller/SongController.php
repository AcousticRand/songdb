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
use App\Repository\SongRepository;
use Doctrine\ODM\MongoDB\DocumentManager;
use Doctrine\ODM\MongoDB\LockException;
use Doctrine\ODM\MongoDB\Mapping\MappingException;
use Doctrine\ODM\MongoDB\MongoDBException;
use MongoDB\BSON\Regex;
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
     * @var DocumentManager $documentManager
     */
    protected $documentManager;

    /**
     * @var SongRepository $repository
     */
    protected $repository;

    public function __construct(DocumentManager $dm)
    {
        $this->documentManager = $dm;
        $this->repository      = $this->documentManager->getRepository(Song::class);
    }

    /* Route functions here vvvvv ************************************************************************************/

    /**
     * @Route("/", name="song_index")
     *
     * @return Response
     */
    public function list(): Response
    {
        return $this->renderList(
            [],
            ['artist' => 'ASC', 'title' => 'ASC']
        );
    }

    /**
     * @Route("/search/artist/{query}", name="song_search_artist")
     * @param string          $query
     *
     * @return Response
     */
    public function searchArtist(string $query): Response
    {
        return $this->renderList(
            ['artist' => $query],
            ['title' => 'ASC']
        );
    }

    /**
     * @Route("/search", name="song_search")
     * @param Request         $request
     *
     * @return Response
     */
    public function search(Request $request): Response
    {
        $query = $request->request->get('query');

        $filter  = ['title' => new Regex($query, 'i')];
        $orderBy = ['artist' => 'ASC', 'title' => 'ASC'];

        return $this->renderList($filter, $orderBy);
    }

    /**
     * @Route("/edit/{id}", name="song_edit")
     * @param string          $id
     *
     * @return Response
     * @throws LockException
     * @throws MappingException
     */
    public function edit(string $id): Response
    {
        /**
         * @var Song $song
         */
        $song = $this->repository->find(['id' => $id]);
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
     *
     * @return Response
     * @throws MongoDBException
     * @throws MappingException
     */
    public function create(Request $request): Response
    {
        $formSong = $request->request->get('song');                     // get any pre-existing values
        $label = 'Create Song';
        /**
         * @var Song $song
         */
        $song = new Song();
        if (array_key_exists('id', $formSong)) {                        // if we had an id, we are in edit mode
            $song = $this->repository->find(['id' => $formSong['id']]);
            $label = 'Update Song';
        }

        $form = $this->createForm(SongType::class, $song);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $song = $form->getData();

            $this->documentManager->persist($song);
            $this->documentManager->flush();

            $redirect = ($label === 'Create Song') ? '/songs/new' : '/songs';

            return $this->redirect($redirect);
        }

        return $this->render(
            '/songs/new.html.twig',
            [
                'form' => $form->createView(),
                'label' => $label
            ]
        );
    }

    /**
     * @Route("/delete/{id}", name="song_delete")
     * @param string $id
     *
     * @return Response
     * @throws MongoDBException
     * @throws MappingException
     */
    public function delete(string $id): Response
    {
        /**
         * @var Song $song
         */
        $song = $this->repository->find(['id' => $id]);

        $this->documentManager->remove($song);
        $this->documentManager->flush();

        return $this->redirect('/songs/');
    }
    /* Route functions here ^^^^^ ************************************************************************************/

    /* Utility functions here vvvvv **********************************************************************************/

    /**
     * render the list provided
     *
     * @param array $filter
     * @param array $orderBy
     *
     * @return Response
     */
    private function renderList(array $filter = [], array $orderBy = []): Response
    {
        return $this->render(
            '/songs/index.html.twig', [
            'song_list' => $this->getSearchResults($filter, $orderBy),
        ]);
    }

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
     * @param array $filter
     * @param array $orderBy
     *
     * @return array
     */
    protected function getSearchResults(array $filter = [], array $orderBy = []): array
    {
        return $this->repository->findBy(
            $filter,
            $orderBy
        );
    }

/* Utility functions here ^^^^^ **********************************************************************************/
}