<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\UserType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;

class UserController extends AbstractController
{
    public function userList()
    {
        $userList = $this->getDoctrine()->getRepository(User::class)->findAll();

        return $this->render('User/users.html.twig', [
            'users' => $userList
        ]);
    }

    /**
     * @param Request $request
     * @param User $user
     *
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|\Symfony\Component\HttpFoundation\Response
     */
    public function editUser(Request $request, User $user)
    {
        $form = $this->createForm(UserType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            
            $this->getDoctrine()->getManager()->flush();

            return $this->redirect($this->generateUrl('user_list'));
        }

        return $this->render('User/user.html.twig', [
            'form' => $form->createView(),
        ]);
    }
}
