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
    'CASE_DESCRIPTION',
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
        'expandIcon' => '<span class="fa fa-plus"></span>',
        'collapseIcon' => '<span class="fa fa-minus"></span>',
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
    [
        'attribute' => 'REPORTED_BY',
        'value' => 'REPORTED_BY',
        //'group' => false,  // enable grouping
        //'subGroupOf' => 1,
        'visible' => false
    ],
    [
        'attribute' => 'REPORTED_BY',
        'value' => 'REPORTED_BY',
        //'group' => false,  // enable grouping
        //'subGroupOf' => 2
    ],
    [
        'attribute' => 'DATE_REPORTED',
        'format' => 'date',
        'value' => function ($data) {
            $date_time = \app\components\DATA_FACTORY::StringToDateTime($data->DATE_REPORTED);
            return $date_time;
        },
    ],
    [
        'attribute' => 'DATE_ADDED',
        'format' => 'date',
        'value' => function ($data) {
            $date_time = \app\components\DATA_FACTORY::StringToDateTime($data->DATE_ADDED);
            return $date_time;
        },
    ],
    [
        //lets build the document link
        'header' => 'Download Document',
        'attribute' => 'INCIDENCE_ID',
        'format' => 'raw',
        'value' => function ($model, $key, $index) {
            /* @var $model \app\modules\reporting\models\TRACKING_MODEL */
            /* @var $fileupload \app\modules\tracking\models\FILEUPLOAD */
            $fileupload = new \app\modules\tracking\models\FILEUPLOAD();
            $data = $fileupload->GET_UPLOADED_CASE_FILE($model->INCIDENCE_ID);

            if ($data == null) {
                //no files
                return 'No Document';
            }
            $path = $data->FILE_PATH;

            $host = Yii::$app->request->hostInfo . Yii::$app->request->baseUrl;


            if (strpos($host, "http://") !== false || strpos($host, "https://") !== false) {
                //do not suffix
                $file_url = $host . $path;
            } else {
                $file_url = $host . $path;
            }
            //return "<a href='$file_url' target='_blank'>Download <span class='glyphicon glyphicon-download'></span></a>";
            return Html::a("Download {$data->FILE_NAME}", $file_url, ['class' => 'btn btn-primary btn-sm btn-block', 'target' => '_blank']);
        }
    ],
    ['class' => '\kartik\grid\ActionColumn',
        'width' => '10%',
        'visible' => true,
        'dropdown' => false,
        'vAlign' => 'middle',
        'template' => '{action}',
        'buttons' => [
            'action' => function ($url, $model, $key) {
                return Html::a('View Case <i class="glyphicon glyphicon-eye-open"></i>', $url, ['class' => 'btn btn-default btn-sm']);
            },
        ],
        'urlCreator' => function ($action, $model, $key, $index) {
            if ($action === 'action') {
                $url = \yii\helpers\Url::toRoute(['//case-action', 'id' => $model->INCIDENCE_ID]);
                return $url;
            }
        },
    ],
];
?>

<div class="panel panel-primary">
    <div class="panel-heading"><?= Html::encode($this->title) ?></div>
    <div class="panel-body">
        <div class="row">
            <?= Html::a('Return to Dashboard', ['//dashboard'], ['class' => 'btn btn-success']) ?>
        </div>
        <div class="row">
            <?= GridView::widget([
                'dataProvider' => $dataProvider,
                //'filterModel' => $searchModel,
                'layout' => '{summary}{pager}{items}{pager}',
                'export' => false,
                'pjax' => false,
                'summary' => '',
                'condensed' => true,
                'responsive' => true,
                'hover' => true,
                'columns' => $gridColumns,
                'resizableColumns' => true,
            ]); ?>
        </div>
    </div>
</div>