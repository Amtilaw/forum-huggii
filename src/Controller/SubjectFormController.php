<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
//Form
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
//Entity
use App\Entity\Subject;
use App\Entity\Message;
//Repository
use App\Repository\SubjectRepository;
use App\Repository\MessageRepository;


class SubjectFormController extends AbstractController
{
    /**
     * @Route("/subject/form", name="app_subject_form")
     */
    public function index(Request $request, SubjectRepository $subject): Response
    {
        //Create a form 
        $subject = new Subject();
        $message = new Message();

        $form = $this->createFormBuilder([$subject, $message])
            ->add('name', TextType::class, [
                'label' => 'Nom du sujet',
                'attr' => [
                    'placeholder' => 'Nom du sujet',
                    'class' => 'form-control'
                ]
            ])
            ->add('contenue', TextareaType::class, [
                'label' => 'Message',
                'attr' => [
                    'placeholder' => 'Premier message du sujet',
                    'class' => 'form-control'
                ]
            ])
            ->add('save', SubmitType::class, array('label' => 'Envoyer'))
            ->getForm();
        //Handle the form request
        $form->handleRequest($request);
        //Check if the form is submitted
        if ($form->isSubmitted() && $form->isValid()) {
            //Get the data from the form
            $name = $form['name']->getData();
            $messageText = $form['contenue']->getData();
            //Set the data to the entity
            $subject->setName($name);
            $subject->setDateCreated(new \DateTime());
            $message->setContenue($messageText);
            $message->setDateCreated(new \DateTime());
            $message->setSubject($subject);
            //Save the data to the database
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($subject);
            $entityManager->persist($message);
            $entityManager->flush();
            //Redirect to the index page
            return $this->redirectToRoute('app_home');
        }
        //Render the form
        return $this->render('subject_form/index.html.twig', [
            'form' => $form->createView(),
        ]);
        
       
    }
}
