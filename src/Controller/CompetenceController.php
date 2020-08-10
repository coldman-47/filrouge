<?php

namespace App\Controller;

use App\Entity\Niveau;
use App\Entity\Competence;
use App\Entity\GroupeCompetence;
use App\Repository\CompetenceRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use App\Repository\GroupeCompetenceRepository;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

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
    public function addGrpCompetence(Request $request, SerializerInterface $serializer, EntityManagerInterface $manager)
    {
        $grpCompeTab = json_decode($request->getContent(), true);
        $competencesTab = $grpCompeTab['competences'];
        foreach ($competencesTab as $k => $competence) {
            $competences[] = $serializer->denormalize($competence, Competence::class);
            foreach ($competence['niveaux'] as $level => $niveau) {
                $niveau['niveau'] = $level + 1;
                $niveaux[] = $serializer->denormalize($niveau, Niveau::class);
                $niveaux[$level]->setCompetence($competences[$k]);
            }
        }

        $grpCompetences = $serializer->denormalize($grpCompeTab, GroupeCompetence::class);

        foreach ($competences as $competence) {
            $grpCompetences->addCompetence($competence);
            $competence->addGroupeCompetence($grpCompetences);
            $manager->persist($competence);
        }
        foreach ($niveaux as $niveau) {
            $manager->persist($niveau);
        }

        $manager->persist($grpCompetences);
        $manager->flush();

        return new JsonResponse("success", Response::HTTP_CREATED, [], true);
    }

    /**
     * @Route(
     *  "/api/admin/competences/",
     *  name="addcompetences",
     *  methods = {"POST"},
     *  defaults={
     *      "_api_resource_class" = Competence::class,
     *      "_api_collection_operation_name" = "addcompetences"
     *  }
     * )
     */
    public function addCompetence(Request $request, SerializerInterface $serializer, EntityManagerInterface $manager, GroupeCompetenceRepository $repo)
    {
        $competenceTab = json_decode($request->getContent(), true);

        $grpCompetences = $repo->findOneBy(['libelle' => $competenceTab["grpCompetences"]]);

        $competence = $serializer->denormalize($competenceTab, Competence::class);

        foreach ($competenceTab['niveaux'] as $level => $niveau) {
            $niveau['niveau'] = $level + 1;
            $niveaux[] = $serializer->denormalize($niveau, Niveau::class);
            $niveaux[$level]->setCompetence($competence);
            $manager->persist($niveaux[$level]);
        }
        dd($niveaux[1]);

        $competence->addGroupeCompetence($grpCompetences);

        $manager->persist($competence);
        $manager->persist($grpCompetences);
        $manager->flush();

        return new JsonResponse("success", Response::HTTP_CREATED, [], true);
    }

    /**
     * @Route(
     *  "/api/admin/competences/{id}/",
     *  name="setcompetences",
     *  methods = {"PUT"},
     *  defaults={
     *      "_api_resource_class"=Competence::class
     *  }
     * )
     */
    public function setCompetence(Request $request, SerializerInterface $serializer, EntityManagerInterface $manager, CompetenceRepository $repo, $id, GroupeCompetenceRepository $repoGC)
    {
        $competence = $repo->find($id);
        $grpCmpts = $competence->getGroupeCompetences();
        foreach ($grpCmpts as $val) {
            $grpCmpt[] = json_decode($serializer->serialize($val, "json"), true)["libelle"];
        }
        $nGC = sizeof($grpCmpt);
        $newCompeTab = json_decode($request->getContent(), true);

        //ajout des nouveaux groupes de compétences
        $newgrpCmpt = @$newCompeTab["groupeCompetences"];
        if (!empty($newgrpCmpt)) {
            $newgrpCmpTab = array_diff($newgrpCmpt, $grpCmpt);
            foreach ($newgrpCmpTab as $grpCmpTab) {
                $newGC = $repoGC->findOneBy(['libelle' => $grpCmpTab]);
                if (!empty($newGC)) {
                    $competence->addGroupeCompetence($newGC);
                    $nGC++;
                }
            }
        }

        //suppression des groupes de compétences
        $delgrpCmpTab = @$newCompeTab["dropGroupeCompetences"];
        if (!empty($delgrpCmpTab)) {
            $nGC -= sizeof($delgrpCmpTab);
            if ($nGC < 1) {
                foreach ($delgrpCmpTab as $val) {
                    $oldgrpCmpt = $repoGC->findOneBy(['libelle' => $val]);
                    if (!empty($oldgrpCmpt)) {
                        $exist = false;
                        foreach ($competence->getGroupeCompetences() as $v) {
                            if ($oldgrpCmpt == $v) {
                                $exist = true;
                                break;
                            }
                        }
                        if (!$exist) {
                            return new Response("Le groupe de compétence que vous essayé de supprimer n'est pas rattaché à cette compétence!");
                        }
                        $competence->removeGroupeCompetence($oldgrpCmpt);
                    } else {
                        return new Response("Le groupe de compétence que vous essayé de supprimer n'éxiste pas!");
                    }
                }
            } else {
                return new Response("Une compétence doit au moins appertenir à un groupe de compétence!");
            }
        }


        foreach ($newCompeTab as $key => $val) {
            if (in_array($key, ['libelle', 'descriptif'])) {
                $setter = "set" . ucfirst($key);
                $competence->$setter($val);
            }
        }

        $manager->persist($competence);
        $manager->flush();

        return new JsonResponse("success", Response::HTTP_CREATED, [], true);
    }

    function fetchFormData()
    {
        //     $raw = $request->getContent();
        //     $delimiter = "multipart/form-data; boundary=";
        //     $boundary = "--" . explode($delimiter, $request->headers->get("content-type"))[1];
        //     $elements = str_replace("name=", "", str_replace("Content-Disposition: form-data;", "", str_replace($boundary, "", $raw)));
        //     $elementsTab = explode("\r\n", $elements);
        //     $json = "{";
        //     for ($i = 1; isset($elementsTab[$i + 2]); $i += 4) {
        //         $json .= $elementsTab[$i] . ":" . $elementsTab[$i + 2] . ",";
        //     }
        //     $json .= "}";
        //     dd(($json));
    }
}
