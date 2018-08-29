<?php

use yii\db\Migration;

class m180829_231000_add_phone_number_to_user extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function up()
    {
        $this->addColumn('user', 'phone_number', $this->string());
    }

    /**
     * {@inheritdoc}
     */
    public function down()
    {
        $this->dropColumn('user', 'phone_number');
    }

}
