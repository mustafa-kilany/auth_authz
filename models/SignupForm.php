<?php

namespace app\models;

use Yii;
use yii\base\Model;

/**
 * SignupForm is the model behind the signup form.
 */
class SignupForm extends Model
{
    public $username;
    public $email;
    public $password;
    public $confirm_password;
    public $first_name;
    public $last_name;
    public $role = 'reader'; // Default role for new users

    /**
     * @return array the validation rules.
     */
    public function rules()
    {
        return [
            [['username', 'email', 'password', 'confirm_password', 'first_name', 'last_name'], 'required'],
            [['username', 'email', 'first_name', 'last_name'], 'string', 'max' => 255],
            ['email', 'email'],
            ['password', 'string', 'min' => 6],
            ['confirm_password', 'compare', 'compareAttribute' => 'password', 'message' => 'Passwords do not match.'],
            ['role', 'in', 'range' => ['reader', 'author']], // Only allow reader and author roles for signup
            ['username', 'unique', 'targetClass' => '\app\models\User', 'message' => 'This username has already been taken.'],
            ['email', 'unique', 'targetClass' => '\app\models\User', 'message' => 'This email address has already been taken.'],
        ];
    }

    /**
     * @return array customized attribute labels
     */
    public function attributeLabels()
    {
        return [
            'username' => 'Username',
            'email' => 'Email',
            'password' => 'Password',
            'confirm_password' => 'Confirm Password',
            'first_name' => 'First Name',
            'last_name' => 'Last Name',
            'role' => 'Role',
        ];
    }

    /**
     * Signs up a user using the provided username, email and password.
     * @return bool whether the user is signed up successfully
     */
    public function signup()
    {
        if (!$this->validate()) {
            return false;
        }

        $user = new User();
        $user->username = $this->username;
        $user->email = $this->email;
        $user->first_name = $this->first_name;
        $user->last_name = $this->last_name;
        $user->role = $this->role;
        $user->status = User::STATUS_ACTIVE;
        $user->created_at = time();
        $user->updated_at = time();
        
        // Generate auth key and hash password
        $user->generateAuthKey();
        $user->setPassword($this->password);

        if ($user->save()) {
            // Assign role to user
            $this->assignRole($user);
            return true;
        }

        return false;
    }

    /**
     * Assign role to user
     * @param User $user
     */
    protected function assignRole($user)
    {
        $auth = Yii::$app->authManager;
        $role = $auth->getRole($user->role);
        if ($role) {
            $auth->assign($role, $user->id);
        }
    }
}
