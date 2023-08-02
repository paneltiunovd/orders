<?php

namespace app\controllers;

use app\models\Orders;
use League\Csv\Exception;
use League\Csv\Writer;
use Yii;
use yii\helpers\VarDumper;
use yii\web\Controller;
use yii\web\RangeNotSatisfiableHttpException;
use yii\web\Response;

class OrdersController extends Controller
{


    public function actionDownload(array $orderIds = []): Response {
        $orders = Orders::find()->where(['in', 'id', $orderIds])->all();
        $records = array_map(function (Orders $order) {
            return $order->getDTO()->toArray();
        }, $orders);

        $header = ['id', 'username', 'link', 'Quantity', 'service', 'status', 'mode', 'date'];

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
