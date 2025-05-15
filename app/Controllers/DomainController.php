<?php
namespace App\Controllers;

use App\Core\Controller;
use App\Core\Router;
use App\Models\Domain;
use App\Traits\DomainValidator;

class DomainController extends Controller {
    use DomainValidator;

    public function __construct(Router $router)
    {
        parent::__construct($router);
    }

    /**
     * Affiche la liste de tous les domaines.
     *
     * @return void
     */
    public function index(): void
    {
        $domains = Domain::all();
        $this->view('admin/domains/index', [
            'domains' => $domains,
            'title' => 'Gestion des domaines d\'étude'
        ]);
    }

    /**
     * Affiche le formulaire de création d’un domaine.
     *
     * @return void
     */
    public function create(): void
    {
        $this->view('admin/domains/create', [
            'title' => 'Créer un nouveau domaine'
        ]);
    }

    /**
     * Traite la soumission du formulaire de création d’un domaine.
     *
     * Valide les champs et crée un nouveau domaine dans la base de données.
     *
     * @return void
     */
    public function store(): void
    {
        $errors = $this->validateDomain($_POST['code'] ?? '', $_POST['label'] ?? '');

        if (!empty($errors)) {
            $this->view('admin/domains/create', [
                'errors' => $errors,
                'data' => $_POST,
                'error' => $_SESSION['error'] ?? null
            ]);
            unset($_SESSION['error']);
            return;
        }

        if (Domain::create($_POST['code'], $_POST['label'])) {
            $this->redirect('admin/domains', ['success' => 'Domaine créé avec succès.']);
        } else {
            $this->redirect('admin/domains/create', ['error' => 'Erreur lors de la création du domaine.']);
        }
    }

    /**
     * Affiche le formulaire de modification d’un domaine.
     *
     * @param int $id L’identifiant unique du domaine.
     * @return void
     */
    public function edit(int $id): void
    {
        $domain = Domain::find($id);
        if (!$domain) {
            $this->redirect('error/404', ['message' => 'Domaine non trouvé.']);
            return;
        }
        $this->view('admin/domains/edit', [
            'domain' => $domain,
            'title' => 'Mettre à jour un domaine'
        ]);
        unset($_SESSION['error']);
    }

    /**
     * Traite la soumission du formulaire de modification d’un domaine.
     *
     * Valide les champs et met à jour le domaine dans la base de données.
     *
     * @return void
     */
    public function update(): void
    {
        $id = isset($_POST['id']) ? intval($_POST['id']) : 0;
        if ($id <= 0) {
            $this->redirect('error/400', ['message' => 'ID invalide']);
            return;
        }

        $domain = Domain::find($id);
        if (!$domain) {
            $this->redirect('error/404', ['message' => 'Domaine non trouvé.']);
            return;
        }

        $errors = $this->validateDomain($_POST['code'] ?? '', $_POST['label'] ?? '', $id);

        if (!empty($errors)) {
            $this->view('admin/domains/edit', [
                'domain' => $domain,
                'errors' => $errors,
                'data' => $_POST,
                'error' => $_SESSION['error'] ?? null
            ]);
            unset($_SESSION['error']);
            return;
        }

        $data = [
            'code' => $_POST['code'],
            'label' => $_POST['label']
        ];

        if (Domain::update($id, $data)) {
            $this->redirect('admin/domains', ['success' => 'Domaine mis à jour avec succès.']);
        } else {
            $this->redirect('admin/domains/edit/' . $id, ['error' => 'Erreur lors de la mise à jour du domaine.']);
        }
    }

    /**
     * Supprime un domaine de la base de données.
     *
     * @param int $id
     * @return void
     */
    public function destroy(int $id): void
    {
        if (Domain::delete($id)) {
            $this->redirect('admin/domains', ['success' => 'Domaine supprimé avec succès.']);
        } else {
            $this->redirect('admin/domains', ['error' => 'Erreur lors de la suppression du domaine.']);
        }
    }
}