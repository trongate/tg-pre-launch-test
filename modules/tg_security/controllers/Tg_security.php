<?php
class Tg_security extends Trongate {

    function _make_sure_allowed($scenario='admin panel') {
        //returns EITHER (trongate)token OR initialises 'not allowed' procedure

        switch ($scenario) {
            // case 'members area':
            //     $this->module('members');
            //     $token = $this->members->_make_sure_allowed();
            //     break;
            default:
                $this->module('tg_administrators');
                $token = $this->tg_administrators->_make_sure_allowed();
                break;
        }

        return $token;
    }

    function _get_user_id() {
        //attempt fetch tg_user_id (this gets called by the API explorer)
        $tg_user_id = 0;

        if (isset($_COOKIE['trongatetoken'])) {
            $tg_user_id = $this->_is_token_valid($_COOKIE['trongatetoken'], true);

            if ($tg_user_id == 0) {
                //user has an invalid cookie - destroy it
                setcookie('trongatetoken', '', time() - 3600);
            }
        }

        if ((isset($_SESSION['trongatetoken'])) && ($tg_user_id == 0)) {
            $tg_user_id = $this->_is_token_valid($_SESSION['trongatetoken'], true);
        }

        return $tg_user_id;
    }

    function _is_token_valid($token, $return_id=false) {
        $params['token'] = $token;
        $params['nowtime'] = time();
        $sql = 'select * from tg_tokens where token = :token and expiry_date > :nowtime';
        $rows = $this->model->query_bind($sql, $params, 'object');

        if (count($rows)!==1) {

            if ($return_id == true) {
                return 0;
            } else {
                return false;
            }

        } else {

            if ($return_id == true) {
                $user_obj = $rows[0];
                return $user_obj->user_id;
            } else {
                return true;
            }

        }
    }

}