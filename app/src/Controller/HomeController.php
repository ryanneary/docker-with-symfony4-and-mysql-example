<?php
namespace App\Controller;

use App\Entity\User;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class HomeController extends AbstractController
{
    public function homepage(ObjectManager $objectManager)
    {
        /** @var User $user */
        $user = $objectManager->getRepository(User::class)->findOneBy([]);
        if (!$user) {
            throw new \Exception('A user was expected');
        }
        return $this->render('example/homepage.html.twig', [
            'username' => $user->getUsername(),
        ]);
    }
}