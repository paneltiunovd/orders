<?php

use app\controllers\SiteController;
use app\Enums\OrderModeEnum;
use app\Enums\OrderStatusEnum;
use app\Enums\SearchTypeEnum;
use app\models\DTO\ServiceFrontDTO;
use app\models\Orders;
use yii\bootstrap5\LinkPager;
use yii\helpers\Html;

?>
<ul class="nav nav-tabs p-b">
    <li class="active"><a href="/">All orders</a></li>
    <?php
    /** @var Orders $response */
    foreach ($statusesList as $response): ?>
        <?php
            $get = Yii::$app->request->get();

            $statusId = $response->status ;
            $statusName = OrderStatusEnum::texts[$response->status];
            $statusName = "{$statusName} ({$response->status_count})";
        ?>
        <li><a href="?status=<?= $statusId .SiteController::arrayToGet($get) ?>"><?= $statusName ?></a></li>
    <?php endforeach; ?>
    <li class="pull-right custom-search">
        <form class="form-inline" action="/" method="get">

            <?php
            /** @var Orders $response */
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
                /** @var Orders $response */
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
        <th>ID</th>
        <th>User</th>
        <th>Link</th>
        <th>Quantity</th>
        <th class="dropdown-th">
            <div class="dropdown">
                <button class="btn btn-th btn-default dropdown-toggle" type="button" id="dropdownMenu1" data-toggle="dropdown" aria-haspopup="true" aria-expanded="true">
                    Service
                    <span class="caret"></span>
                </button>
                <ul class="dropdown-menu" aria-labelledby="dropdownMenu1">
                    <li class="active"><a href="<?= SiteController::urlWithParams($get, 'service', -1) ?>">All (<?= $total ?>)</a></li>
                    <?php /** @var Orders $order */
                    /** @var ServiceFrontDTO $serviceDTO */
                    foreach ($serviceList as $key => $serviceDTO): ?>
                        <li><a href="<?= SiteController::urlWithParams($get, 'service', $serviceDTO->getServiceId()) ?>"><span class="label-id"><?= $serviceDTO->getCountOrders() ?></span> <?= $serviceDTO->getServiceName() ?></a></li>
                    <?php endforeach; ?>
                </ul>
            </div>
        </th>
        <th>Status</th>
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
                        <li><a href="<?= SiteController::urlWithParams($get, 'mode', (int) $value) ?>"> <?= OrderModeEnum::texts[$value] ?></a></li>
                    <?php endforeach; ?>
                </ul>
            </div>
        </th>
        <th>Created</th>
    </tr>
    </thead>
    <tbody>
    <?php
        /** @var Orders $order */
        foreach ($orders as $order):
            try {
                $serviceDTO = $serviceList[$order->service_id];
            } catch (Exception $e) {
                $serviceDTO = null;
            }
            ?>
            <tr>
                <td><?= Html::encode($order->id) ?></td>
                <td><?= Html::encode($order->users->getName($searchType === SearchTypeEnum::USERNAME_TYPE, $search)) ?></td>
                <td class="link"><?= Html::encode($order->link) ?></td>
                <td><?= Html::encode($order->quantity) ?></td>
                <td class="service">
                    <span class="label-id"><?= Html::encode($serviceDTO->getCountOrders()) ?></span> <?= Html::encode($serviceDTO->getServiceName()) ?>
                </td>
                <td><?= Html::encode($order->getStatusToString()) ?></td>
                <td><?= Html::encode($order->getModeToString()) ?></td>
                <td>
                    <span class="nowrap">
                        <?= Html::encode($order->getDateObject()->getFirstDate()) ?>
                    </span>
                    <span class="nowrap">
                        <?= Html::encode($order->getDateObject()->getSecondDate()) ?>
                    </span>
                </td>
            </tr>
        <?php endforeach; ?>
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