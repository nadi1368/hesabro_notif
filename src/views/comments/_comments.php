<?php
use common\models\Comments;
?>
<div class="box">
    <div class="card-body">
        <table class="table table-bordered">
            <tbody>
            <?php foreach ($comments as $comment): ?>
                <?php $css_class=Comments::itemAlias('CssClass',$comment->css_class) ?>
                <tr>
                    <td width="80%" class="<?= $css_class ?>"><?= $comment->des ?></td>
                    <td class="<?= $css_class ?>">
                        <?= $comment->creator->fullName.' - '.Yii::$app->jdate->date('Y/m/d H:i', $comment->created) ; ?></td>
                </tr>
            <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</div>
