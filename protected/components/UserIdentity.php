<?php

/**
 * UserIdentity represents the data needed to identity a user.
 * It contains the authentication method that checks if the provided
 * data can identity the user.
 */
class UserIdentity extends CUserIdentity
{
    private $_id;
    public function authenticate()
    {
        list($body, $info) = Yii::app()->rest->post('/users/me', array(
            'token'     => Yii::app()->session['rest_api']['access_token'],
        ));

        if ($info['status_code'] == 200) {
            $this->_id=$body->id;
            $this->username=$body->username;
            $this->setState('profile', $body);
            $this->errorCode=self::ERROR_NONE;            
        } else {
            $this->errorCode=self::ERROR_USERNAME_INVALID;
        }

        return !$this->errorCode;
    }
 
    public function getId()
    {
        return $this->_id;
    }
}