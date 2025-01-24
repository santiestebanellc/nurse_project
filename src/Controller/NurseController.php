<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Entity\Nurse;
use App\Repository\NurseRepository;
use Doctrine\ORM\EntityManagerInterface;

#[Route('/nurse', name: 'nurse_main')]
class NurseController extends AbstractController
{
    private EntityManagerInterface $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    #[Route('/delete/{id}', name: 'nurse_delete', methods: ['DELETE'])]
    public function delete(int $id): JsonResponse
    {
        $nurse = $this->entityManager->getRepository(Nurse::class)->find($id);

        if (!$nurse) {
            return new JsonResponse(['message' => 'Nurse not found'], Response::HTTP_NOT_FOUND);
        }

        $this->entityManager->remove($nurse);
        $this->entityManager->flush();

        return new JsonResponse(['message' => 'Nurse deleted successfully'], Response::HTTP_OK);
    }

    #[Route('/find/{field}/{value}', name: 'nurse_find_by_field', methods: ['GET'])]
    public function findByField(string $field, string $value, NurseRepository $nurseRepository): JsonResponse
    {
        $criteria = [$field => $value];
        $nurses = $nurseRepository->findBy($criteria);

        if (empty($nurses)) {
            return new JsonResponse([], Response::HTTP_NOT_FOUND);
        }

        $result = array_map(function (Nurse $nurse) {
            return [
                'id' => $nurse->getId(),
                'name' => $nurse->getName(),
                'first_surname' => $nurse->getFirstSurname(),
                'second_surname' => $nurse->getSecondSurname(),
                'email' => $nurse->getEmail(),
                'image' => $this->getImageData($nurse->getImage())
            ];
        }, $nurses);

        return new JsonResponse($result, Response::HTTP_OK);
    }

    #[Route('/edit', name: 'nurse_edit', methods: ['PUT'])]
    public function edit(Request $request): JsonResponse
    {
        $data = json_decode($request->getContent(), true);

        if (!isset($data['id'])) {
            return new JsonResponse(['message' => 'ID is required'], Response::HTTP_BAD_REQUEST);
        }

        $nurse = $this->entityManager->getRepository(Nurse::class)->find($data['id']);

        if (!$nurse) {
            return new JsonResponse(['message' => 'Nurse not found'], Response::HTTP_NOT_FOUND);
        }

        if (isset($data['name'])) $nurse->setName($data['name']);
        if (isset($data['first_surname'])) $nurse->setFirstSurname($data['first_surname']);
        if (isset($data['second_surname'])) $nurse->setSecondSurname($data['second_surname']);
        if (isset($data['email'])) $nurse->setEmail($data['email']);
        if (isset($data['password'])) $nurse->setPassword($data['password']);
        if (isset($data['image'])) $nurse->setImage(base64_decode($data['image']));

        $this->entityManager->flush();

        return new JsonResponse(['message' => 'Nurse updated successfully'], Response::HTTP_OK);
    }

    #[Route('/show/{id}', name: 'nurse_show', methods: ['GET'])]
    public function show(int $id): JsonResponse
    {
        $nurse = $this->entityManager->getRepository(Nurse::class)->find($id);

        if (!$nurse) {
            return new JsonResponse(['message' => 'Nurse not found'], Response::HTTP_NOT_FOUND);
        }

        return new JsonResponse([
            'id' => $nurse->getId(),
            'name' => $nurse->getName(),
            'first_surname' => $nurse->getFirstSurname(),
            'second_surname' => $nurse->getSecondSurname(),
            'email' => $nurse->getEmail(),
            'password' => $nurse->getPassword(),
            'image' => $nurse->getImage()
        ], Response::HTTP_OK);
    }

    #[Route('/login', name: 'nurse_login', methods: ['POST'])]
    public function login(Request $request, NurseRepository $nurseRepository): JsonResponse
    {
        $email = $request->get('email');
        $password = $request->get('password');
    
        if ($email !== null && $password !== null) {
            $nurse = $nurseRepository->findOneBy(['email' => $email]);
    
            if ($nurse && $nurse->getPassword() === $password) {
                return new JsonResponse([
                    'success' => true,
                    'id' => $nurse->getId()
                ], 200);
            }
        }
    
        return new JsonResponse(['success' => false], 404);
    }
    
    #[Route('/register', name: 'nurse_create', methods: ['POST'])]
    public function createNurse(Request $request, NurseRepository $nurseRepository): JsonResponse
    {
        $data = json_decode($request->getContent(), true);
        $email = $data['email'] ?? null;
        
        if ($email === null) {
            return new JsonResponse(['success' => false, 'error' => 'Email is required'], 400);
        }

        $nurse = $nurseRepository->findOneBy(['email' => $email]);
        if ($nurse !== null) {
            return new JsonResponse(['success' => false, 'error' => 'A nurse with this email already exists'], 409);
        }

        $name = $data['name'] ?? null;
        $firstSurname = $data['first_surname'] ?? null;
        $secondSurname = $data['second_surname'] ?? null;
        $password = $data['password'] ?? null;
        $image = $data['image'] ?? null;

        if (
            (!is_string($email) || !filter_var($email, FILTER_VALIDATE_EMAIL)) ||
            (!is_string($name) || $name === null) ||
            (!is_string($firstSurname) || $firstSurname === null) ||
            (!is_string($secondSurname) || $secondSurname === null) ||
            (!is_string($password) || $password === null)
        ) {
            return new JsonResponse(['success' => false, 'error' => 'Invalid data type provided'], 400);
        }

        $newNurse = new Nurse();
        $newNurse->setEmail($email);
        $newNurse->setName($name);
        $newNurse->setFirstSurname($firstSurname);
        $newNurse->setSecondSurname($secondSurname);
        $newNurse->setPassword($password);

        if ($image !== null && is_string($image)) {
            $newNurse->setImage(base64_decode($image));
        }

        $this->entityManager->persist($newNurse);
        $this->entityManager->flush();
        
        $nurseId = $newNurse->getId();
        
        return new JsonResponse(['success' => true, 'nurse_id' => $nurseId], 201);
    }

    #[Route('/index', name: 'nurse_list_all', methods: ['GET'])]
    public function index(EntityManagerInterface $entityManager): JsonResponse
    {
        $nurses = $entityManager->getRepository(Nurse::class)->findAll();

        $result = array_map(function (Nurse $nurse) {
            return [
                'id' => $nurse->getId(),
                'name' => $nurse->getName(),
                'first_surname' => $nurse->getFirstSurname(),
                'second_surname' => $nurse->getSecondSurname(),
                'email' => $nurse->getEmail(),
                'password' => $nurse->getPassword(),
                'image' => $nurse->getImage()
            ];
        }, $nurses);

        return new JsonResponse($result, empty($nurses) ? Response::HTTP_NOT_FOUND : Response::HTTP_OK);
    }
    


    private function getImageData($image): ?string
    {
        if (is_resource($image)) {
            return base64_encode(stream_get_contents($image));
        } elseif (is_string($image)) {
            return base64_encode($image);
        }

        return null;
    }
}
