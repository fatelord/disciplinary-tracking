<?php

use yii\helpers\Html;
use kartik\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel \app\modules\reporting\models\CASE_INCIDENCE_MODEL */
/* @var $model \app\modules\reporting\models\CASE_INCIDENCE_MODEL */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->params['breadcrumbs'][] = $this->title;

$gridColumns = [
    ['class' => 'yii\grid\SerialColumn'],
    //'STUDENT_REG_NO',
    [
        'attribute' => 'STUDENT_REG_NO',
        'width' => '100%',
        'value' => function ($model, $key, $index, $widget) {
            /* @var $model \app\modules\reporting\models\CASE_INCIDENCE_MODEL */
            $reg_no = $model->STUDENT_REG_NO;
            $student_model = \app\modules\tracking\extended\STUDENT_MODEL::findOne($reg_no);
            return $student_model != null ? $student_model->getRegFullName() : $reg_no;
        },
        'group' => true,  // enable grouping,
        'groupedRow' => false,                    // move grouped column to a single grouped row
        'groupOddCssClass' => 'kv-grouped-row',  // configure odd group cell css class
        'groupEvenCssClass' => 'kv-grouped-row', // configure even group cell css class
    ],
    [
        'header' => 'Case Status',
        'attribute' => 'INCIDENCE_ID',
        'format' => 'raw',
        'value' => function ($model) {
            /* @var $model \app\modules\reporting\models\TRACKING_MODEL */
            $complete = \app\modules\reporting\models\TRACKING_MODEL::GetTrackingStage($model->INCIDENCE_ID);
            return $complete ? '<span class="btn btn-success btn-block btn-xs">Complete</span>' : '<span class="btn btn-danger btn-block btn-xs">Incomplete</span>';
        },
        //'group' => true,  // enable grouping
        // 'subGroupOf' => 1,
    ],
    [
        'class' => 'kartik\grid\ExpandRowColumn',
        'value' => function () {
            return GridView::ROW_COLLAPSED;
            //return GridView::ROW_EXPANDED;
        },

        'allowBatchToggle' => false,
        'expandOneOnly' => true,
        'expandIcon' => '<span class="fa fa-folder"></span>',
        'collapseIcon' => '<span class="fa fa-folder-open"></span>',
        'detail' => function ($model) {
            /* @var $model \app\modules\reporting\models\CASE_INCIDENCE_MODEL */
            $incidence_id = $model->INCIDENCE_ID;
            $tracking = new \app\modules\reporting\models\TRACKING_MODEL();
            $dataProvider = $tracking->search($incidence_id, \app\components\CONSTANTS::STATUS_PENDING);

            return Yii::$app->controller->renderPartial('_expand_row', [
                'dataProvider' => $dataProvider,
            ]);
        },

        'detailOptions' => [
            //'class' => 'kv-state-enable',
        ],

    ],
    //'CASE_DESCRIPTION',
    //'STATUS_CODE',
    //'REPORTED_BY',
    //'DATE_REPORTED',
    //'DATE_ADDED',
    //'FACULTY_CODE',
    //'COLLEGE_CODE',
    ['class' => '\kartik\grid\ActionColumn',
        'width' => '10%',
        'visible' => false,
        'dropdown' => false,
        'vAlign' => 'middle',
        'template' => '{image}',
        'buttons' => [
            'image' => function ($url, $model, $key) {
                return Html::a('Add Image <i class="glyphicon glyphicon-camera"></i>', $url);
            },
        ],
        'urlCreator' => function ($action, $model, $key, $index) {
            if ($action === 'image') {
                $url = ''; //\yii\helpers\Url::toRoute(['//product/images/add-image', 'product_id' => $model->PRODUCT_ID]);
                return $url;
            }
        },
    ],
    [
        'class' => '\kartik\grid\ActionColumn',
        'dropdown' => true,
        'vAlign' => 'middle',
        'template' => '{update}{view}{delete}',
        'deleteOptions' => ['label' => '<i class="glyphicon glyphicon-remove"></i>Remove'],
        'visible' => false
    ]
];
?>

<div class="panel panel-default">
    <div class="panel-heading"><?= Html::encode($this->title) ?></div>
    <div class="panel-body">
        <div class="row">
            <?= Html::a('Return to Dashboard', ['//dashboard'], ['class' => 'btn btn-success']) ?>
        </div>
        <hr/>
        <div class="products-index row">
            <?= GridView::widget([
                'dataProvider' => $dataProvider,
                //'filterModel' => $searchModel,
                'layout' => '{summary}{pager}{items}{pager}',
                'export' => false,
                'pjax' => true,
                'summary' => '',
                'responsive' => true,
                'hover' => true,
                'columns' => $gridColumns,
                'resizableColumns' => true,
            ]); ?>
        </div>
    </div>
</div>