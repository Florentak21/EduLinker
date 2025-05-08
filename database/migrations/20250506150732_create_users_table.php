<?php

declare(strict_types=1);

use Phinx\Migration\AbstractMigration;

final class CreateUsersTable extends AbstractMigration
{
    /**
     * Change Method.
     *
     * Write your reversible migrations using this method.
     *
     * More information on writing migrations is available here:
     * https://book.cakephp.org/phinx/0/en/migrations.html#the-change-method
     *
     * Remember to call "create()" or "update()" and NOT "save()" when working
     * with the Table class.
     */
    public function change(): void
    {
        $this->table('users')
            ->addColumn('firstname', 'string', ['limit' => 100, 'null' => false])
            ->addColumn('lastname', 'string', ['limit' => 100, 'null' => false])
            ->addColumn('gender', 'enum',  ['values' => ['M', 'F'], 'null' => false] )
            ->addColumn('email', 'string', ['limit' => 255, 'null' => false])
            ->addColumn('role', 'enum', ['values' => ['admin', 'student', 'teacher'], 'null' => false])
            ->addColumn('password', 'string', ['limit' => 255, 'null' => false])
            ->addTimestamps()
            ->addIndex(['email'], ['unique' => true])
            ->create();
    }
}
