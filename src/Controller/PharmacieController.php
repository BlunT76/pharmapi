<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Serializer\SerializerInterface;

use Doctrine\ORM\EntityManagerInterface;

use App\Repository\PharmacieRepository;
use App\Entity\Pharmacie;

class PharmacieController extends AbstractController
{
    /**
     * retourne toute les pharmacies
     */
    public function index(PharmacieRepository $pharmacieRepository, SerializerInterface $serializer): JsonResponse
    {
        // on recupere toutes les pharmacies
        $pharmacies = $pharmacieRepository->findAll();

        // on convertit le resultat en json
        $data = $serializer->serialize($pharmacies, 'json');

        // on renvoie la liste des pharmacies
        return new JsonResponse($data, 200, [], true);
    }

    /**
     * ajoute une pharmacie
     */
    public function post(Request $request, PharmacieRepository $pharmacieRepository): JsonResponse
    {
        // on recupere le body json
        $data = $request->toArray();

        // on recupere le manager de model
        $entityManager = $this->getDoctrine()->getManager();

        // on instancie une nouvelle pharmacie
        $pharmacie = new Pharmacie();

        // on set nos proprietes
        $pharmacie->setNom($data['nom']);
        $pharmacie->setQuartier($data['quartier']);
        $pharmacie->setVille($data['ville']);
        $pharmacie->setGarde($data['garde']);

        // on enregistre 
        $entityManager->persist($pharmacie);

        // et on lance la requete a la database
        $entityManager->flush();

        // on renvoie une reponse
        return $this->json(['pharmacies' => 'Saved new pharmacie with id '.$pharmacie->getId()]);
    }

    /**
     * modifie une pharmacie
     */
    public function update(int $id, Request $request, PharmacieRepository $pharmacieRepository): JsonResponse
    {
        // on recupere le body json
        $data = $request->toArray();

        // on recupere le manager de model
        $entityManager = $this->getDoctrine()->getManager();

        // on recupere la pharmacie a modifier
        $pharmacie = $pharmacieRepository->find($id);

        // on verifie que cette pharmacie existe
        if (!$pharmacie) {
            throw $this->createNotFoundException(
                'No pharmacie found for id '.$id
            );
        }

        // on modifie la pharmacie
        $pharmacie->setNom($data['nom']);
        $pharmacie->setQuartier($data['quartier']);
        $pharmacie->setVille($data['ville']);
        $pharmacie->setGarde($data['garde']);

        // on enregistre 
        $entityManager->persist($pharmacie);

        // et on lance la requete a la database
        $entityManager->flush();

        // on renvoie une reponse
        return $this->json(['pharmacies' => 'Updated pharmacie with id '.$pharmacie->getId()]);
    }

    /**
     * supprime une pharmacie
     */
    public function delete(int $id, PharmacieRepository $pharmacieRepository): JsonResponse
    {
        // on recupere la pharmacie a modifier
        $pharmacie = $pharmacieRepository->find($id);

        // on verifie que cette pharmacie existe
        if (!$pharmacie) {
            throw $this->createNotFoundException(
                'No pharmacie found for id '.$id
            );
        }

        // on recupere le manager de model
        $entityManager = $this->getDoctrine()->getManager();

        // et on la supprime
        $entityManager->remove($pharmacie);

        // et on lance la requete a la database
        $entityManager->flush();

        // on renvoie une reponse
        return $this->json(['pharmacies' => 'Deleted pharmacie with id '.$id]);
    }

    /**
     * retourne les pharmacies de garde
     */
    public function garde(PharmacieRepository $pharmacieRepository, SerializerInterface $serializer): JsonResponse
    {
        // on determine le jour
        $day = date('l');

        // on le traduit en francais
        $days = array('Sunday', 'Monday', 'Tuesday', 'Wednesday','Thursday','Friday', 'Saturday');

        $frenchDays = array('dimanche', 'lundi', 'mardi', 'mercredi','jeudi','vendredi', 'samedi');

        $key = array_search($day, $days);

        $day = $frenchDays[$key];

        // on recupere toutes les pharmacies de garde
        $pharmacies = $pharmacieRepository->findByGarde($day);

        // on convertit le resultat en json
        $data = $serializer->serialize($pharmacies, 'json');

        // on renvoie la liste des pharmacies de garde
        return new JsonResponse($data, 200, [], true);
    }
}
