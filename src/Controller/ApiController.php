<?php

namespace App\Controller;

use App\Entity\Pokemon;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\Serializer;
use Symfony\Component\Serializer\Encoder\CsvEncoder;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;
use JMS\Serializer\SerializerBuilder;

class ApiController extends AbstractController
{
    /**
     * @Route("/api", name="api")
     */
    public function index(ParameterBagInterface $params, EntityManagerInterface $em)
    {
        $serialize = SerializerBuilder::create()->build();
        $serializer = new Serializer([new ObjectNormalizer()], [new CsvEncoder()]);
        $data = $serializer->decode(file_get_contents($params->get('directory')."/file/pokemon.csv"), 'csv');
        dd($data);
        foreach ($data as $item){
            $pokemon = new Pokemon();
            $pokemon->getName($item["Name"]);
            $pokemon->getType1($item["Type 1"]);
            $pokemon->getType2($item["Type 2"]);
            $pokemon->getTotal($item["Total"]);
            $pokemon->getHp($item["HP"]);
            $pokemon->getAttack($item["Attack"]);
            $pokemon->getDefense($item["Defense"]);
            $pokemon->getSpAtk($item["Sp"]);
            $pokemon->getSpDef($item["Sp"]);
            $pokemon->getSpeed($item["Speed"]);
            $pokemon->getGeneration($item["Generation"]);
            $pokemon->getLegendary($item["Legendary"]);
            $em -> persist($pokemon);
        }
        $em ->flush();
        return new JsonResponse($data, 404, [], false);
    }
}
