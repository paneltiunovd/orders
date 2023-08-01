<?php

namespace app\controllers;

use app\Enums\TypeEnum;
use app\models\Orders;
use Yii;
use yii\data\Pagination;
use yii\db\ActiveQuery;
use yii\web\Controller;
use yii\web\NotFoundHttpException;

class SiteController extends Controller
{

    /**
     * @throws NotFoundHttpException
     */
    public function actionIndex(
        string $search = null,
        int $searchType = null
    ): string
    {
        $query = Orders::find()->orderBy([
            'id' => SORT_DESC,
        ]);
        if($searchType !== null) {
            if(!key_exists($searchType, TypeEnum::AVAILABLE_TYPES_FIELDS_AND_OPERATOR)) {
                throw new NotFoundHttpException();
            }

            if($search !== null) {
                $searchUse = strtoupper($search);
                $arr = TypeEnum::AVAILABLE_TYPES_FIELDS_AND_OPERATOR[$searchType];
                $field = $arr[0];
                $operator = $arr[1];
                $condition = "{$field} {$operator} :field";
                $searchContent = ['field' => $operator === TypeEnum::LIKE_OPERATOR ? "%{$searchUse}%" : $searchUse];
                if($searchType === TypeEnum::USERNAME_TYPE) {
                    $query->with(['users' => function (ActiveQuery $query) use ($searchUse, $operator) {
                        $query->where("upper(first_name) like binary :first", [
                            'first' => "%{$searchUse}%"
                        ]);
                        $query->orWhere("upper(last_name) like binary :last", [
                            'last' => "%{$searchUse}%"
                        ]);
                    }]);
                } else {
                    $query->where($condition, $searchContent);
                }
            }
        }

        $request = Yii::$app->request;
        $get = $request->get();
        unset($get['page']);
        unset($get['per-page']);
        $pageSize = 100;

        $countQuery = clone $query;
        $total = $countQuery->count();

        $pagination = new Pagination([
            'defaultPageSize' => $pageSize,
            'totalCount' => $total,
            'route' => '/' . count($get) > 0 ? "?" . http_build_query($get) : '',
        ]);

        $orders = array_filter($query
            ->offset($pagination->offset)
            ->limit($pagination->limit)
            ->all(), function (Orders $order) {
            return $order->users !== null;
        });


        $totalPages = (int) ceil($total / $pageSize);

        return $this->render('index', [
                'orders' => $orders,
                'pagination' => $pagination,
            ] + compact('search', 'searchType', 'totalPages', 'total'));
    }

}
