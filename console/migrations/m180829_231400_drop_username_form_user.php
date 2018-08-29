<?php

use yii\db\Migration;

class m180829_231400_drop_username_form_user extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function up()
    {
        $this->dropColumn('user', 'username');
    }

    /**
     * {@inheritdoc}
     */
    public function down()
    {
        $this->addColumn('user', 'username', $this->string());
    }

}
