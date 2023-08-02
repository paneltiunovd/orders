<?php

namespace app\controllers;

use app\Enums\OperatorEnum;
use app\Enums\OrderModeEnum;
use app\Enums\OrderStatusEnum;
use app\Enums\SearchTypeEnum;
use app\models\DTO\ServiceFrontDTO;
use app\models\Orders;
use app\models\Services;
use Yii;
use yii\data\Pagination;
use yii\db\ActiveQuery;
use yii\helpers\VarDumper;
use yii\web\Controller;

class SiteController extends Controller
{

    public static function urlWithParams(array $array, string $key, int $id): string {
        $isWhat = $id === -1;
        if(array_key_exists($key, $array) || $isWhat) {
            unset($array[$key]);
        }
        return (!$isWhat ? '?' . $key . '=' . $id : '') . self::arrayToGet($array, $isWhat);
    }

    public static function arrayToGet(array $array, bool $isStart = false): string
    {
        return count($array) > 0 ? ($isStart ? "?" : "&") . http_build_query($array) : '';
    }

    private static function processingFilter(ActiveQuery $query, ?string $search, int $searchType)
    {
        if($search !== null && $search !== '') {
            $searchUse = strtoupper($search);
            $arr = SearchTypeEnum::AVAILABLE_TYPES_FIELDS_AND_OPERATOR[$searchType];
            $field = $arr[0];
            $operator = $arr[1];
            $mField = 'field' . str_replace('.', '', $field);
            $condition = "{$field} {$operator} :" . $mField;
            $searchContent = [$mField => $operator === OperatorEnum::LIKE_OPERATOR ? "%{$searchUse}%" : $search];
            if($searchType === SearchTypeEnum::USERNAME_TYPE) {
                $query->joinWith(['users' => function (ActiveQuery $users) use ($searchUse) {
                     $users
                        ->where("upper(first_name) like binary :first or upper(last_name) like binary :last", [
                            'first' => "%{$searchUse}%",
                            'last' => "%{$searchUse}%",
                        ]);
                }]);
            } else {
               $query->andWhere($condition, $searchContent);
            }
        }
    }

    public function actionIndex(
        string $search = null,
        int $searchType = null,
        string $status = null,
        int $mode = null,
        string $service = null
    ): string
    {
        $query = Orders::find()->orderBy([
            'orders.id' => SORT_DESC,
        ]);
        if($status !== null) {
            self::processingFilter($query, $status, SearchTypeEnum::STATUS_TYPE);
        }
        if($mode !== null) {
            self::processingFilter($query, $mode, SearchTypeEnum::MODE_TYPE);
        }
        if($service !== null) {
            self::processingFilter($query, $service, SearchTypeEnum::SERVICE_TYPE);
        }
        if($searchType !== null) {
            self::processingFilter($query, $search, $searchType);
        }

        $statusesOrderQuery = clone $query;

        $get = Yii::$app->request->get();

        $total = $query->count();
        $pageSize = 100;
        $countQuery = clone $query;

        $pagination = new Pagination([
            'totalCount' => $countQuery->count(),
            'pageSize' => $pageSize,
            'route' => '/' . self::arrayToGet($get, true)
        ]);

        $pagination->pageSizeParam = false;
        $pagination->forcePageParam = false;

        $orders = $query
            ->joinWith('service')
            ->offset($pagination->offset)
            ->limit($pagination->limit)
            ->all();

        $existedStatuses = $statusesOrderQuery->addSelect(['COUNT(orders.id) as status_count', 'status', 'mode', 'service_id'])
            ->groupBy(['status'])
            ->having('COUNT(*) > 1')
            ->all();

        $notExistedAndZero = [];
        $statusesList = array_merge($existedStatuses, $notExistedAndZero);

        $totalPages = (int) ceil($query->count() / $pageSize);

        $searchTypes = array_map(function ($type) {
            return [
                'id' => $type,
                'name' => SearchTypeEnum::available_texts_for_dropdown[$type]
            ];
        }, array_filter(SearchTypeEnum::getValues(), function ($type) {
            return key_exists($type, SearchTypeEnum::available_texts_for_dropdown);
        }));

        $serviceList = [];
        /** @var Services $service */
        foreach (Services::find()->all() as $service) {
            $serviceList[$service->id] = new ServiceFrontDTO($service);
        }

        return $this->render('index', compact(
            'pagination',
            'orders',
            'search',
            'searchTypes',
            'status',
            'mode',
            'serviceList',
            'searchType',
            'get',
            'totalPages',
            'total',
            'statusesList',
        ));
    }

}
