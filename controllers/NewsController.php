<?php
namespace app\controllers;

use Yii;
use yii\web\Controller;
use yii\filters\AccessControl;
use app\models\News;
use yii\data\ActiveDataProvider;

class NewsController extends Controller
{
    /**
     * @inheritdoc
     */
    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::className(),
                'rules' => [
                    [
                        'allow' => true,
                        'roles' => ['@'],
                    ],
                ],
            ],
        ];
    }
    
    /**
     * Выводим новости (отладка)
     * @return type
     */
    public function actionViewList()
    {
        $dataProvider = new ActiveDataProvider([
            'query' => News::find()->orderBy('pub_date DESC')
        ]);
        
        return $this->render('view-list', [
            'dataProvider' => $dataProvider
        ]);
    }   
    
    /**
     * Обновляем список новостей (отладка)
     * @return type
     */
    public function actionUpdateList()
    {
        (new \app\components\RssParser())->getNews();
        return $this->redirect(Yii::$app->request->referrer);
    }   
    
    /**
     * Переключаем статус новости. 
     * В основном для отладки, когда нет новых новостей, а потестить надо
     * @param type $id
     */
    public function actionToggleNewsStatus($id)
    {
        $model = News::findOne($id);
        if ($model){
            $model->posted = (int)!$model->posted;
            $model->save();
        }
        
        return $this->redirect(Yii::$app->request->referrer);
    }
    
    /**
     * @return type
     */
    public function actionSendManually()
    {
        if (Yii::$app->request->isPost){
            $title = Yii::$app->request->post('title');
            $link = Yii::$app->request->post('link');
            $content = "<a href='". $link ."' target='_blank'>". $title ."</a>";
            
            Yii::$app->telegramBot->sendManually($content);
            return $this->redirect('view-list');
        }
        
        return $this->render('send-manually');
    }        
}
