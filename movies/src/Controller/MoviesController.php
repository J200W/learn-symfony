<?php

namespace App\Controller;


use App\Entity\Movie;
use App\Form\MovieFormType;
use App\Repository\MovieRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class MoviesController extends AbstractController
{
    private $em;


    private $movieRepository;

    public function __construct(MovieRepository $movieRepository, EntityManagerInterface $em)
    {
        $this->movieRepository = $movieRepository;
        $this->em = $em;
    }

    #[Route(path:"", name:"", methods: ["GET"])]
    public function home(): Response
    {
        return $this->redirectToRoute("movies");
    }

    // HOME PAGE

    #[Route(
        '/movies',
        name: 'movies',
    )]

    public function index(): Response
    {
        $movies = $this->movieRepository->findAll();

        return $this->render("movies/index.html.twig", [
            'movies' => $movies
        ]);
    }

    // SHOW PAGE

    #[Route(
        '/movies/{id}',
        name: 'movie',
        requirements: ['id' => '\d+'],
        methods: ['GET']
    )]

    public function show(int $id): Response
    {
        $movie = $this->movieRepository->find($id);

        return $this->render("movies/show.html.twig", [
            'movie' => $movie
        ]);
    }

    // CREATE PAGE

    #[Route(
        '/movies/create',
        name: 'movie_create',
        methods: ['GET', 'POST']
    )]

    public function create(Request $request): Response
    {
        $movie = new Movie();
        $form = $this->createForm(MovieFormType::class, $movie);

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $newMovie = $form->getData();
            $imagePath = $form->get('imagePath')->getData();
            if ($imagePath) {
                $newFileName = uniqid() . '.' . $imagePath->guessExtension();

                try {
                    $imagePath->move(
                        $this->getParameter('kernel.project_dir') . '/public/uploads',
                        $newFileName
                    );
                } catch (FileException $e) {
                    return  new Response($e->getMessage());
                }

                $newMovie->setImagePath('/uploads/' . $newFileName);
            }

            $this->em->persist($newMovie);
            $this->em->flush();

            return $this->redirectToRoute('movies');
        }

        return $this->render("movies/create.html.twig", [
            'form' => $form->createView()
        ]);
    }

    // EDIT PAGE

    #[Route(
        '/movies/edit/{id}',
        name: 'movie_edit',
        requirements: ['id' => '\d+'],
        methods: ['GET', 'POST']
    )]

    public function edit(int $id, Request $request): Response
    {
        $movie = $this->movieRepository->find($id);
        $form = $this->createForm(MovieFormType::class, $movie);
        $form->handleRequest($request);
        $imagePath = $form->get('imagePath')->getData();

        if ($form->isSubmitted() && $form->isValid()) {
            if ($imagePath) {
                if ($movie->getImagePath() !== null) {
                    if (file_exists(
                        $this->getParameter('kernel.project_dir') . '/public/' . $movie->getImagePath()
                    )) {
                        $this->getParameter('kernel.project_dir') . '/public/' . $movie->getImagePath();
                        $newFileName = uniqid() . '.' . $imagePath->guessExtension();

                        try {
                            $imagePath->move(
                                $this->getParameter('kernel.project_dir') . '/public/uploads',
                                $newFileName
                            );
                        } catch (FileException $e) {
                            return  new Response($e->getMessage());
                        }

                        $movie->setImagePath('/uploads/' . $newFileName);
                        $movie->setTitle($form->get('title')->getData());
                        $movie->setReleaseYear($form->get('releaseYear')->getData());
                        $movie->setDescription($form->get('description')->getData());
                        $this->em->flush();
                        return $this->redirectToRoute('movies');
                    }
                    else {
                        dd("file doesn't exist");
                    }
                }
            } else {
                $movie->setTitle($form->get('title')->getData());
                $movie->setReleaseYear($form->get('releaseYear')->getData());
                $movie->setDescription($form->get('description')->getData());
                $this->em->flush();
                return $this->redirectToRoute('movies');
            }
        }

        return $this->render("movies/edit.html.twig", [
            'movie' => $movie,
            'form' => $form->createView()
        ]);
    }

    // DELETE PAGE

    #[Route(
        '/movies/delete/{id}',
        name: 'movie_delete',
        requirements: ['id' => '\d+'],
        methods: ['GET', 'DELETE']
    )]

    public function delete(int $id): Response
    {
        $movie = $this->movieRepository->find($id);
        $this->em->remove($movie);
        $this->em->flush();
        return $this->redirectToRoute('movies');
    }
}