<?php

declare(strict_types=1);

use Phinx\Seed\AbstractSeed;

class CreateDomain extends AbstractSeed
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
            [
                'code' => 'AL',
                'label' => 'Architecture des logiciels'
            ], [
                'code' => 'SRC',
                'label' => 'Système, Réseaux et Cloud'
            ], [
                'code' => 'SI',
                'label' => 'Sécurité Informatique'
            ]
        ];

        $this->insert('domains', $data);
    }
}
