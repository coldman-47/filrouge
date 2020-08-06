<?php

namespace App\Controller;

use App\Entity\GroupeCompetence;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;

class CompetenceController extends AbstractController
{
    /**
     * @Route(
     *  "/api/admin/grpcompetences/",
     *  name="add_grp_competences",
     *  methods = {"POST"},
     *  defaults={
     *      "_api_resource_class" = GroupeCompetence::class,
     *      "_api_collection_operation_name" = "add_grp_competences"
     *  }
     * )
     */
    public function getCompetence(Request $request)
    {
        dd($request->getContent());
    }
}
