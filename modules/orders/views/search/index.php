<?php

use app\modules\orders\controllers\SearchController;
use app\modules\orders\DTO\OrderDTO;
use app\modules\orders\DTO\ServiceFrontDTO;
use app\modules\orders\enums\OrderModeEnum;
use app\modules\orders\enums\OrderStatusEnum;
use app\modules\orders\enums\SearchTypeEnum;
use app\modules\orders\models\Orders;
use yii\bootstrap5\LinkPager;
use yii\helpers\Html;
use yii\helpers\Url;

?>
<ul class="nav nav-tabs p-b">
    <li class="active"><a href="<?= SearchController::urlWithParams($get, 'status', -1) ?>">All orders</a></li>
    <style>
        .disabledBtn {
            color: currentColor;
            cursor: not-allowed;
            opacity: 0.5;
            text-decoration: none;
        }
    </style>
    <?php
    /** @var array $status */
    foreach ($statusesList as $status): ?>
        <?php

            $statusId = $status['status'];
            $statusName = OrderStatusEnum::texts[$status['status']];
            $statusName = "{$statusName} ({$status['status_count']})";
        ?>
        <li><a <?= $status['disabled'] ? 'class="disabledBtn"' : 'href="' . SearchController::urlWithParams($get, 'status', $statusId) . '"' ?>><?= $statusName ?></a></li>
    <?php endforeach; ?>
    <li class="pull-right custom-search">
        <form class="form-inline" action="<?= Url::toRoute('/orders/search', $get) ?>" method="get">
            <?php
            foreach ($get as $key => $value):
                if(!($key === 'search' || $key === 'searchType')) {?>
                    <input type="hidden" name="<?= $key ?>" value="<?= $value ?>">
                <?php }?>
            <?php endforeach; ?>
            <div class="input-group">
                <input type="text" name="search" class="form-control" value="<?= $searchType !== SearchTypeEnum::STATUS_TYPE ? $search : '' ?>" placeholder="Search orders">
                <span class="input-group-btn search-select-wrap">

            <select class="form-control search-select" name="searchType">
                <?php
                foreach ($searchTypes as $type): ?>
                    <option <?= Html::encode(($searchType == $type['id'] ? 'selected': '')) ?> value="<?= $type['id'] ?>"><?= $type['name'] ?></option>
                <?php endforeach; ?>
            </select>
            <button type="submit" class="btn btn-default"><span class="glyphicon glyphicon-search" aria-hidden="true"></span></button>
            </span>
            </div>
        </form>
    </li>
</ul>
<table class="table order-table">
    <thead>
    <tr>
        <th><?= Yii::t('app', 'ID') ?></th>
        <th><?= Yii::t('app', 'User') ?></th>
        <th><?= Yii::t('app', 'Link') ?></th>
        <th><?= Yii::t('app', 'Quantity') ?></th>
        <th class="dropdown-th">
            <div class="dropdown">
                <button class="btn btn-th btn-default dropdown-toggle" type="button" id="dropdownMenu1" data-toggle="dropdown" aria-haspopup="true" aria-expanded="true">
                    <?= Yii::t('app', 'Service') ?>
                    <span class="caret"></span>
                </button>
                <ul class="dropdown-menu" aria-labelledby="dropdownMenu1">
                    <li class="active"><a href="<?= SearchController::urlWithParams($get, 'service', -1) ?>">All (<?= $total ?>)</a></li>
                    <?php /** @var Orders $order */
                    /** @var ServiceFrontDTO $serviceDTO */
                    foreach ($serviceList as $key => $serviceDTO): ?>
                        <li><a href="<?= SearchController::urlWithParams($get, 'service', $serviceDTO->getServiceId()) ?>"><span class="label-id"><?= $serviceDTO->getCountOrders() ?></span> <?= $serviceDTO->getServiceName() ?></a></li>
                    <?php endforeach; ?>
                </ul>
            </div>
        </th>
        <th><?= Yii::t('app', 'Status') ?></th>
        <th class="dropdown-th">
            <div class="dropdown">
                <button class="btn btn-th btn-default dropdown-toggle" type="button" id="dropdownMenu1" data-toggle="dropdown" aria-haspopup="true" aria-expanded="true">
                    Mode
                    <span class="caret"></span>
                </button>
                <ul class="dropdown-menu" aria-labelledby="dropdownMenu1">
                    <?php /** @var Orders $order */
                    /** @var ServiceFrontDTO $serviceDTO */
                    foreach (OrderModeEnum::getValues() as $value): ?>
                        <li><a href="<?= SearchController::urlWithParams($get, 'mode', (int) $value) ?>"> <?= OrderModeEnum::texts[$value] ?></a></li>
                    <?php endforeach; ?>
                </ul>
            </div>
        </th>
        <th><?= Yii::t('app', 'Created') ?></th>
    </tr>
    </thead>
    <tbody>
    <?php
        /** @var OrderDTO $order */
        foreach ($orders as $order):
            ?>
            <tr>
                <td><?= Html::encode($order->id) ?></td>
                <td><?= Html::encode($order->username) ?></td>
                <td class="link"><?= Html::encode($order->link) ?></td>
                <td><?= Html::encode($order->quantity) ?></td>
                <td class="service">
                    <span class="label-id"><?= Html::encode($order->service_id) ?></span> <?= Html::encode($order->service_name) ?>
                </td>
                <td><?= Html::encode($order->human_reed_status) ?></td>
                <td><?= Html::encode($order->human_reed_mode) ?></td>
                <td>
                    <span class="nowrap">
                        <?= Html::encode($order->formatted_date_first) ?>
                    </span>
                    <span class="nowrap">
                        <?= Html::encode($order->formatted_date_second) ?>
                    </span>
                </td>
            </tr>
        <?php endforeach; ?>
        <form action="<?= Url::toRoute(['/orders/download']) ?>">

            <?php
            /** @var OrderDTO $order */
            foreach ($ordersIds as $id):
                ?>
                <input name="ids[]" type="hidden" value="<?= $id ?>">
            <?php endforeach; ?>
            <button type="submit"><?= Yii::t('app', 'Save result') . '('. count($orders) . ')' ?>)</button>
        </form>
    </tbody>
</table>
<div class="row">
    <div class="col-sm-8">
        <nav>
            <?= LinkPager::widget([
                'pagination' => $pagination,
                'registerLinkTags' => true
            ]) ?>

        </nav>

    </div>


</div>