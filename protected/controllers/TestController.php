<?php

class TestController extends Controller
{
    public function filters()
    {
        return array(
            'accessControl',
        );
    }

    public function accessRules()
    {
        return array(
            array('allow',
                'users'=>array('*'),
            )
        );
    }

    public function actionIndex()
    {
        if (empty(Yii::app()->session['rest_api'])) {
            $this->render('index');
        } else {
            $this->redirect('/test/done');
        }
    }

    public function actionDone()
    {
        $identity = new UserIdentity(null,null);
        if($identity->authenticate()) {
            Yii::app()->user->login($identity);
            $this->redirect('/test/me');            
        }
        else
            $this->redirect('/');
    }

    public function actionMe()
    {
        $this->render('me');
    }

    public function actionCallback()
    {
        $code = Yii::app()->request->getQuery('code', null);
        $token = Yii::app()->request->getQuery('access_token', null);

        if (!empty($code)) {
            list($body, $info) = Yii::app()->rest->post('/auth/access_token', array(
                'client_id'        => Yii::app()->rest->app_id,
                'client_secret'    => Yii::app()->rest->app_secret,
                'code'             => $code,
                'grant_type'       => 'authorization_code',
                'redirect_uri'     => Yii::app()->createAbsoluteUrl('/test/callback')
            ));

            if ($info['status_code'] == 200) {
                $session = array();

                if (!empty($body)) {
                    foreach ($body as $key => $value) {
                        $session[$key] = $value;
                    }
                }

                Yii::app()->session['rest_api'] = $session;

                $this->redirect(Yii::app()->createAbsoluteUrl('/test/done'));
            } else  {
                if (isset($body->error)) {
                    Yii::app()->user->setFlash('error', Inflector::classify($body->error));
                } else {
                    Yii::app()->user->setFlash('error', 'Something goes wrong, maybe your code is already expired.');
                }
                $this->redirect(Yii::app()->createAbsoluteUrl('/test'));
            }
        } else if (!empty($token)) {
            Yii::app()->session['rest_api'] = array('access_token' => $token);
            $this->redirect(Yii::app()->createAbsoluteUrl('/test'));
        } else {
            $this->redirect(Yii::app()->createAbsoluteUrl('/test'));
        }
    }
}