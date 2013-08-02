<?php
/* @var $this SiteController */

$this->pageTitle=Yii::app()->name;
?>

<?php $this->beginWidget('bootstrap.widgets.TbHeroUnit',array(
    'heading'=>'Welcome to ' . CHtml::encode(Yii::app()->name),
)); ?>

<p>&nbsp;</p>
<p>
    This is sample client for OAuth2 authorization with PHP Based API.
    You can see login demo by clicking login on menu.
</p>

<p>You can fork this project at <a href="https://github.com/rolies106/yii-api" class="btn btn-primary">GitHub</a></p>


<?php $this->endWidget(); ?>