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
//Form
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;

class MessageController extends AbstractController
{
    /**
     * @Route("/message{subjectId}", name="app_message")
     */
    public function index(Request $request, $subjectId, SubjectRepository $subject): Response
    {
        //Create a form to send a message
        $message = new Message();
        $form = $this->createFormBuilder($message)
            ->add('contenue', TextareaType::class, [
                'label' => 'Message',
                'attr' => [
                    'placeholder' => 'Votre message',
                    'class' => 'form-control'
                ]
            ])
            ->add('save', SubmitType::class, ['label' => 'Envoyer'])
            ->getForm();
        //Handle the form request
        $form->handleRequest($request);
        //Check if the form is submitted
        if ($form->isSubmitted() && $form->isValid()) {
            //Get the data from the form
            $messageText = $form['contenue']->getData();
            //Set the data to the entity
            $message->setContenue($messageText);
            $message->setDateCreated(new \DateTime());
            $message->setSubject($subject->find($subjectId));
            //Persist the data to the database
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($message);
            $entityManager->flush();
            //Redirect to subject page
            return $this->redirectToRoute('app_subject_default', ['subjectId' => $subjectId]);
        }
        //Render form 
        return $this->render('message/index.html.twig', [
            'form' => $form->createView(),
            'subject' => $subject->find($subjectId),
        ]);
    }
}
