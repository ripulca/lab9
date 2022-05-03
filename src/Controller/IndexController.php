<?php

namespace App\Controller;

use App\Entity\Photo;
use App\Form\PhotoType;
use App\Repository\PhotoRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

#[Route('/')]
class IndexController extends AbstractController
{
    #[Route('/', name: 'app_index', methods: ['GET'])]
    public function index(PhotoRepository $photoRepository, $page=1): Response
    {
        $session = new Session();
        $name = $session->get('name') ?? null;
        $photos=$photoRepository->getAllPhotos();
        $totalPhotosReturned = $photos->getIterator()->count();
        $totalPhotos = $photos->count();
        $iterator = $photos->getIterator();
        $maxPages=0;
        if($totalPhotos!=0){
            $maxPages = ceil($totalPhotos / $totalPhotosReturned);
        }
        // $pages=$photos->getIterator()->count();
        return $this->render('index.html.twig', [
            'photos' => $photos,
            'maxPages' => $maxPages,
            'thisPage'=>$page,
            'userName'=>$name
        ]);
    }

    #[Route('/{page}', name: 'app_pages_index', requirements: ['page' => '\d+'], methods: ['GET'])]
    public function indexPage(PhotoRepository $photoRepository, $page = 1): Response
    {
        $session = new Session();
        $name = $session->get('name') ?? null;
        $photos=$photoRepository->getAllPhotos($page);
        $totalPhotosReturned = $photos->getIterator()->count();
        $totalPhotos = $photos->count();
        $iterator = $photos->getIterator();
        $maxPages = ceil($totalPhotos / $totalPhotosReturned);
        
        return $this->render('index.html.twig', [
            'photos' => $photoRepository->findAll(),
            'maxPages' => $maxPages,
            'thisPage'=>$page,
            'userName'=>$name
        ]);
    }
}
