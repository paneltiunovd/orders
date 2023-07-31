<?php

namespace app\controllers;

use Yii;
use yii\filters\AccessControl;
use yii\helpers\Url;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\web\Response;
use yii\filters\VerbFilter;
use app\models\LoginForm;
use app\models\ContactForm;

class OrdersController extends Controller
{

    /**
     * @throws NotFoundHttpException
     */
    public function actionSearch(): Response
    {

        return $this->redirect(Url::toRoute('/'));
    }

}
