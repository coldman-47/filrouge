<?php

namespace App\Controller;

use App\Entity\Brief;
use App\Entity\Groupe;
use App\Entity\EtatBrief;
use App\Repository\GroupeRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class BriefController extends AbstractController
{
    /**
     * @Route(
     *  "/api/admin/brief/",
     *  name="add_brief",
     *  methods = {"POST"},
     *  defaults={
     *      "_api_resource_class" = Brief::class,
     *      "_api_collection_operation_name" = "add_brief"
     *  }
     * )
     */
    public function addBrief(Request $request, SerializerInterface $serializer,EntityManagerInterface $manager)
    {
        $briefTab=$request->request->All();
        $brief=$serializer->denormalize($briefTab, Brief::class);
        $groupe=$serializer->denormalize($briefTab['Groupe'], Groupe::class);
        $etatBrief=new EtatBrief();
        $etatBrief->setGroupe($groupe);
        $etatBrief->setStatut('En cours');
        $brief->addEtatBrief($etatBrief);
        $manager->persist($brief);
        $manager->persist($etatBrief);
        $manager->flush();
        dd($brief);
    }
}
