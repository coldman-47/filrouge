<?php

namespace App\Controller;

use App\Entity\Chat;
use App\Entity\Promo;
use App\Repository\UserRepository;
use App\Repository\PromoRepository;
use App\Repository\ApprenantRepository;
use App\Repository\ChatRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class ChatController extends AbstractController
{
    /**
     * @Route(
     * "api/users/promo/{id}/apprenant/{id1}/chats", 
     * name="chat_post",
     * methods={"POST"},
     * defaults={
     *      "_api_resource_class" = Promo::class,
     *      "_api_collection_operation_name" = "chat_post"
     *  }
     * )
     */
    public function addComment($id,$id1, Request $request, SerializerInterface $serializer, UserRepository $repo_user, PromoRepository $repo_promo, ApprenantRepository $repo_apprenant, EntityManagerInterface $manager)
    {
        $json = json_decode($request->getContent(), true);
        $promos = $repo_promo->find($id);
        if ($promos) {
            $user = $repo_user->find($id1);
            //testons si l'utilisateurs existe est que c'est un apprenant
            if ($user->getProfil()->getLibelle()=="APPRENANT") {

                $chat = $serializer->denormalize($json, Chat::class);

                $manager->persist($chat);
                $manager->flush();
                return new JsonResponse("success", Response::HTTP_CREATED, [], true);

            }else {
                
                  return new Response("l'utilisateur n'est pas un apprenant ou n'existe pas");
            }
        }else {   

            return new Response("la promo n'existe pas");
       }
    }

     /**
     * @Route(
     * "api/users/promo/{id}/apprenant/{id1}/chats", 
     * name="chat_get",
     * methods={"GET"},
     * defaults={
     *      "_api_resource_class" = Promo::class,
     *      "_api_collection_operation_name" = "chat_get"
     *  }
     * )
     */
    public function collectComment($id,$id1, UserRepository $repo_user, PromoRepository $repo_promo, ChatRepository $repo_chats)
    {
        $promo = $repo_promo->find($id);
        $chats = $repo_chats->findAll();
        if ($promo) {
            $user = $repo_user->find($id1);
            //testons si l'utilisateurs existe
            if ($user->getProfil()->getLibelle()=="APPRENANT") {
                foreach ($chats as $chat) {
                    if($chat->getUser()->getId() == $id1){
                        if($chat->getPromo()->getId() == $id){
                            $bon[] = $chat;
                        }  
                    }
                }
                return $this->json($bon, 200,["groups"=>"chat:read"]);
                
            }else{
                
                  return new Response("l'utilisateur n'est pas un apprenant ou n'existe pas");
            }
        }else{   

            return new Response("la promo n'existe pas");
       }
    }
}
