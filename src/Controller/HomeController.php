<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Entity\Subject;
use App\Repository\SubjectRepository;
use App\Entity\Message;
use App\Repository\MessageRepository;


class HomeController extends AbstractController
{
    /**
     * @Route("/", name="app_home")
     */
    public function index(SubjectRepository $subjects, MessageRepository $message): Response
    {
        //Get all subject from database
        $subjects = $subjects->findAllWithLastMessage();

        return $this->render('home/index.html.twig', [
            'controller_name' => 'HomeController',
            'subjects' => $subjects,
        ]);
    }
}
