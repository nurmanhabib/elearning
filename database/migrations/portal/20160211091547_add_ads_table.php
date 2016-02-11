<?php

use Phinx\Migration\AbstractMigration;

class AddAdsTable extends AbstractMigration
{
    /**
     * Change Method.
     *
     * Write your reversible migrations using this method.
     *
     * More information on writing migrations is available here:
     * http://docs.phinx.org/en/latest/migrations.html#the-abstractmigration-class
     *
     * The following commands can be used in this method and Phinx will
     * automatically reverse them when rolling back:
     *
     *    createTable
     *    renameTable
     *    addColumn
     *    renameColumn
     *    addIndex
     *    addForeignKey
     *
     * Remember to call "create()" or "update()" and NOT "save()" when working
     * with the Table class.
     */
    public function change()
    {
        $table = $this->table('ads');
        $table->addColumn('code', 'string', ['limit' => 20])
            ->addColumn('name', 'string', ['limit' => 100])
            ->addColumn('image', 'string')
            ->addColumn('size', 'string')
            ->addColumn('link', 'string')
            ->addColumn('target', 'string', ['limit' => '20', 'default' => '_blank'])
            ->addColumn('show', 'boolean', ['default' => true])
            ->addColumn('start', 'datetime')
            ->addColumn('expired', 'datetime')
            ->addColumn('created_at', 'datetime')
            ->addColumn('updated_at', 'datetime')
            ->create();
    }
}
