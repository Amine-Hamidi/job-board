<?php

namespace App\Controller;

use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use Lexik\Bundle\JWTAuthenticationBundle\Services\JWTTokenManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Cookie;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Attribute\Route;


class AuthCookieController extends AbstractController
{
    #[Route('/api/login-cookie', name: 'api_login_cookie', methods: ['POST'])]
public function login(
    Request $request,
    EntityManagerInterface $em,
    JWTTokenManagerInterface $jwtManager,
    UserPasswordHasherInterface $passwordHasher
): JsonResponse {
    $data = json_decode($request->getContent(), true);
    $email = $data['email'] ?? null;
    $password = $data['password'] ?? null;

    if (!$email || !$password) {
        return new JsonResponse(['error' => 'Missing credentials'], Response::HTTP_BAD_REQUEST);
    }

    $user = $em->getRepository(User::class)->findOneBy(['email' => $email]);

    if (!$user) {
        return new JsonResponse(['error' => 'User not found'], Response::HTTP_UNAUTHORIZED);
    }
      

    if (!$passwordHasher->isPasswordValid($user, $password)) {
        return new JsonResponse(['error' => 'Invalid password'], Response::HTTP_UNAUTHORIZED);
    }

    $token = $jwtManager->create($user);

    $response = new JsonResponse(['message' => 'Token set in cookie']);
    $response->headers->setCookie(
        new \Symfony\Component\HttpFoundation\Cookie(
            'BEARER',
            $token,
            0,
            '/',
            null,
            false,
            true,
            false,
            'Lax'
        )
    );

    return $response;
}

}
