<?php
class Tg_administrators extends Trongate {

    //NOTE: the default username and password is 'admin' and 'admin'

    private $dashboard_home = 'cars/manage'; //where to go after login

    function login() {
        $data['username'] = input('username');
        $data['form_location'] = str_replace('/login', '/submit_login', current_url());
        $data['view_module'] = 'tg_administrators';
        $data['view_file'] = 'login_form'; 
        $this->template('tg_administrators', $data);
    }

    function submit_login() {
        $submit = input('submit'); 

        if ($submit == 'Submit') {
            $this->validation_helper->set_rules('username', 'username', 'required|callback_login_check');
            $this->validation_helper->set_rules('password', 'password', 'required|min_length[5]');
            $result = $this->validation_helper->run();

            if ($result == true) {
                $this->_log_user_in(input('username'));
            } else {
                $this->login();
            }
        } elseif($submit == 'Cancel') {
            redirect(BASE_URL);
        }
    }

    function submit() {
        $data['token'] = $this->_make_sure_allowed();
        $submit = input('submit');

        if ($submit == 'Submit') {
            $this->validation_helper->set_rules('username', 'username', 'required|min_length[6]|callback_username_check');
            $this->validation_helper->set_rules('password', 'password', 'required|min_length[6]');
            $this->validation_helper->set_rules('repeat_password', 'repeat password', 'matches[password]');

            $result = $this->validation_helper->run();

            if ($result == true) {
                $update_id =  segment(3);
                $data = $this->_get_data_from_post();
                unset($data['repeat_password']);
                $data['password'] = $this->_hash_string($data['password']);

                if (is_numeric($update_id)) {
                    $this->model->update($update_id, $$data);
                    set_flashdata('The record was successfully updated');
                } else {

                    //create a new tg_users record 
                    $this->module('tg_users');
                    $params['code'] = make_rand_str(32);
                    $params['user_level_id'] = 1;
                    $data['tg_user_id'] = $this->model->insert($params, 'tg_users');

                    $this->model->insert($data);
                    set_flashdata('The record was successfully created');
                }

                redirect('tg_administrators/manage');

            } else {
                $this->create();
            }
        } elseif($submit == 'Cancel') {
            redirect('tg_administrators/manage');
        }
    }

    function submit_delete() {
        $this->_make_sure_allowed();
        $update_id =  segment(3);
        $submit = input('submit');

        if (($submit == 'Delete Record Now') && (is_numeric($update_id))) {
            //get the trongate_user_id 
            $user_obj = $this->model->get_where($update_id, 'tg_users');
            $trongate_user_id = $user_obj->tg_user_id;
            $this->model->delete($trongate_user_id, 'tg_users');
            $this->model->delete($update_id, 'tg_users');
            set_flashdata('The record was successfully deleted');
        }

        redirect('tg_administrators/manage');
    }

    function manage() {
        $token = $this->_make_sure_allowed();
        $data['my_admin_id'] = $this->_get_my_id($token);
        $data['rows'] = $this->model->get('username', 'tg_administrators');
        $data['view_module'] = 'tg_administrators';
        $data['view_file'] = 'manage';
        $this->template('tg_administrators', $data);
    }

    function account() {
        $token = $this->_make_sure_allowed();
        $update_id = $this->_get_my_id($token);
        redirect('tg_administrators/create/'.$update_id);
    }

    function create() {
        $token = $this->_make_sure_allowed();
        $update_id = segment(3);
        $submit = input('submit');

        if ((is_numeric($update_id)) && ($submit == '')) {
            $data = $this->_get_data_from_db($update_id);
        } else {
            $data = $this->_get_data_from_post();
        }

        $data['my_admin_id'] = $this->_get_my_id($token);

        if (is_numeric($update_id)) {
            $data['headline'] = 'Update Record';

            if ($data['my_admin_id'] == $update_id) {
                $data['headline'] = str_replace('Record', 'Your Account', $data['headline']);
            }

        } else {
            $data['headline'] = 'Create Record';
        }

        $data['form_location'] = str_replace('/create', '/submit', current_url());
        $data['conf_delete_url'] = str_replace('/create', '/conf_delete', current_url());
        $data['token'] = $this->_make_sure_allowed();
        $data['view_module'] = 'tg_administrators';
        $data['view_file'] = 'create';
        $this->template('tg_administrators', $data);
    }

    function conf_delete() {
        $token = $this->_make_sure_allowed();
        $update_id =  segment(3);

        if (!is_numeric($update_id)) {
            redirect('tg_administrators/manage');
        } else {
            $data['my_admin_id'] = $this->_get_my_id($token);
            $data['form_location'] = str_replace('/conf_delete', '/submit_delete', current_url());
            $data['view_module'] = 'tg_administrators';
            $data['view_file'] = 'conf_delete';
            $this->template('tg_administrators', $data);
        }
    }

    function go_home() {
        redirect($this->dashboard_home);
    }

    function _get_my_id($token) {
        $params['token'] = $token;
        $sql = 'SELECT tg_administrators.id
                FROM tg_users
                INNER JOIN tg_administrators
                       ON tg_users.id = tg_administrators.tg_user_id
                INNER JOIN tg_tokens
                       ON tg_tokens.user_id = tg_users.id 
                WHERE tg_tokens.token = :token 
                ORDER BY tg_tokens.id DESC LIMIT 0,1';
        $result = $this->model->query_bind($sql, $params, 'object');
        if (gettype($result) == 'array') {
            $id = $result[0]->id;
        } else {
            $id = false;
        }
        return $id;
    }

    function _make_sure_allowed() {

        //let's assume that only users with a valid token 
        //who are user_level_id = 1 can view
        $this->module('tg_tokens');
        $token = $this->tg_tokens->_attempt_get_valid_token(1);

        if ($token == false) {

            if (ENV == 'dev') {
                //automatically give token to user when in dev mode

                //generate trongatetoken for 1st tg_administrator record on tbl
                $sql = 'select * from tg_administrators order by id limit 0,1';
                $rows = $this->model->query($sql, 'object');

                if ($rows == false) {
                    redirect(BASE_URL.'tg_administrators/missing_tbl_msg');
                } else {
                    $token_params['user_id'] = $rows[0]->tg_user_id;

                    //start off by clearing all tokens for this user
                    $this->_delete_tokens_for_user($token_params['user_id']);

                    //now generate the new token
                    $token_params['expiry_date'] = 86400+time();
                    $this->module('tg_tokens');
                    $_SESSION['trongatetoken'] = $this->tg_tokens->_generate_token($token_params);
                    return $_SESSION['trongatetoken'];
                }

            } else {
                redirect('tg_administrators/login');
            }

        } else {
            return $token;
        }

    }

    function _get_data_from_db($update_id) {
        $result_obj = $this->model->get_where($update_id);
        if (gettype($result_obj) == 'object') {
            $data = (array) $result_obj;

        } else {
            $data = false;
        }
        return $data;
    }

    function _get_data_from_post() {
        $data['username'] = input('username');
        $data['password'] = input('password');
        $data['repeat_password'] = input('repeat_password');
        return $data;
    }

    function _log_user_in($username) {
        $user_obj = $this->model->get_one_where('username', $username);
        $trongate_user_id = $user_obj->tg_user_id;
        $token_data['user_id'] = $trongate_user_id;

        $remember = input('remember');
        if (($remember === '1') || ($remember === 1)) {
            //set a cookie and remember for 30 days
            $token_data['expiry_date'] = time() + (86400*30);
            $token = $this->tg_tokens->_generate_token($token_data);
            setcookie('trongatetoken', $token, $token_data['expiry_date'], '/');            
        } else {
            //user did not select 'remember me' checkbox
            $this->module('tg_tokens');
            $_SESSION['trongatetoken'] = $this->tg_tokens->_generate_token($token_data);            
        }

        redirect($this->dashboard_home);
    }

    function logout() {
        $this->module('tg_tokens');
        $this->tg_tokens->_destroy();
        redirect('tg_administrators/login');
    }

    function _delete_tokens_for_user($tg_user_id) {
        $params['user_id'] = $tg_user_id;
        $sql = 'delete from tg_tokens where user_id = :user_id';
        $this->model->query_bind($sql, $params);

        //let's delete expired tokens too
        $this->_delete_expired_tokens();
    }

    function _delete_expired_tokens() {
        $params['nowtime'] = time();
        $sql = 'delete from tg_tokens where expiry_date<:nowtime';
        $this->model->query_bind($sql, $params);        
    }

    function _hash_string($str) {
        $hashed_string = password_hash($str, PASSWORD_BCRYPT, array(
            'cost' => 11
        ));
        return $hashed_string;
    }

    function _verify_hash($plain_text_str, $hashed_string) {
        $result = password_verify($plain_text_str, $hashed_string);
        return $result; //TRUE or FALSE
    }

    function username_check($str) {
        //NOTE: You may wish to add other rules of your own here! 
        $update_id =  segment(3);
        $result = $this->model->get_one_where('username', $str, 'tg_administrators');
        $error_msg = 'The username that you submitted is not available.';

        if (gettype($result) == 'object') {
            if (!is_numeric($update_id)) {
                return $error_msg;
            } else {
                $register_id = $result->id;
                if ($update_id !== $register_id) {
                    return $error_msg;
                }
            }
        }

        return true;
    }

    function login_check($submitted_username) {
        $submitted_password = input('password');
        $error_msg = 'You did not enter a correct username and/or or password.';
    
        $result = $this->model->get_one_where('username', $submitted_username, 'tg_administrators');
        if (gettype($result) == 'object') {
            $hashed_password = $result->password;
            $is_password_good = $this->_verify_hash($submitted_password, $hashed_password);
            if ($is_password_good == true) {
                return true;
            }
        }

        return $error_msg;
    }

}