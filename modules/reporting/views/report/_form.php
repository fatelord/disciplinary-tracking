<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model \app\modules\reporting\models\INCIDENCE_MODEL */
/* @var $uploads \app\modules\tracking\models\FILEUPLOAD */
/* @var $student_case \app\models\STUDENT_INCIDENCE */
/* @var $form yii\widgets\ActiveForm */
$description = null;
if (!$model->isNewRecord) {
    $t = gettype($model->CASE_DESCRIPTION);
    if ($t == 'resource') {
        $description = stream_get_contents($model->CASE_DESCRIPTION);
    } else {
        $description = $model->CASE_DESCRIPTION;
    }
}
?>

<div class="incidence--model-form">

    <?php $form = ActiveForm::begin(); ?>
    <?= $form->field($model, 'STUDENT_REG_NO')->dropDownList(\app\modules\reporting\models\INCIDENCE_MODEL::GetStudentsList()) ?>

    <!--?= $form->field($model, 'DATE_REPORTED')->textInput(['maxlength' => true]) ?-->
    <?= $form->field($model, 'DATE_REPORTED')->widget(\kartik\date\DatePicker::classname(), [
        'options' => ['placeholder' => 'Enter reporting date ...'],
        'pluginOptions' => [
            'autoclose' => true,
            //'format' => 'yyyy-dd-M hh:ii'
            'format' => 'dd-M-yyyy',
            'todayHighlight' => true,
            'todayBtn' => true,
        ]
    ]); ?>

    <?= $form->field($model, 'CASE_DESCRIPTION')->textarea(['rows' => 6, 'value' => $description]) ?>

    <?= $form->field($model, 'STATUS_CODE')->dropDownList(\app\models\STATUS_MODEL::GetStatusList(), ['prompt' => 'Select student status']) ?>

    <?= $form->field($model, 'REPORTED_BY')->textInput(['maxlength' => true]) ?>

    <?= $form->field($student_case, 'DISCIPLINARY_TYPE_ID')->dropDownList(\app\models\DISCIPLINARY_TYPE_MODEL::GetDisciplinaryTypeList(), ['prompt' => 'Select Case Type']) ?>

    <?= $form->field($student_case, 'CASE_TYPE_ID')->widget(\kartik\depdrop\DepDrop::classname(), [
        'options' => ['id' => 'case_type_id'],
        'pluginOptions' => [
            'depends' => ['student_incidence-disciplinary_type_id'], //depends on th above dropdown :-)
            'placeholder' => 'Select case type...',
            'url' => \yii\helpers\Url::to(['/site/subcat'])
        ]
    ]); ?>

    <!--?= $form->field($student_case, 'CASE_TYPE_ID')->textInput(['maxlength' => true]) ?-->

    <div class="form-group">
        <?= Html::submitButton($model->isNewRecord ? Yii::t('app', 'Submit Incidence') : Yii::t('app', 'Update Incidence'), ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
