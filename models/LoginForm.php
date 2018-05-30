<?php

namespace app\models;

use Yii;
use yii\base\Model;

/**
 * LoginForm is the model behind the login form.
 *
 * @property User|null $user This property is read-only.
 *
 */
class LoginForm extends Model
{
    public $username;
    public $rememberMe = true;

    private $_user = false;


    /**
     * @return array the validation rules.
     */
    public function rules()
    {
        return [
            // username required
            [['username',], 'required'],
            // rememberMe must be a boolean value
            ['rememberMe', 'boolean'],
            ['username', 'validateUsername'],
        ];
    }

    public function validateUsername($attribute, $params)
        {
          if (!$this->hasErrors()) {
            $user = $this->getUser();
            if (!$user) {
              $this->addError($attribute, 'New username. User registered');
              
              $newUser = new Users();
              $newUser->username = $this->username;
              $newUser->save();
              $user = $newUser;
            }
          }
        }
    /**
     * Logs in a user using the provided username.
     * @return bool whether the user is logged in successfully
     */
    public function login()
    {
        if ($this->validate()) {
            return Yii::$app->user->login($this->getUser(), $this->rememberMe ? 3600*24*30 : 0);
        }
        return false;
    }

    /**
     * Finds user by [[username]]
     *
     * @return User|null
     */
    public function getUser()
    {
        if ($this->_user === false) {
            $this->_user = User::findByUsername($this->username);
        }

        return $this->_user;
    }
}
