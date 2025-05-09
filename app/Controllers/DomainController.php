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
        $this->view('domains/index', ['domains' => $domains]);
    }

    /**
     * Affiche le formulaire de création d’un domaine.
     *
     * @return void
     */
    public function create(): void
    {
        $this->view('domains/create');
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
            $this->view('domains/create', ['errors' => $errors, 'data' => $_POST]);
            return;
        }

        if (Domain::create($_POST['code'], $_POST['label'])) {
            $this->redirect('domains');
        } else {
            $this->view('domains/create', ['error' => 'Erreur lors de la création du domaine.']);
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
            header('HTTP/1.1 404 Not Found');
            $this->view('errors/404', []);
            return;
        }
        $this->view('domains/edit', ['domain' => $domain]);
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
            header('HTTP/1.1 400 Bad Request');
            $this->view('errors/400', ['error' => 'ID invalide']);
            return;
        }

        $domain = Domain::find($id);
        if (!$domain) {
            header('HTTP/1.1 404 Not Found');
            $this->view('errors/404', []);
            return;
        }

        $errors = $this->validateDomain($_POST['code'] ?? '', $_POST['label'] ?? '', $id);

        if (!empty($errors)) {
            $this->view('domains/edit', ['domain' => $domain, 'errors' => $errors, 'data' => $_POST]);
            return;
        }

        $data = [
            'code' => $_POST['code'],
            'label' => $_POST['label']
        ];

        if (Domain::update($id, $data)) {
            $this->redirect('domains');
        } else {
            $this->view('domains/edit', ['domain' => $domain, 'error' => 'Erreur lors de la mise à jour du domaine.']);
        }
    }

    /**
     * Supprime un domaine de la base de données.
     *
     * @param int $id L’identifiant unique du domaine.
     * @return void
     */
    public function destroy(int $id): void
    {
        if (Domain::delete($id)) {
            $this->redirect('domains');
        } else {
            $domains = Domain::all();
            $this->view('domains/index', ['domains' => $domains, 'error' => 'Erreur lors de la suppression du domaine.']);
        }
    }
}