<?php

namespace app\modules\orders\controllers;

use app\modules\orders\models\Orders;
use League\Csv\Exception;
use League\Csv\Writer;
use Yii;
use yii\helpers\VarDumper;
use yii\web\Controller;
use yii\web\RangeNotSatisfiableHttpException;
use yii\web\Response;

class DownloadController extends Controller
{

    public function actionIndex(array $ids = []): Response {
        $header = ['id', 'username', 'link', 'quantity', 'status', 'mode', 'date', 'time', 'service'];

        $orders = Orders::find()->where(['in', 'id', $ids])->all();
        $records = array_map(function (Orders $order) {
            return $order->getDTO(true)->toArray();
        }, $orders);

        $csv = Writer::createFromString();

        $csv->insertOne($header);
        $csv->insertAll($records);

        try {
            return Yii::$app->response->sendContentAsFile($csv->toString(), 'orders.csv', [
                'mimeType' => 'application/csv',
                'inline' => false
            ]);
        } catch (RangeNotSatisfiableHttpException|Exception $e) {
            $response = Yii::$app->response;
            $response->format = Response::FORMAT_JSON;
            $response->data = ['error' => $e->getMessage()];
            return $response;
        }
    }

}
