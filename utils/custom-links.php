<?php
$results = (function (): array {
    $url = '/login';
    $link = 'Se connecter';

    if (isset($_SESSION['user_id'], $_SESSION['user_role']) && !empty($_SESSION['user_id']) && !empty($_SESSION['user_role'])) {
        switch ($_SESSION['user_role']) {
            case 'admin':
                $url = "/admin/dashboard";
                $link = "Accéder à la plateforme";
                break;
            case 'teacher':
                $url = "/teacher/dashboard";
                $link = "Accéder à la plateforme";
                break;
            case 'student':
                $url = "/student/dashboard";
                $link = "Accéder à la plateforme";
                break;
            default:
                $url = '/login';
                $link = 'Se connecter';
                break;
        }
    }

    return [
        'url' => $url,
        'link' => $link
    ];
})();
?>