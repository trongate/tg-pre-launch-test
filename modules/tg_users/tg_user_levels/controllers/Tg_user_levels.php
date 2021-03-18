<?php
class Tg_user_levels extends Trongate {

    function __construct() {
        parent::__construct();
        $this->parent_module = 'tg_users';
        $this->child_module = 'tg_user_levels';
    }

    function _get_user_level($user_id) {

        $sql = 'SELECT
                    tg_user_levels.level_title
                FROM
                    tg_users
                JOIN tg_user_levels ON tg_users.user_level_id = tg_user_levels.id 
                where tg_users.id = :user_id';

        $data['user_id'] = $user_id;
        $result = $this->model->query_bind($sql, $data, 'array');

        if (isset($result[0])) {
            $user_level = $result[0]['level_title'];
        } else {
            $user_level = '';
        }

        return $user_level;
    }

    function __destruct() {
        $this->parent_module = '';
        $this->child_module = '';        
    }

}