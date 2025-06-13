<?php

declare(strict_types=1);

use Phinx\Seed\AbstractSeed;

class CreateTeachers extends AbstractSeed
{
    public function run(): void
    {
        $password = password_hash('Bonjour@25', PASSWORD_DEFAULT);

        $usersData = [
            // Domaine 1 (AL)
            ['id' => 2,  'firstname' => 'Marie',  'lastname' => 'Dupont',   'email' => 'marie.dupont@school.com',   'gender' => 'F'],
            ['id' => 3,  'firstname' => 'Jean',   'lastname' => 'Martin',   'email' => 'jean.martin@school.com',    'gender' => 'M'],
            ['id' => 4,  'firstname' => 'Sophie', 'lastname' => 'Bernard',  'email' => 'sophie.bernard@school.com', 'gender' => 'F'],
            ['id' => 5,  'firstname' => 'Pierre', 'lastname' => 'Thomas',   'email' => 'pierre.thomas@school.com',  'gender' => 'M'],
            ['id' => 6,  'firstname' => 'Claire', 'lastname' => 'Petit',    'email' => 'claire.petit@school.com',   'gender' => 'F'],
            // Domaine 2 (SRC)
            ['id' => 7,  'firstname' => 'Thomas', 'lastname' => 'Robert',   'email' => 'thomas.robert@school.com',  'gender' => 'M'],
            ['id' => 8,  'firstname' => 'Anne',   'lastname' => 'Richard',  'email' => 'anne.richard@school.com',   'gender' => 'F'],
            ['id' => 9,  'firstname' => 'Luc',    'lastname' => 'Durand',   'email' => 'luc.durand@school.com',     'gender' => 'M'],
            ['id' => 10, 'firstname' => 'Emma',   'lastname' => 'Leroy',    'email' => 'emma.leroy@school.com',     'gender' => 'F'],
            ['id' => 11, 'firstname' => 'David',  'lastname' => 'Moreau',   'email' => 'david.moreau@school.com',   'gender' => 'M'],
            // Domaine 3 (SI)
            ['id' => 12, 'firstname' => 'Julie',  'lastname' => 'Simon',    'email' => 'julie.simon@school.com',    'gender' => 'F'],
            ['id' => 13, 'firstname' => 'Marc',   'lastname' => 'Laurent',  'email' => 'marc.laurent@school.com',   'gender' => 'M'],
            ['id' => 14, 'firstname' => 'Laura',  'lastname' => 'Lefebvre', 'email' => 'laura.lefebvre@school.com', 'gender' => 'F'],
            ['id' => 15, 'firstname' => 'Alex',   'lastname' => 'Michel',   'email' => 'alex.michel@school.com',    'gender' => 'M'],
            ['id' => 16, 'firstname' => 'Chloé',  'lastname' => 'Garcia',   'email' => 'chloe.garcia@school.com',   'gender' => 'F'],
        ];

        // Ajouter les champs communs à usersData
        foreach ($usersData as &$user) {
            $user['role'] = 'teacher';
            $user['password'] = $password;
            $user['created_at'] = date('Y-m-d H:i:s');
            $user['updated_at'] = date('Y-m-d H:i:s');
        }

        // Données pour la table teachers
        $teachersData = [
            // Domaine 1 (AL)
            ['user_id' => 2,  'domain_id' => 1],
            ['user_id' => 3,  'domain_id' => 1],
            ['user_id' => 4,  'domain_id' => 1],
            ['user_id' => 5,  'domain_id' => 1],
            ['user_id' => 6,  'domain_id' => 1],
            // Domaine 2 (SRC)
            ['user_id' => 7,  'domain_id' => 2],
            ['user_id' => 8,  'domain_id' => 2],
            ['user_id' => 9,  'domain_id' => 2],
            ['user_id' => 10, 'domain_id' => 2],
            ['user_id' => 11, 'domain_id' => 2],
            // Domaine 3 (SI)
            ['user_id' => 12, 'domain_id' => 3],
            ['user_id' => 13, 'domain_id' => 3],
            ['user_id' => 14, 'domain_id' => 3],
            ['user_id' => 15, 'domain_id' => 3],
            ['user_id' => 16, 'domain_id' => 3],
        ];

        // Ajouter les timestamps à teachersData
        foreach ($teachersData as &$teacher) {
            $teacher['created_at'] = date('Y-m-d H:i:s');
            $teacher['updated_at'] = date('Y-m-d H:i:s');
        }

        // Insérer dans la table users
        $this->table('users')
            ->insert($usersData)
            ->saveData();

        // Insérer dans la table teachers
        $this->table('teachers')
            ->insert($teachersData)
            ->saveData();
    }
}