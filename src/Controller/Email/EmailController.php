<?php

namespace App\Controller\Email;

use App\Entity\Product;
use App\Entity\User;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class EmailController extends AbstractController
{
    public function newProductEmail(\Swift_Mailer $swiftMailer)
    {
        $products = $this->getDoctrine()->getRepository(Product::class)->getLastDayProduct();

        $superAdminList = $this->getDoctrine()->getRepository(User::class)->findBy(['role' => 'ROLE_SUPER_ADMIN']);

        /** @var User $admin */
        foreach ($superAdminList as $admin) {
            $message = (new \Swift_Message('Products'))
                ->setFrom('send@example.com')
                ->setTo($admin->getEmail())
                ->setBody(
                    $this->renderView(
                        'Email/product.html.twig',
                        ['products' => $products]
                    ),
                    'text/html'
                )
            ;

            $swiftMailer->send($message);
        }

        return $this->json('Message Send.');
    }
}
