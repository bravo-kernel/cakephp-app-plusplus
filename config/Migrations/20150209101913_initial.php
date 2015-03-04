<?php
use Phinx\Migration\AbstractMigration;

class Initial extends AbstractMigration {

    /**
     * Change Method.
     *
     * More information on this method is available here:
     * http://docs.phinx.org/en/latest/migrations.html#the-change-method
     *
     * @return void
     */
    public function change()
    {
        $table = $this->table('users',
            [
                'id' => false,
                'primary_key' => ['id']
            ]);

        $table
            ->addColumn('id', 'uuid')
            ->addColumn('username', 'string')
            ->addColumn('email', 'string')
            ->addColumn('password', 'string')
            ->addColumn('slug', 'string')
            ->addColumn('active', 'boolean')
            ->addColumn('role_id', 'integer', ['signed' => false, 'default' => '1'])
            ->addColumn('created', 'datetime', ['default' => 'CURRENT_TIMESTAMP'])
            ->addColumn('modified', 'datetime', ['null' => '1', 'default' => null])
            ->save();

        $table = $this->table('user_roles')
            ->addColumn('name', 'string')
            ->addColumn('alias', 'string')
            ->addColumn('description', 'text')
            ->addColumn('created', 'datetime', ['default' => 'CURRENT_TIMESTAMP'])
            ->addColumn('modified', 'datetime', ['null' => '1', 'default' => null])
            ->save();

        $table = $this->table('user_roles_users')
            ->addColumn('user_role_id', 'integer', ['signed' => false])
            ->addColumn('user_id', 'uuid')
            ->addColumn('created', 'datetime', ['default' => 'CURRENT_TIMESTAMP'])
            ->addColumn('modified', 'datetime', ['null' => '1', 'default' => null])
            ->save();
    }

    /**
     * Migrate Up.
     *
     * @return void
     */
    public function up()
    {
    }

    /**
     * Migrate Down.
     *
     * @return void
     */
    public function down()
    {
    }

}
