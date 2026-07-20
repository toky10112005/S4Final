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
        $user = $session->get('Client')
            ?? $session->get('client')
            ?? $session->get('user')
            ?? $session->get('operateur');

        if (!$user) {
            return redirect()->to('/login/operateur')->with('erreur', 'Accès refusé : droits insuffisants');
        }

        $roles = is_array($arguments) ? $arguments : [];
        if (empty($roles)) {
            $roles = ['operateur'];
        }

        $userRole = null;
        if (is_array($user)) {
            $userRole = $user['role'] ?? null;
        } elseif (is_object($user)) {
            $userRole = $user->role ?? null;
        }

        if ($userRole === null || !in_array($userRole, $roles, true)) {
            return redirect()->to('/login/operateur')->with('erreur', 'Accès refusé : droits insuffisants');
        }

        return null;
    }

    public function after(RequestInterface $request, ResponseInterface
    $response, $arguments = null)
    {
    // Rien à faire après
    }
}