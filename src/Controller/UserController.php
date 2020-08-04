<?php

namespace App\Controller;

use App\Entity\User;
use App\Entity\Profil;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class UserController extends AbstractController
{
    /**
     * @Route(path="/api/admin/users/", name="post_user", methods = {"POST"})
     */

    // private $serializer, $encoder, $request;
    // public function __construct(SerializerInterface $serializer, UserPasswordEncoderInterface $encoder, Request $request)
    // {
    //     $this->serializer = $serializer;
    //     $this->encoder = $encoder;
    //     $this->request = $request;
    // }

    // function handler($nUser)
    // {
    //     $uploadedFile = $this->request->files->get('avatarFile');
    //     if (!$uploadedFile) {
    //         throw new BadRequestHttpException('Un utilisateur doit être identifié par une photo');
    //     }
    //     $image = fopen($uploadedFile->getRealPath(), 'r');
    //     $type = $uploadedFile->getMimeType();
    //     $newUser['avatar'] = $image;
    //     $newUser['avatarType'] = $type;

    //     $user = $this->serializer->denormalize($nUser, User::class, true);
    //     $user->setPassword($this->encoder->encodePassword($user, $nUser['password']));
    //     return $user;
    // }

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
        // echo "<img src='data:$type;base64,$avatar'>";

        return new JsonResponse("success", Response::HTTP_CREATED, [], true);
    }

    /**
     * @Route(
     * name="apprenant_list",
     * path="api/apprenants",
     * methods={"GET"},
     * defaults={
     * "_api_resource_class"=User::class,
     * "_api_collection_operation_name"="get_apprenants"
     * }
     * )
     */
    public function getApprenant(SerializerInterface $serializer, UserRepository $repo)
    {
        $apprenants = $repo->findByProfil("APPRENANT");
        $apprenantsJson = $serializer->serialize(
            $apprenants,
            "json",
            [
                "groups" => ["profil:read"]
            ]
        );
        return new JsonResponse($apprenantsJson, Response::HTTP_OK, [], true);
    }
    /**
     * @Route(
     * name="get_apprenant",
     * path="api/apprenants/{id}/",
     * methods={"GET"},
     * defaults={
     * "_api_resource_class"=User::class,
     * "_api_item_operation_name"="update_apprenants"
     * }
     * )
     */
    public function getOneApprenant(SerializerInterface $serializer, UserRepository $repo, $id)
    {
        $apprenants = $repo->findOneByProfil('APPRENANT', $id);
        $apprenantsJson = $serializer->serialize(
            $apprenants,
            "json",
            [
                "groups" => ["profil:read"]
            ]
        );
        return new JsonResponse($apprenantsJson, Response::HTTP_OK, [], true);
    }

    /**
     * @Route(
     * "/api/apprenants/",
     *  name="add_apprenant",
     * defaults={
     * "_api_resource_class"=User::class,
     * "_api_collection_operation_name"="add_apprenant"
     * }
     * )
     */
    public function addApprenant(EntityManagerInterface $manager, Request $request, UserPasswordEncoderInterface $encoder, SerializerInterface $serializer)
    {
        $newApprenant = $request->request->all();
        $uploadedFile = $request->files->get('avatarFile');
        if ($uploadedFile) {
            $image = fopen($uploadedFile->getRealPath(), 'r');
            $type = $uploadedFile->getMimeType();
            $newApprenant['avatar'] = $image;
            $newApprenant['avatarType'] = $type;
        } else {
            // throw new BadRequestHttpException('Un utilisateur doit être identifié par une photo');
        }

        $apprenant = $serializer->denormalize($newApprenant, User::class, true);
        $apprenant->setPassword($encoder->encodePassword($apprenant, $newApprenant['password']));
        $apprenant->setProfil($manager->getRepository(Profil::class)->findOneBy(['libelle' => 'APPRENANT']));

        $manager->persist($apprenant);
        $manager->flush();

        return new JsonResponse("success", Response::HTTP_CREATED, [], true);
    }

    /**
     * @Route(
     *  "/api/apprenants/{id}/",
     *  name="update_apprenant",
     *  defaults={
     *      "_api_resource_class"=User::class,
     *      "_api_item_operation_name"="update_apprenant"
     *  }
     * )
     */
    public function updateApprenant(Request $req, $id, SerializerInterface $serializer, EntityManagerInterface $manager)
    {
        $newApprenant = json_decode($req->getContent(), true);
        $apprenant = $req->attributes->get('data');
        // $apprenantJson = $serializer->serialize($apprenant, "json");
        // $apprenantTab = $serializer->decode($apprenantJson, "json");
        // foreach ($newApprenant as $k => $val) {
        //     foreach ($apprenantTab as $key => $v) {
        //         if ($k === $key) {
        //             $apprenantTab[$key] = $val;
        //         }
        //     }
        // }
        // $apprenantJson = $serializer->encode($apprenantTab, "json");
        // $apprenant = $serializer->deserialize($apprenantJson, User::class, "json", ['groups' => 'profil:read']);
        dd($apprenant->getUsername());
    }
}
