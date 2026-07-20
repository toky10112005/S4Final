<?php
namespace App\Filters;

use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;
use CodeIgniter\Filters\FilterInterface;

class RoleFilter implements FilterInterface
{
    public function before(RequestInterface $request, $arguments = null)
    {
        $session = session();
        $user = $session->get('Client') ?? $session->get('user') ?? $session->get('client');

        if (!$user) {
            return redirect()->to('/')->with('erreur', 'Accès refusé : droits insuffisants');
        }

        $roles = is_array($arguments) ? $arguments : [];
        if (empty($roles)) {
            $roles = ['operateur'];
        }

        $userRole = is_array($user) ? ($user['role'] ?? null) : null;
        if ($userRole === null || !in_array($userRole, $roles, true)) {
            return redirect()->to('/')->with('erreur', 'Accès refusé : droits insuffisants');
        }

        return null;
    }

    public function after(RequestInterface $request, ResponseInterface
    $response, $arguments = null)
    {
    // Rien à faire après
    }
}