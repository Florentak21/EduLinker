<?php

declare(strict_types=1);

use Phinx\Migration\AbstractMigration;

final class CreateStudentsTable extends AbstractMigration
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
        $this->table('students')
            ->addColumn('matricule', 'string',  ['limit' => 20, 'null' => false])
            ->addColumn('has_binome', 'boolean', ['default' => false, 'null' => false])
            ->addColumn('matricule_binome', 'string',  ['limit' => 20, 'null' => true])
            ->addColumn('theme', 'text', ['null' => true])
            ->addColumn('description', 'text', ['null' => true])
            ->addColumn('theme_status', 'enum',['values' => ['non-soumis', 'en-traitement', 'rejete', 'valide'],'default' => 'non-soumis', 'null' => false])
            ->addColumn('cdc', 'string', ['limit' => 255, 'null' => true])
            ->addColumn('user_id', 'integer',['signed' => false, 'null' => false])
            ->addColumn('domain_id', 'integer',['signed' => false, 'null' => false])
            ->addColumn('teacher_id', 'integer', ['signed' => false, 'null'=>true])
            ->addColumn('submitted_at', 'timestamp', ['null' => true])
            ->addColumn('last_reminder_at', 'timestamp', ['null' => true])
            ->addColumn('assigned_at', 'timestamp', ['null' => true])
            ->addTimestamps()
            ->addIndex(['matricule'], ['unique'=>true])
            ->addForeignKey('user_id', 'users',    'id', ['delete'=>'CASCADE'])
            ->addForeignKey('domain_id', 'domains',  'id', ['delete'=>'CASCADE'])
            ->addForeignKey('teacher_id', 'teachers', 'id', ['delete'=>'SET_NULL'])
            ->create();
    }
}
