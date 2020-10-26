<?php


namespace App\Controller;


use App\Entity\User;
use phpDocumentor\Reflection\DocBlock\Tags\Return_;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;


class UserController extends AbstractController
{
/**
 * Formulaire d'inscription d'un User
 * @Route("/membre/inscription", name="user_create", methods={"GET|POST"})
 * @param Request $request
 */

public function createUser(Request $request, UserPasswordEncoderInterface $encoder){
#1. Création d'un nouvel user
    $user = new User();
    $user->setRoles(['ROLE_USER']);
#2. Création du formulaire
    $form =$this ->createFormBuilder($user)
        ->add('firstname', TextType::class)
        ->add('lastname' ,TextType::class)
        ->add('email', EmailType::class)
        ->add('password', PasswordType::class)
        ->add('submit', SubmitType::class)
        ->getForm();

    $form->handleRequest($request);
    if ($form->isSubmitted() && $form->isValid()){

     #3. TODO: Encodage du MDP
        $user->setPassword(
        $encoder->encodePassword($user,$user->getPassword())
    );
        #4. TODO: Sauvegarde en BDD
    $em=$this->getDoctrine()->getManager();
    $em->persist($user);
    $em->flush();
        #5. TODO: Notification Flash
    $this->addFlash('notice', 'Félicitation pour votre inscription ! ');
        #6.Redirection FIXME modifier l'url vers page de connection
    return $this->redirectToRoute('index');

    }
        #7. Transmission de la vue
    return $this->render('user/create.html.twig', [
        'form'=>$form->createView()
    ]);
}
}