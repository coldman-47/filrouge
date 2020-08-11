<?php

namespace App\Controller;

use App\Entity\Referentiel;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use App\Repository\GroupeCompetenceRepository;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Serializer\SerializerInterface;

class ReferentielController extends AbstractController
{
    /**
     * @Route(
     *  "/api/admin/referentiels/",
     *  name="addreferentiel",
     *  methods = {"POST"},
     *  defaults={
     *      "_api_resource_class" = Referentiel::class,
     *      "_api_collection_operation_name" = "addreferentiel"
     *  }
     * )
     */
    public function addReferentiel(Request $request, GroupeCompetenceRepository $repo, SerializerInterface $serializer, EntityManagerInterface $manager)
    {
        $referentielTab = json_decode($request->getContent(), true);

        $grpCompetences = $repo->findOneBy(['libelle' => $referentielTab["competences"]]);

        $referentiel = $serializer->denormalize($referentielTab, Referentiel::class);
        $referentiel->addGrpCompetence($grpCompetences);

        $manager->persist($referentiel);
        $manager->flush();

        return new JsonResponse("success", Response::HTTP_CREATED, [], true);
    }

    /**
     * @Route(
     *  "/api/admin/referentiels/{id}",
     *  name="setreferentiel",
     *  methods = {"PUT"},
     *  defaults={
     *      "_api_resource_class" = Referentiel::class,
     *      "_api_item_operation_name" = "setreferentiel"
     *  }
     * )
     */
    public function setReferentiel(Request $request)
    {
        $data = $this->fetchFormData($request, 'programme');
        dd(($data));
    }

    function fetchFormData(Request $request, string $fileName)
    {
        $raw = $request->getContent();
        $delimiter = "multipart/form-data; boundary=";
        $boundary = "--" . explode($delimiter, $request->headers->get("content-type"))[1];
        $elements = str_replace([$boundary, "Content-Disposition: form-data;", "name="], "", $raw);
        $elementsTab = explode("\r\n\r\n", $elements);
        $json = [];
        for ($i = 0; isset($elementsTab[$i + 1]); $i += 2) {
            $key = str_replace(["\r\n", ' "', '"'], '', $elementsTab[$i]);
            if (strchr($key, $fileName)) {
                $json[$fileName] =  base64_encode($elementsTab[$i + 1]);
                // echo "<img src='data:image;base64," . $json[$fileName] . "'>";
            } else {
                $val = str_replace(["\r\n", "--"], '', $elementsTab[$i + 1]);
                $json[$key] =  $val;
            }
        }
        return $json;
    }
}
