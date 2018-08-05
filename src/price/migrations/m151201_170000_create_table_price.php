<?php

class m151201_170000_create_table_price extends CDbMigration
{
    const TABLE_SCHEMA = 'prices';
    const TABLE_OPTIONS = 'engine=innodb character set=UTF8 collate utf8_unicode_ci;';

    public function safeUp()
    {
        $this->createTable(self::TABLE_SCHEMA, [
            'id' => 'pk',
            'supplier_id' => 'int(11) not null',
            'sku' => 'string default null',
            'search' => 'string default null',
            'brand' => 'string default null',
            'name' => 'string default null',
            'description' => 'string default null',
            'price' => 'string default null',
            'exist' => 'string default null',
            'count' => 'string default null',
            'type' => 'string default null',
            'delivery' => 'string default null',
            'vendor' => 'string default null',
            'note' => 'string default null',
            'construction_number' => 'string default null',
            'constructions' => 'string default null',
            'cross_numbers' => 'string default null',
            'tecdoc_article_id' => 'string default null',
            'tecdoc_article_nr' => 'string default null',
            'tecdoc_supplier_brand' => 'string default null',
            'tecdoc_supplier_id' => 'string default null',
            'state' => 'string default null',
            'marker' => 'string default null', // row hash from price
            'visible' => 'tinyint(1) default null', // show|hide
            'status' => 'tinyint(1) default null',
            'created_at' => 'int(11) not null',
            'updated_at' => 'int(11) not null',
        ], self::TABLE_OPTIONS);
    }

    public function safeDown()
    {
        $this->dropTable(self::TABLE_SCHEMA);
    }
}
