<?php

class m151201_170003_create_table_price_currencies extends CDbMigration
{
    const TABLE_SCHEMA = 'price_templates';
    const TABLE_OPTIONS = 'engine=innodb character set=UTF8 collate utf8_unicode_ci;';

    public function safeUp()
    {
        $this->createTable(self::TABLE_SCHEMA, [
            'id' => 'pk',
            'code' => 'string default null',
            'name' => 'string default null',
            'value' => 'float default null',
            'default' => 'tinyint(1) default 0',
            'created_at' => 'int(11) not null',
            'updated_at' => 'int(11) not null',
        ], self::TABLE_OPTIONS);
    }

    public function safeDown()
    {
        $this->dropTable(self::TABLE_SCHEMA);
    }
}
