<?php if (empty(Yii::app()->session['rest_api'])) : ?>
    <a href="http://api-php.local/auth/authorize?client_id=<?php echo Yii::app()->rest->app_id; ?>&response_type=code" class="orange">Login</a>
<?php else : ?>
    Success
<?php endif; ?>