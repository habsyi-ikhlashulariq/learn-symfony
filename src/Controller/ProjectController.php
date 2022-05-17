<?php

namespace App\Controller;

use App\Entity\Project;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class ProjectController extends AbstractController
{
    /**
     * @Route("/project", name="index_project", methods={"GET"})
     */
    public function index(): Response
    {
        return $this->json([
            'message' => 'Welcome to your new controller!',
            'path' => 'src/Controller/ProjectController.php',
        ]);
    }
    
    /**
     * @Route("/project/showAll", name="show_project", methods={"GET"})
     */
    public function showAll(): Response
    {
        $products = $this->getDoctrine()
            ->getRepository(Project::class)
            ->findAll();
 
        $data = [];
 
        foreach ($products as $product) {
           $data[] = [
               'id' => $product->getId(),
               'name' => $product->getName(),
               'description' => $product->getDescription(),
           ];
        }

        $response = [
            "code" => 200,
            "message" => "Success get Data",
            "data" => $data
        ];

        return $this->json($response);
    }

    /**
     * @Route("/project", name="add_project", methods={"POST"})
     */

    public function new(Request $request): Response
    {
        $bodyrequest = $request->getContent();
        $datarequest = json_decode($bodyrequest, true);

        $entityManager = $this->getDoctrine()->getManager();
 
        $project = new Project();
        $project->setName($datarequest['name']);
        $project->setDescription($datarequest['description']);
 
        $entityManager->persist($project);
        $entityManager->flush();
 
        return $this->json('Created new project successfully with id ' . $project->getId());
    }

        /**
     * @Route("/project/{id}", name="project_edit", methods={"PUT"})
     */
    public function edit(Request $request, int $id): Response
    {
        $entityManager = $this->getDoctrine()->getManager();
        $project = $entityManager->getRepository(Project::class)->find($id);
 
        if (!$project) {
            return $this->json('No project found for id' . $id, 404);
        }
        $bodyrequest = $request->getContent();
        $datarequest = json_decode($bodyrequest, true);
 
        $project->setName($datarequest['name']);
        $project->setDescription($datarequest['description']);
        $entityManager->flush();
 
        $data =  [
            'id' => $project->getId(),
            'name' => $project->getName(),
            'description' => $project->getDescription(),
        ];
         
        return $this->json($data);
    }


    /**
     * @Route("/project/{id}", name="project_delete", methods={"DELETE"})
     */
    public function delete(int $id): Response
    {
        $entityManager = $this->getDoctrine()->getManager();
        $project = $entityManager->getRepository(Project::class)->find($id);
 
        if (!$project) {
            return $this->json('No project found for id' . $id, 404);
        }
 
        $entityManager->remove($project);
        $entityManager->flush();
 
        return $this->json('Deleted a project successfully with id ' . $id);
    }
}
