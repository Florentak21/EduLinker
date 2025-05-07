<?php

declare(strict_types=1);

use Phinx\Migration\AbstractMigration;

final class CreateTeachersTable extends AbstractMigration
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
        $table = $this->table('teachers' ,['engine' => 'InnoDB']);
        $table->addColumn('phone',     'string', ['limit'=>50, 'null'=>false])
              ->addColumn('user_id',   'integer' ,['signed' => false])
              ->addColumn('domain_id', 'integer', ['signed' => false])
              ->addTimestamps()
              ->addIndex(['phone'], ['unique'=>true])
              ->addForeignKey('user_id',   'users',   'id', ['delete'=>'CASCADE','update'=>'NO_ACTION'])
              ->addForeignKey('domain_id', 'domains', 'id', ['delete'=>'CASCADE','update'=>'NO_ACTION'])
              ->create();
    }
}
