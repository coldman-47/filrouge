<?php

namespace App\Controller;

use App\Entity\Promo;
use App\Entity\Groupe;
use App\Entity\Apprenant;
use App\Repository\PromoRepository;
use App\Repository\GroupeRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class GroupeController extends AbstractController
{
    
    /**
     * @Route(
     *  "/api/admin/groupes",
     *  name="add_groupes",
     *  methods = {"POST"},
     *  defaults={
     *      "_api_resource_class" = Groupe::class,
     *      "_api_collection_operation_name" = "add_groupes"
     *  }
     * )
     */
    public function addGroupe(Request $request, SerializerInterface $serializer,EntityManagerInterface $manager, PromoRepository $repo)
    {    
        //recuperation des données en json
        $groupeTab = json_decode($request->getContent(), true);

            $groupe=new groupe();
            foreach($groupeTab as $key=>$val){
                $setter="set".ucfirst($key);
                if($key==="promo"){
                    $val=$serializer->denormalize($val, Promo::class);
                }
                $groupe->$setter($val);
            }
            $manager->persist($groupe);
            $manager->flush();

        return new JsonResponse("success", Response::HTTP_CREATED, [], true);
    }
    /**
     * @Route(
     *  "/api/admin/groupes/{id}",
     *  name="update_groupe",
     *  methods = {"PUT"},
     *  defaults={
     *      "_api_resource_class" = Groupe::class,
     *      "_api_item_operation_name" = "update_groupe"
     *  }
     * )
     */
    public function updateGroupe(Request $request, SerializerInterface $serializer,EntityManagerInterface $manager)
    {
        $groupe=$request->attributes->get("data");

        $update_groupe = json_decode($request->getContent(), true);
        // dd( $update_groupe);
        foreach($update_groupe as $key=>$values){
        //ajout d'un apprenant
            if($key==="apprenant"){
                $setter="add".ucfirst($key);

                foreach($values as $val){
                    $val=$serializer->denormalize($val, Apprenant::class);
                    $groupe->$setter($val);
                }
            }else
            //lister apprenants
            {
                $setter="set".ucfirst($key); 
                $groupe->$setter($values);   
            }
        }
        dd($groupe);
        $manager->persist($groupe);
        $manager->flush();

        return new JsonResponse("success", Response::HTTP_CREATED, [], true);
    } 


    /**
     * @Route(
     *  "/api/admin/groupes/{id}/appreant",
     *  name="delete_groupe",
     *  methods = {"delete"},
     *  defaults={
     *      "_api_resource_class" = Groupe::class,
     *      "_api_item_operation_name" = "delete_groupe"
     *  }
     * )
     */
    public function deleteGroupe(Request $request, SerializerInterface $serializer,EntityManagerInterface $manager)
    {
        $groupe=$request->attributes->get("data");

        $update_groupe = json_decode($request->getContent(), true);
        // dd( $update_groupe);
        foreach($update_groupe as $key=>$values){
        //suppression d'un apprenant
            if($key==="apprenant"){
                $setter="remove".ucfirst($key);

                foreach($values as $val){
                    $val=$serializer->denormalize($val, Apprenant::class);
                    $groupe->$setter($val);
                }
            }else
            //lister apprenants
            {
                $setter="set".ucfirst($key); 
                $groupe->$setter($values);   
            }
        }
        dd($groupe);
        $manager->persist($groupe);
        $manager->flush();

        return new JsonResponse("success", Response::HTTP_CREATED, [], true);
    } 
    
}

