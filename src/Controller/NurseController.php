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
        }

        return new JsonResponse($return_nurses);
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

            return new JsonResponse($return_nurses, 302);
        }
        return new JsonResponse($return_nurses, 404);
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
                    return new JsonResponse(['success' => true], 302);
                }
            }
        }
        return new JsonResponse(['success' => false], 404);
    }

    #[Route('/delete/{id}', name: 'nurse_delete', methods: ['DELETE'])]
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
}
