<?php

declare(strict_types=1);

use Phinx\Seed\AbstractSeed;

class CreateAdmin extends AbstractSeed
{
    /**
     * Run Method.
     *
     * Write your database seeder using this method.
     *
     * More information on writing seeders is available here:
     * https://book.cakephp.org/phinx/0/en/seeding.html
     */
    public function run(): void
    {
        $data = [
            'firstname' => 'admin',
            'lastname' => 'admin',
            'gender' => 'M',
            'email' => 'admin@example.com',
            'role' => 'admin',
            'password' => password_hash('Bonjour@25', PASSWORD_DEFAULT)
        ];

        $this->insert('users', $data);
    }
}
