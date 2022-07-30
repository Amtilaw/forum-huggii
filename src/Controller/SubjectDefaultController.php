<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
//request 
use Symfony\Component\HttpFoundation\Request;
//Entity
use App\Entity\Subject;
use App\Entity\Message;
//Repository
use App\Repository\SubjectRepository;
use App\Repository\MessageRepository;

class SubjectDefaultController extends AbstractController
{
    /**
     * @Route("/subject/default{subjectId}", name="app_subject_default")
     */
    public function index(SubjectRepository $subject, MessageRepository $message, $subjectId): Response
    {
        //Get the subject from the database
        $subject = $subject->find($subjectId);
        //Get all messages from the database
        $messages = $message->findBy(['subject' => $subject], ['date_created' => 'DESC']);
        
        return $this->render('subject_default/index.html.twig', [
            'controller_name' => 'SubjectDefaultController',
            'subject' => $subject,
            'messages' => $messages,
        ]);
    }
}
