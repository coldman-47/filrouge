<?php

namespace App\Controller;

use App\Entity\Tag;
use App\Entity\GroupeTag;
use Doctrine\ORM\Mapping\Entity;
use App\Repository\TagRepository;
use App\Repository\GroupeTagRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class TagController extends AbstractController
{
    /**
     * @Route(
     *  "/api/admin/grptags/",
     *  name="add_grp_tags",
     *  methods = {"POST"},
     *  defaults={
     *      "_api_resource_class" = GroupeTag::class,
     *      "_api_collection_operation_name" = "add_grp_tags"
     *  }
     * )
     */
    public function addGrpTag(Request $request, SerializerInterface $serializer, EntityManagerInterface $manager)
    {
        $grpCompeTab = json_decode($request->getContent(), true);
        $tagsTab = $grpCompeTab['tags'];
        foreach ($tagsTab as $tag) {
            $tags[] = $serializer->denormalize($tag, Tag::class);
        }
        $grpTags = $serializer->denormalize($grpCompeTab, GroupeTag::class);

        foreach ($tags as $tag) {

            $grpTags->addTag($tag);
            $tag->addGroupeTag($grpTags);
            $manager->persist($tag);
        }

        $manager->persist($grpTags);
        $manager->flush();

        return new JsonResponse("success", Response::HTTP_CREATED, [], true);
    }

    /**
     * @Route(
     *  "/api/admin/tags/",
     *  name="addtags",
     *  methods = {"POST"},
     *  defaults={
     *      "_api_resource_class" = Tag::class,
     *      "_api_collection_operation_name" = "add_tags"
     *  }
     * )
     */
    public function addTag(Request $request, SerializerInterface $serializer, EntityManagerInterface $manager, GroupeTagRepository $repo)
    {
        $tagTab = json_decode($request->getContent(), true);
       
        $tag = $serializer->denormalize($tagTab, Tag::class);
        
        $manager->persist($tag);
        $manager->flush();

        return new JsonResponse("success", Response::HTTP_CREATED, [], true);
    }
    
    /**
     * @Route(
     * "/api/admin/grptags/{id}/",
     *  name="update_groupetag",
     *  methods={"PUT"},
     *  defaults={
     *      "_api_resource_class" = GroupeTag::class,
     *      "_api_item_operation_name" = "update_groupetags"
     *      }
     *    )
     */
    public function updateGroupeTag(Request $request, SerializerInterface $serializer, EntityManagerInterface $manager)
    {
        //Récuperation de l'objet dans la base de données
        $grptag = $request->attributes->get("data");
        //récuperation de l'objet à modifier dans le body
        $grptag_update = json_decode($request->getContent(), true);
 
         foreach ($grptag_update as $key => $value) {
             
             if($key==="Tag" || $key === "dropTag"){
                 
                 $setter = "add".ucfirst($key);
                 if ($key === "dropTag") {
                     
                     $setter = "removeTag";
                 }
                 foreach ($value as $val) {
                     
                     $val = $serializer->denormalize($val, Tag::class);
                     $grptag->$setter($val);
                 }
             }else{
                 
                 $setter = "set".ucfirst($key);
                 $grptag->$setter($value);
             }
         }
        $manager->persist($grptag);
        $manager->flush();
 
        return new JsonResponse("success", Response::HTTP_CREATED, [], true);
       
    }
}
