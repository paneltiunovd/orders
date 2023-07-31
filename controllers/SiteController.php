<?php

namespace app\controllers;

use app\models\Orders;
use yii\data\Pagination;
use yii\helpers\Url;
use yii\web\Controller;
use yii\web\NotFoundHttpException;

class SiteController extends Controller
{

    const ID_TYPE = 1;
    const LINK_TYPE = 2;
    const USERNAME_TYPE = 3;

    const EQ_OPERATOR = '=';
    const LIKE_OPERATOR = 'like';

    const AVAILABLE_TYPES_FIELDS_AND_OPERATOR = [
        self::ID_TYPE => ['id', self::EQ_OPERATOR],
        self::LINK_TYPE => ['link', self::LIKE_OPERATOR],
        self::USERNAME_TYPE => ['user_name', 'like'],
    ];


    /**
     * Displays homepage.
     *
     * @return string
     * @throws NotFoundHttpException
     */
    public function actionIndex(
        string $search = null,
        int $searchType = null,
        int $mode = null
    ): string
    {
        $query = Orders::find();
        if($searchType !== null) {
            if($this->checkConstEnum($searchType, self::AVAILABLE_TYPES_FIELDS_AND_OPERATOR)) {
                throw new NotFoundHttpException();
            }
            if($search !== null) {
                $arr = self::AVAILABLE_TYPES_FIELDS_AND_OPERATOR[$searchType];
                $field = $arr[0];
                $operator = $arr[1];
                $query->where("{$field} {$operator} :field", ['field' => $operator === self::LIKE_OPERATOR ? "%{$search}%" : $search]);
            }
        }

        if($mode !== null) {
            if($this->checkConstEnum($mode, [1, 2])) {
                throw new NotFoundHttpException();
            }
            $query->where('mode', $mode);
        }

        $pageSize = 100;

        $pagination = new Pagination([
            'defaultPageSize' => $pageSize,
            'totalCount' => $query->count(),
        ]);

        $orders = $query->orderBy('id')
            ->offset($pagination->offset)
            ->limit($pagination->limit)
            ->all();


        $total = $pagination->totalCount;

        $totalPages = (int) (($total + $pageSize - 1) / $pageSize);

        return $this->render('index', [
            'orders' => $orders,
            'pagination' => $pagination,
        ] + compact('search', 'searchType', 'pageSize', 'totalPages'));
    }

    private function checkConstEnum($searchType, array $array): bool
    {
        return!key_exists($searchType, $array);
    }

}
