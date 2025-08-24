<?php

use yii\db\Migration;

class m250824_193128_init_rbac extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $auth = Yii::$app->authManager;

        // Create permissions
        $viewBooks = $auth->createPermission('viewBooks');
        $viewBooks->description = 'View all books';
        $auth->add($viewBooks);

        $createBook = $auth->createPermission('createBook');
        $createBook->description = 'Create a new book';
        $auth->add($createBook);

        $updateOwnBook = $auth->createPermission('updateOwnBook');
        $updateOwnBook->description = 'Update own book';
        $auth->add($updateOwnBook);

        $updateAnyBook = $auth->createPermission('updateAnyBook');
        $updateAnyBook->description = 'Update any book';
        $auth->add($updateAnyBook);

        $deleteOwnBook = $auth->createPermission('deleteOwnBook');
        $deleteOwnBook->description = 'Delete own book';
        $auth->add($deleteOwnBook);

        $deleteAnyBook = $auth->createPermission('deleteAnyBook');
        $deleteAnyBook->description = 'Delete any book';
        $auth->add($deleteAnyBook);

        $manageUsers = $auth->createPermission('manageUsers');
        $manageUsers->description = 'Manage users';
        $auth->add($manageUsers);

        // Create roles
        $reader = $auth->createRole('reader');
        $reader->description = 'Reader - can only view books';
        $auth->add($reader);

        $author = $auth->createRole('author');
        $author->description = 'Author - can view all books and edit own books';
        $auth->add($author);

        $admin = $auth->createRole('admin');
        $admin->description = 'Admin - can do everything';
        $auth->add($admin);

        // Assign permissions to roles
        // Reader permissions
        $auth->addChild($reader, $viewBooks);

        // Author permissions
        $auth->addChild($author, $reader); // Author inherits reader permissions
        $auth->addChild($author, $createBook);
        $auth->addChild($author, $updateOwnBook);
        $auth->addChild($author, $deleteOwnBook);

        // Admin permissions
        $auth->addChild($admin, $author); // Admin inherits author permissions
        $auth->addChild($admin, $updateAnyBook);
        $auth->addChild($admin, $deleteAnyBook);
        $auth->addChild($admin, $manageUsers);

        // Create default admin user
        $this->createDefaultAdmin();
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $auth = Yii::$app->authManager;
        
        // Remove all roles and permissions
        $auth->removeAll();
        
        echo "m250824_193128_init_rbac cannot be reverted.\n";
        return false;
    }

    /**
     * Create default admin user
     */
    private function createDefaultAdmin()
    {
        $user = new \app\models\Users();
        $user->username = 'admin';
        $user->email = 'admin@bookclub.com';
        $user->password_hash = Yii::$app->security->generatePasswordHash('admin123');
        $user->auth_key = Yii::$app->security->generateRandomString();
        $user->first_name = 'Admin';
        $user->last_name = 'User';
        $user->role = 'admin';
        $user->status = 10;
        $user->created_at = time();
        $user->updated_at = time();
        
        if ($user->save()) {
            // Assign admin role to the user
            $auth = Yii::$app->authManager;
            $adminRole = $auth->getRole('admin');
            $auth->assign($adminRole, $user->id);
        }
    }
}
