<?php

class m151201_170005_create_table_price_ranges extends CDbMigration
{
    const TABLE_SCHEMA = 'price_ranges';
    const TABLE_OPTIONS = 'engine=innodb character set=UTF8 collate utf8_unicode_ci;';

    public function safeUp()
    {
        $this->createTable(self::TABLE_SCHEMA, [
            'id' => 'pk',
            'supplier_id' => 'int(11) not null',
            'from' => 'int(11) default null',
            'to' => 'int(11) default null',
            'created_at' => 'int(11) not null',
            'updated_at' => 'int(11) not null',
        ], self::TABLE_OPTIONS);
    }

    public function safeDown()
    {
        $this->dropTable(self::TABLE_SCHEMA);
    }
}
