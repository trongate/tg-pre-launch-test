<?php
class Members extends Trongate {

    private $default_limit = 20;
    private $per_page_options = array(10, 20, 50, 100);

    function _init_picture_uploader_multi_settings() {
        $data['targetModule'] = 'members';
        $data['destination'] = 'members_pictures';
        $data['max_file_size'] = 1200;
        $data['max_width'] = 2500;
        $data['max_height'] = 1400;
        return $data;
    }

    function create() {
        $this->module('tg_security');
        $this->tg_security->_make_sure_allowed();

        $update_id = segment(3);
        $submit = input('submit');

        if (($submit == '') && (is_numeric($update_id))) {
            $data = $this->_get_data_from_db($update_id);
        } else {
            $data = $this->_get_data_from_post();
        }

        if (is_numeric($update_id)) {
            $data['headline'] = 'Update Member Record';
            $data['cancel_url'] = BASE_URL.'members/show/'.$update_id;
        } else {
            $data['headline'] = 'Create New Member Record';
            $data['cancel_url'] = BASE_URL.'members/manage';
        }

        $data['form_location'] = BASE_URL.'members/submit/'.$update_id;
        $data['view_file'] = 'create';
        $this->template('admin', $data);
    }

    function manage() {
        $this->module('tg_security');
        $this->tg_security->_make_sure_allowed();

        if (segment(4) !== '') {
            $data['headline'] = 'Search Results';
            $searchphrase = trim($_GET['searchphrase']);
            $params['first_name'] = '%'.$searchphrase.'%';
            $params['last_name'] = '%'.$searchphrase.'%';
            $sql = 'select * from members
            WHERE first_name LIKE :first_name
            OR last_name LIKE :last_name
            ORDER BY first_name ';
            $all_rows = $this->model->query_bind($sql, $params, 'object');
        } else {
            $data['headline'] = 'Manage Members';
            $all_rows = $this->model->get('id');
        }

        $pagination_data['total_rows'] = count($all_rows);
        $pagination_data['page_num_segment'] = 3;
        $pagination_data['limit'] = $this->_get_limit();
        $pagination_data['pagination_root'] = 'members/manage';
        $pagination_data['record_first_name_plural'] = 'members';
        $pagination_data['include_showing_statement'] = true;
        $data['pagination_data'] = $pagination_data;

        $data['rows'] = $this->_extract_rows($all_rows);
        $data['selected_per_page'] = $this->_get_selected_per_page();
        $data['per_page_options'] = $this->per_page_options;
        $data['view_module'] = 'members';
        $data['view_file'] = 'manage';
        $this->template('admin', $data);
    }

    function show() {
        $this->module('tg_security');
        $token = $this->tg_security->_make_sure_allowed();
        $update_id = segment(3);

        if ((!is_numeric($update_id)) && ($update_id != '')) {
            redirect('members/manage');
        }

        $data = $this->_get_data_from_db($update_id);
        $data['token'] = $token;

        if ($data == false) {
            redirect('members/manage');
        } else {
            $data['form_location'] = BASE_URL.'members/submit/'.$update_id;
            $data['update_id'] = $update_id;
            $data['headline'] = 'Member Information';
            $data['picture_uploader_multi_settings'] = $this->_init_picture_uploader_multi_settings();
            $data['view_file'] = 'show';
            $this->template('admin', $data);
        }
    }

    function _extract_rows($all_rows) {
        $rows = [];
        $start_index = $this->_get_offset();
        $limit = $this->_get_limit();
        $end_index = $start_index + $limit;

        $count = -1;
        foreach($all_rows as $row) {
            $count++;
            if (($count >= $start_index) && ($count < $end_index)) {
                $rows[] = $row;
            }
        }

        return $rows;
    }

    function submit() {
        $this->module('tg_security');
        $this->tg_security->_make_sure_allowed();

        $submit = input('submit', true);

        if ($submit == 'Submit') {

            $this->validation_helper->set_rules('first_name', 'First Name', 'required|min_length[2]|max_length[255]');
            $this->validation_helper->set_rules('last_name', 'Last Name', 'required|min_length[2]|max_length[255]');

            $result = $this->validation_helper->run();

            if ($result == true) {

                $update_id = segment(3);
                $data = $this->_get_data_from_post();

                if (is_numeric($update_id)) {
                    //update an existing record
                    $this->model->update($update_id, $data, 'members');
                    $flash_msg = 'The record was successfully updated';
                } else {
                    //insert the new record
                    $update_id = $this->model->insert($data, 'members');
                    $flash_msg = 'The record was successfully created';
                }

                set_flashdata($flash_msg);
                redirect('members/show/'.$update_id);

            } else {
                //form submission error
                $this->create();
            }

        }

    }

    function submit_delete() {
        $this->module('tg_security');
        $this->tg_security->_make_sure_allowed();

        $submit = input('submit');
        $params['update_id'] = segment(3);

        if (($submit == 'Submit') && (is_numeric($params['update_id']))) {
            //delete all of the comments associated with this record
            $sql = 'delete from tg_comments where target_table = :module and update_id = :update_id';
            $params['module'] = 'members';
            $this->model->query_bind($sql, $params);

            //delete the record
            $this->model->delete($params['update_id'], 'members');

            //set the flashdata
            $flash_msg = 'The record was successfully deleted';
            set_flashdata($flash_msg);

            //redirect to the manage page
            redirect('members/manage');
        }
    }

    function _get_limit() {
        if (isset($_SESSION['selected_per_page'])) {
            $limit = $this->per_page_options[$_SESSION['selected_per_page']];
        } else {
            $limit = $this->default_limit;
        }

        return $limit;
    }

    function _get_offset() {
        $page_num = segment(3);

        if (!is_numeric($page_num)) {
            $page_num = 0;
        }

        if ($page_num > 1) {
            $offset = ($page_num - 1) * $this->_get_limit();
        } else {
            $offset = 0;
        }

        return $offset;
    }

    function _get_selected_per_page() {
        if (!isset($_SESSION['selected_per_page'])) {
            $selected_per_page = $this->per_page_options[1];
        } else {
            $selected_per_page = $_SESSION['selected_per_page'];
        }

        return $selected_per_page;
    }

    function set_per_page($selected_index) {
        $this->module('tg_security');
        $this->tg_security->_make_sure_allowed();

        if (!is_numeric($selected_index)) {
            $selected_index = $this->per_page_options[1];
        }

        $_SESSION['selected_per_page'] = $selected_index;
        redirect('members/manage');
    }

    function _get_data_from_db($update_id) {
        $members = $this->model->get_where($update_id, 'members');

        if ($members == false) {
            $this->template('error_404');
            die();
        } else {
            $data['first_name'] = $members->first_name;
            $data['last_name'] = $members->last_name;
            return $data;
        }
    }

    function _get_data_from_post() {
        $data['first_name'] = input('first_name', true);
        $data['last_name'] = input('last_name', true);
        return $data;
    }

}