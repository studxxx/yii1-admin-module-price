<?php

class m151201_170006_add_default_currencies extends CDbMigration
{
    const TABLE_SCHEMA = 'price_currencies';

    public function safeUp()
    {
        $this->insert(self::TABLE_SCHEMA, [
            'code' => 'uah',
            'name' => 'Гривня',
            'value' => 1,
            'default' => 1,
            'created_at' => time(),
            'updated_at' => time(),
        ]);
    }

    public function safeDown()
    {
        $this->delete(self::TABLE_SCHEMA, [
            'code' => 'uah'
        ]);
    }
}
