<?php

namespace App\Controller\Component;

use Cake\Controller\Component;
use Cake\Utility\Security;
use Cake\Auth\DefaultPasswordHasher;

class MyAuthenComponent extends Component {

    public function setAuthen($user) {
        $this->request->getSession()->write('Authen.isactive', 'Y');
        $this->request->getSession()->write('Authen.User', $user);
    }

    public function isLogin() {
        $isactive = $this->request->getSession()->read('Authen.isactive');
        if ($isactive == 'Y') {
            return true;
        }
        return false;
    }

    public function getUserId() {
        $user_id = $this->request->getSession()->read('Authen.User.id');
        return $user_id;
    }

    public function hashPassword($password = '') {
        $options = [
            //'salt' => Security::getSalt(),
            //'salt' => mcrypt_create_iv(22, MCRYPT_DEV_URANDOM),
            'cost' => 12,
        ];
        //$password = password_hash($password, PASSWORD_BCRYPT, $options);
        //$password = Security::encrypt($password, Security::getSalt());
        //$password = (new DefaultPasswordHasher)->hash($password);

        return $password;
    }

    public function verifyPassword($password, $hash) {
        
        if (password_verify($password, $hash)) {
            return true;
        }
        
        return false;
    }

}
