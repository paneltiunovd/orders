<?php

namespace app\controllers;

use app\Enums\OperatorEnum;
use app\Enums\SearchTypeEnum;
use app\models\Orders;
use Yii;
use yii\data\Pagination;
use yii\db\ActiveQuery;
use yii\db\Expression;
use yii\web\Controller;

class SiteController extends Controller
{

    private static function processingFilter(ActiveQuery &$query, ?string $search, int $searchType)
    {
        if($search !== null && $search !== '') {
            $searchUse = strtoupper($search);
            $arr = SearchTypeEnum::AVAILABLE_TYPES_FIELDS_AND_OPERATOR[$searchType];
            $field = $arr[0];
            $operator = $arr[1];
            $condition = "{$field} {$operator} :field";
            $searchContent = ['field' => $operator === OperatorEnum::LIKE_OPERATOR ? "%{$searchUse}%" : $search];
            if($searchType === SearchTypeEnum::USERNAME_TYPE) {
                $query->with(['users' => function (ActiveQuery $query) use ($searchUse) {
                    $query->select(["first_name", new Expression("CONCAT(`first_name`, `last_name`) as name")])
                        ->where("upper(first_name) like binary :name", [
                            'name' => "%{$searchUse}%",
                        ]);
                }]);
            } else {
                $query = $query->where($condition, $searchContent);
            }
            Yii::debug($query->sql);;
        }
    }

    public function actionIndex(
        string $search = null,
        int $searchType = null,
        string $status = null
    ): string
    {
        $query = Orders::find()->orderBy([
            'id' => SORT_DESC,
        ]);
        if($status !== null) {
            self::processingFilter($query, $status, SearchTypeEnum::STATUS_TYPE);
        }
        if($searchType !== null) {
            self::processingFilter($query, $search, $searchType);
        }

        $get = Yii::$app->request->get();
        unset($get['page']);
        unset($get['per-page']);

        $total = $query->count();
        $pageSize = 100;

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

        $totalPages = (int) ceil($query->count() / $pageSize);

        $statusesList = $query->select(['COUNT(id) as status_count', 'id', 'status'])
            ->groupBy(['status'])
            ->having('COUNT(*) > 1')
            ->all();

        $searchTypes = array_map(function ($type) {
            return [
                'id' => $type,
                'name' => SearchTypeEnum::available_texts_for_dropdown[$type]
            ];
        }, array_filter(SearchTypeEnum::getValues(), function ($type) {
            return is_int($type) && key_exists($type, SearchTypeEnum::available_texts_for_dropdown);
        }));

        $queryParams = function (bool $isStart = false) use ($get): string {
            return count($get) > 0 ? ($isStart ? "?" : "&") . http_build_query($get) : '';
        };

        return $this->render('index', [
                'orders' => $orders,
                'pagination' => $pagination,
            ] + compact('search', 'queryParams', 'searchType', 'totalPages', 'total', 'statusesList', 'searchTypes', 'status'));
    }

}
