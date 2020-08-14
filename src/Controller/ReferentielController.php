<?php

namespace App\Controller;

use App\Entity\GroupeCompetence;
use App\Entity\Referentiel;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use App\Repository\GroupeCompetenceRepository;
use App\Repository\ReferentielRepository;
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
     *  name="getreferentiel",
     *  methods = {"GET"},
     *  defaults={
     *      "_api_resource_class" = Referentiel::class,
     *      "_api_collection_operation_name" = "getreferentiel"
     *  }
     * )
     */
    public function getReferentiel(ReferentielRepository $repo, SerializerInterface $serializer)
    {
        $referentiels = $repo->findBy(['deleted' => false]);
        $referentielJson = $serializer->serialize($referentiels, "json");
        return new JsonResponse($referentielJson, Response::HTTP_CREATED, [], true);
        dd($referentielJson);
    }

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
    public function addReferentiel(Request $request, SerializerInterface $serializer, EntityManagerInterface $manager)
    {
        $referentielTab = $request->request->all();
        $uploadedFile = $request->files->get('programme');
        if ($uploadedFile) {
            $programme = fopen($uploadedFile->getRealPath(), 'r');
            $referentielTab['programme'] = $programme;
        }
        $grpcmptIds = explode(',', $referentielTab['grpCompetences']);
        $prefix = '/api/admin/grpcompetences/';
        foreach ($grpcmptIds as $val) {
            $grpCompetences[] = $prefix . trim($val);
        }
        $referentielTab['grpCompetences'] = $grpCompetences;
        $referentiel = $serializer->denormalize($referentielTab, Referentiel::class);

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
    public function setReferentiel(Request $request, EntityManagerInterface $manager)
    {
        $referentiel = $request->attributes->get('data');
        $data = $this->putFormData($request, 'programme');
        foreach ($data as $k => $v) {
            $setter = 'set' . ucfirst($k);
            if (!method_exists($referentiel, $setter)) {
                return new Response("La méthode $setter() n'éxiste pas dans l'entité Referentiel");
            }
            $referentiel->$setter($v);
        }
        $manager->persist($referentiel);
        $manager->flush();
        return new JsonResponse("success", Response::HTTP_CREATED, [], true);
    }

    /**
     * @Route(
     *  "/api/admin/referentiels/{id}",
     *  name="delreferentiel",
     *  methods = {"DELETE"},
     *  defaults={
     *      "_api_resource_class" = Referentiel::class,
     *      "_api_item_operation_name" = "delreferentiel"
     *  }
     * )
     */
    public function delReferentiel(Request $request, EntityManagerInterface $manager)
    {
        $ref = $request->attributes->get('data');
        $ref->setDeleted(true);
        $manager->persist($ref);
        $manager->flush();
        return new JsonResponse("success", Response::HTTP_CREATED, [], true);
    }

    function putFormData(Request $request, string $fileName = null)
    {
        $raw = $request->getContent();
        $delimiter = "multipart/form-data; boundary=";
        $boundary = "--" . explode($delimiter, $request->headers->get("content-type"))[1];
        $elements = str_replace([$boundary, "Content-Disposition: form-data;", "name="], "", $raw);
        $elementsTab = explode("\r\n\r\n", $elements);
        $data = [];
        for ($i = 0; isset($elementsTab[$i + 1]); $i += 2) {
            $key = str_replace(["\r\n", ' "', '"'], '', $elementsTab[$i]);
            if (strchr($key, $fileName)) {
                $stream = fopen('php://memory', 'r+');
                fwrite($stream, base64_encode($elementsTab[$i + 1]));
                rewind($stream);
                $data[$fileName] =  $stream;
                // echo "<img src='data:image;base64," . $data[$fileName] . "'>";
            } else {
                $val = str_replace(["\r\n", "--"], '', $elementsTab[$i + 1]);
                $data[$key] =  $val;
            }
        }
        return $data;
    }
}
