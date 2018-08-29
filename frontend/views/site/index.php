<?php

/* @var $this yii\web\View */

$this->title = 'My Yii Application';
?>
<div class="site-index">

    <div class="jumbotron">
        <h1>Messaging API backend</h1>


    </div>

    <div class="body-content">

        <div class="row">
            <div class="col-lg-12">
                <h2>Documentation</h2>


                <p>POST /api/user/register - регистрация<br>
                    POST /api/user/login - логин<br>
                    GET /api - получить инфо и вресию АПИ<br>
                    GET /api/message/get?type=sent&status=1 - получить историю сообщение (обязательно type = sent или type = received), статус опционально (0 - отправленные, 1 - доставленные, 2 - прочитанные). Если не указан - все<br>
                    POST /api/message/send - отправить сообщение<br>
                    GET /api/message/{message_id} - прочитать сообщение<br>
                    DELETE  /api/message/{message_id} - удалить сообщение<br>
                    </p>

                <p>

                    API оформил в виде отдельного приложения, тестами покрыл часть кода, реально на такую задачу надо больше времени чтобы сделать все красиво, но вариант уже рабочий..
                    Для того чтобы в тесты засунуть работу с АПИ пришлось захардкодить<br>
                    const API_ENDPOINT = 'http://techmedia4u.local/api';
                    Если вдруг будете поднимать задачу у себя - надо поменять<br>

                </p>


            </div>


        </div>

    </div>
</div>
