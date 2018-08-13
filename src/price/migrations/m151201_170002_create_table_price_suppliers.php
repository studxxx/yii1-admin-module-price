<?php

class m151201_170002_create_table_price_suppliers extends CDbMigration
{
    const TABLE_SCHEMA = 'price_suppliers';
    const TABLE_OPTIONS = 'engine=innodb character set=UTF8 collate utf8_unicode_ci;';

    public function safeUp()
    {
        $this->createTable(self::TABLE_SCHEMA, [
            'id' => 'pk',
            'currency_id' => 'int(11) not null',
            'name' => 'string default null',
            'description' => 'string default null',
            'email' => 'string default null',
            'phone' => 'string default null',
            'title' => 'string default null',
            'created_at' => 'int(11) not null',
            'updated_at' => 'int(11) not null',
        ], self::TABLE_OPTIONS);
    }

    public function safeDown()
    {
        $this->dropTable(self::TABLE_SCHEMA);
    }
}
