<?php

namespace App\Controller;

use App\Form\LoginType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;

class SecurityController extends AbstractController
{
    /**
     * @param AuthenticationUtils $authenticationUtils
     *
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function login(AuthenticationUtils $authenticationUtils)
    {
        $form = $this->createForm(LoginType::class, ['username' => $authenticationUtils->getLastUsername()], [
            'action' => $this->generateUrl('login_check'),
        ]);

        if ($error = $authenticationUtils->getLastAuthenticationError()) {
            $this->addFlash('danger', $error->getMessage());
        }

        return $this->render(
            'Security/login.html.twig',
            [
                'error' => $error,
                'form' => $form->createView()
            ]
        );
    }

    /**
     * @throws \Exception
     */
    public function logout()
    {
        throw new \Exception('Error');
    }
}
