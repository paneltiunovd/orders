<?php

use app\Enums\TypeEnum;
use app\models\Orders;
use app\models\User;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\LinkPager;

?>
<ul class="nav nav-tabs p-b">
    <li class="active"><a href="#">All orders</a></li>
    <li><a href="#">Pending</a></li>
    <li><a href="#">In progress</a></li>
    <li><a href="#">Completed</a></li>
    <li><a href="#">Canceled</a></li>
    <li><a href="#">Error</a></li>
    <li class="pull-right custom-search">
        <form class="form-inline" action="<?php echo Url::toRoute('/') ?>" method="get">
            <div class="input-group">
                <input type="text" name="search" class="form-control" value="<?= Html::encode($search) ?>" placeholder="Search orders">
                <span class="input-group-btn search-select-wrap">

            <select class="form-control search-select" name="searchType">
              <option <?= Html::encode(($searchType == 1 ? 'selected': '')) ?> value="1">Order ID</option>
              <option <?= Html::encode(($searchType == 2 ? 'selected': '')) ?> value="2">Link</option>
              <option <?= Html::encode(($searchType == 3 ? 'selected': '')) ?> value="3">Username</option>
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
                    <li class="active"><a href="">All (894931)</a></li>

                    <?php /** @var Orders $order */
                    foreach ([] as $service): ?>
                        <li><a href=""><span class="label-id">214</span> Real Views</a></li>
<!--                        <li><a href=""><span class="label-id">215</span> Page Likes</a></li>-->
<!--                        <li><a href=""><span class="label-id">10</span> Page Likes</a></li>-->
<!--                        <li><a href=""><span class="label-id">217</span> Page Likes</a></li>-->
<!--                        <li><a href=""><span class="label-id">221</span> Followers</a></li>-->
<!--                        <li><a href=""><span class="label-id">224</span> Groups Join</a></li>-->
<!--                        <li><a href=""><span class="label-id">230</span> Website Likes</a></li>-->
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
                    <li class="active"><a href="">All</a></li>
                    <li><a href="">Manual</a></li>
                    <li><a href="">Auto</a></li>
                </ul>
            </div>
        </th>
        <th>Created</th>
    </tr>
    </thead>
    <tbody>
    <?php
        /** @var Orders $order */
        foreach ($orders as $order): ?>
            <?php
            try {
                $serviceDTO = $order->serviceFrontDTO();
            } catch (Exception $e) {
                $serviceDTO = null;
            }
            ?>
            <tr>
                <td><?= Html::encode($order->id) ?></td>
                <td><?= Html::encode($order->users->getName($searchType === TypeEnum::USERNAME_TYPE, $search)) ?></td>
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
            <?php
                echo LinkPager::widget([
                    'pagination' => $pagination,
                ]);
            ?>
<!--            <ul class="pagination">-->
<!--                <li class="disabled"><a href="" aria-label="Previous">&laquo;</a></li>-->
<!--                <li class="active"><a href="">1</a></li>-->
<!--                <li><a href="">2</a></li>-->
<!--                <li><a href="">3</a></li>-->
<!--                <li><a href="">4</a></li>-->
<!--                <li><a href="">5</a></li>-->
<!--                <li><a href="">6</a></li>-->
<!--                <li><a href="">7</a></li>-->
<!--                <li><a href="">8</a></li>-->
<!--                <li><a href="">9</a></li>-->
<!--                <li><a href="">10</a></li>-->
<!--                <li><a href="" aria-label="Next">&raquo;</a></li>-->
<!--            </ul>-->
        </nav>

    </div>
    <div class="col-sm-4 pagination-counters">

        <?php echo $pagination->page + 1 ?> to <?php echo $totalPages ?> of <?php echo $total?>
    </div>

</div>