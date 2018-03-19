<?php

/**
 * @var $this yii\web\View
 * @var $token string
 */

$this->title = 'My Yii Application';
?>
<div class="site-index">

    <div class="jumbotron">
        <h1>Webservice</h1>

        <p class="lead">You need a valid token to access the webservice</p>

        <p>
            <?php

            echo \yii\helpers\Html::a('Generate Token', null, [
                'class' => 'btn btn-lg btn-success',
                'onclick' => '
                    $.pjax.reload({container:"#token-pjax", replace:false, url:"/site/generate-token"});
                '
            ])

            ?>

        </p>
    </div>

    <?php

    \yii\widgets\Pjax::begin([
        'id' => 'token-pjax'
    ]);

    if ($token){

        echo <<<HTML

        <div class="jumbotron">
            <h2>Your token:</h2>
            <div style="display: inline-block;width: 300px;font-size: 20px;border: 1px solid black;background-color: #faffd0;">
                {$token}
            </div>
        </div>

HTML;

    }

    \yii\widgets\Pjax::end();

    ?>

</div>
