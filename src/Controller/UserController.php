<?php

namespace App\Controller;

use App\Entity\Profil;
use App\Entity\User;
use Doctrine\Common\Persistence\ObjectManager;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Symfony\Component\Serializer\SerializerInterface;

class UserController extends AbstractController
{
    /**
     * @Route(path="/api/admin/users/", name="user", methods = {"POST"})
     */
    public function addUser(EntityManagerInterface $manager, Request $request, UserPasswordEncoderInterface $encoder, SerializerInterface $serializer)
    {
        $newUser = $request->request->all();
        $uploadedFile = $request->files->get('avatarFile');
        if (!$uploadedFile) {
            throw new BadRequestHttpException('Un utilisateur doit être identifié par une photo');
        }
        $image = fopen($uploadedFile->getRealPath(), 'r');
        $type = $uploadedFile->getMimeType();
        $newUser['avatar'] = $image;
        $newUser['avatarType'] = $type;

        $user = $serializer->denormalize($newUser, User::class, true);
        $user->setPassword($encoder->encodePassword($user, $newUser['password']));
        $user->setProfil($manager->getRepository(Profil::class)->findOneBy(['libelle' => $newUser['profils']]));

        $manager->persist($user);
        $manager->flush();

        // $photo = stream_get_contents($image);
        // $avatar = base64_encode($photo);
        // echo "<img src='data:$type;base64,$avatar'>";

        return new JsonResponse("success", Response::HTTP_CREATED, [], true);
    }

    /**
     * @Route(path="/api/admin/users/", name="getusers", methods = {"GET"})
     */
    public function getUsers(SerializerInterface $serializer, EntityManagerInterface $manager)
    {
        $users = $manager->getRepository(User::class)->findAll();
        foreach ($users as $user) {
            $user->setAvatar(base64_encode(stream_get_contents($user->getAvatar())));
        }
        $usersJson = $serializer->normalize($users, "json");
        $user = $usersJson[9];
        return new JsonResponse($usersJson);
    }

    public function index()
    {
        return $this->render('user/index.html.twig', [
            'controller_name' => 'UserController',
        ]);
    }
}
