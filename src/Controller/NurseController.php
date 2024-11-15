<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use App\Entity\Nurse;
use App\Repository\NurseRepository;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\DBAL\Exception as DBALException;

#[Route('/nurse', name: 'nurse_main')]
class NurseController extends AbstractController
{

    #[Route('/index', name: 'nurse_list_all', methods: ['GET'])]
    public function index(EntityManagerInterface $entityManager): JsonResponse
    {
        $nurses = $entityManager->getRepository(Nurse::class)->findAll();

        $return_nurses = array();

        if (!empty($nurses)) {
            foreach ($nurses as $nurse) {
                $return_nurses[] = [
                    'id' => $nurse->getId(),
                    'name' => $nurse->getName(),
                    'first_surname' => $nurse->getFirstSurname(),
                    'second_surname' => $nurse->getSecondSurname(),
                    'email' => $nurse->getEmail(),
                ];
            }
            return new JsonResponse($return_nurses, 200);
        }
        return new JsonResponse($return_nurses, 404);
    }

    #[Route('/name/{str_name}', name: 'nurse_list_name', methods: ['GET'])]
    public function findByName(NurseRepository $nurseRepository, $str_name): JsonResponse
    {
        $nurses = $nurseRepository->findBy(['name' => $str_name]);

        $return_nurses = [];

        if (!empty($nurses)) {
            foreach ($nurses as $nurse) {
                $return_nurses[] = [
                    'id' => $nurse->getId(),
                    'name' => $nurse->getName(),
                    'first_surname' => $nurse->getFirstSurname(),
                    'second_surname' => $nurse->getSecondSurname(),
                    'email' => $nurse->getEmail(),
                ];
            }

            return new JsonResponse($return_nurses, 200);
        }
        return new JsonResponse($return_nurses, 404);
    }

    #[Route('/id/{str_id}', name: 'nurse_id', methods: ['GET'])]
    public function findById(EntityManagerInterface $entityManager, NurseRepository $nurseRepository, string $str_id): JsonResponse
    {
        $nurse = $nurseRepository->find($str_id);
    
        if (!empty($nurse)) {
            $return_nurse = [
                'id' => $nurse->getId(),
                'name' => $nurse->getName(),
                'first_surname' => $nurse->getFirstSurname(),
                'second_surname' => $nurse->getSecondSurname(),
                'email' => $nurse->getEmail(),
            ];
            return new JsonResponse($return_nurse, 200); 
        }
    
        return new JsonResponse(['error' => 'Nurse not found'], 404);
    }

    #[Route('/login', name: 'nurse_login', methods: ['POST'])]
    public function login(Request $request, NurseRepository $nurseRepository): JsonResponse
    {
        $email = $request->get('email');
        $password = $request->get('password');
        if ($email !== null && $password !== null) {
            $nurse = $nurseRepository->findOneBy(['email' => $email]);
            if ($nurse) {
                if ($nurse->getPassword() === $password) {
                    return new JsonResponse(['success' => true], 200);
                }
            }
        }
        return new JsonResponse(['success' => false], 404);
    }

    #[Route('/', name: 'nurse_create', methods: ['POST'])]
    public function createNurse(Request $request, NurseRepository $nurseRepository, EntityManagerInterface $entityManagerInterface): JsonResponse
    {
        $data = $request->toArray();
        $email = $data['email']??null;
        
        if ($email === null) {
            return new JsonResponse(['success' => false], 400);
        }

        $nurse = $nurseRepository->findOneBy(['email'=> $email]);
        if ($nurse !== null) {
            return new JsonResponse(['error' => 'A nurse with this email already exists'], 409);
        }

        $name =$data['name'];
        $firstSurname = $data['firstSurname'];
        $secondSurname = $data['secondSurname'];
        $password = $data['password'];

        if (
        (!is_string($email) || !filter_var($email, FILTER_VALIDATE_EMAIL)) ||
        (!is_string($name) || $name === null) ||
        (!is_string($firstSurname) || $firstSurname === null) ||
        (!is_string($secondSurname) || $secondSurname === null) ||
        (!is_string($password) || $password === null)
        ){
            return new JsonResponse(['error' => 'Invalid data type provided'], 404);
        }

        $newNurse = new Nurse();
        $newNurse->setEmail($email);
        $newNurse->setName($name);
        $newNurse->setFirstSurname($firstSurname);
        $newNurse->setSecondSurname($secondSurname);
        $newNurse->setPassword($password);

        $entityManagerInterface->persist($newNurse);
        $entityManagerInterface->flush();
        $nurseId = $newNurse->getId();
        
        return new JsonResponse(['success' => true, 'nurse_id' => $nurseId], 201);
    }

    #[Route('/{id}', name: 'nurse_delete', methods: ['DELETE'])]
    public function delete(NurseRepository $nurseRepository, EntityManagerInterface $entityManager, $id): JsonResponse
    {
        try {
            $nurse = $nurseRepository->find($id);
            if (!empty($nurse)) {
                $entityManager->remove($nurse);
                $entityManager->flush();
                return new JsonResponse(['success' => true], 200);
            }
            return new JsonResponse(['success' => false], 404);
        } catch (DBALException $e) {
            return new JsonResponse(['success' => false, 'message' => 'Database error: ' . $e->getMessage()], 500);
        } catch (\Exception $e) {
            return new JsonResponse(['success' => false, 'message' => 'An error occurred: ' . $e->getMessage()], 500);
        }
    }

    #[Route('/{id}', name: 'nurse_update', methods: ['PUT'])]
    public function updateNurseById(Request $request, NurseRepository $nurseRepository, EntityManagerInterface $entityManager, int $id): JsonResponse
    {
        $nurse = $nurseRepository->find($id);
        if (!$nurse) {
            return new JsonResponse(['error' => 'Nurse not found'], 404);
        }

        $data = $request->toArray();
        
        $name = $data['name'] ?? null;
        $firstSurname = $data['first_surname'] ?? null;
        $secondSurname = $data['second_surname'] ?? null;
        $email = $data['email'] ?? null;
        $password = $data['password'] ?? null;

        if (($name !== null && !is_string($name)) ||
        ($firstSurname !== null && !is_string($firstSurname)) ||
        ($secondSurname !== null && !is_string($secondSurname)) ||
        ($email !== null && (!is_string($email) || !filter_var($email, FILTER_VALIDATE_EMAIL))) || // Cambiado aquí
        ($password !== null && !is_string($password))) {
        return new JsonResponse(['error' => 'Invalid data type provided'], 404);
        }


        if ($name !== null) {
            $nurse->setName($name);
        }
        if ($firstSurname !== null) {
            $nurse->setFirstSurname($firstSurname);
        }
        if ($secondSurname !== null) {
            $nurse->setSecondSurname($secondSurname);
        }
        if ($email !== null) {
            $nurse->setEmail($email);
        }
        if ($password !== null) {
            $nurse->setPassword($password);
        }

        $entityManager->flush();

        return new JsonResponse(['success' => true, 'message' => 'Nurse updated successfully'], 200);
    }

}