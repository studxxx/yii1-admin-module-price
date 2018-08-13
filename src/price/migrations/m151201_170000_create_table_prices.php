<?php

class m151201_170000_create_table_prices extends CDbMigration
{
    const TABLE_SCHEMA = 'prices';
    const TABLE_OPTIONS = 'engine=innodb character set=UTF8 collate utf8_unicode_ci;';

    public function safeUp()
    {
        $this->createTable(self::TABLE_SCHEMA, [
            'id' => 'pk',
            'supplier_id' => 'int(11) not null',
            'price_file' => 'string not null',
            'csv_file' => 'string default null',
            'status' => 'tinyint(1) default 1',
            'created_at' => 'int(11) not null',
            'updated_at' => 'int(11) not null',
        ], self::TABLE_OPTIONS);
    }

    public function safeDown()
    {
        $this->dropTable(self::TABLE_SCHEMA);
    }
}
