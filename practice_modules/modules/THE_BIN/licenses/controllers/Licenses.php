<?php
class Licenses extends Trongate {

    private $default_limit = 20;
    private $per_page_options = array(10, 20, 50, 100);

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
            $data['headline'] = 'Update License Record';
            $data['cancel_url'] = BASE_URL.'licenses/show/'.$update_id;
        } else {
            $data['headline'] = 'Create New License Record';
            $data['cancel_url'] = BASE_URL.'licenses/manage';
        }

        $data['form_location'] = BASE_URL.'licenses/submit/'.$update_id;
        $data['view_file'] = 'create';
        $this->template('admin', $data);
    }

    function manage() {
        $this->module('tg_security');
        $this->tg_security->_make_sure_allowed();

        if (segment(4) !== '') {
            $data['headline'] = 'Search Results';
            $searchphrase = trim($_GET['searchphrase']);
            $params['license_number'] = '%'.$searchphrase.'%';
            $sql = 'select * from licenses
            WHERE license_number LIKE :license_number
            ORDER BY first_name ';
            $all_rows = $this->model->query_bind($sql, $params, 'object');
        } else {
            $data['headline'] = 'Manage Licenses';
            $all_rows = $this->model->get('id');
        }

        $pagination_data['total_rows'] = count($all_rows);
        $pagination_data['page_num_segment'] = 3;
        $pagination_data['limit'] = $this->_get_limit();
        $pagination_data['pagination_root'] = 'licenses/manage';
        $pagination_data['record_first_name_plural'] = 'licenses';
        $pagination_data['include_showing_statement'] = true;
        $data['pagination_data'] = $pagination_data;

        $data['rows'] = $this->_extract_rows($all_rows);
        $data['selected_per_page'] = $this->_get_selected_per_page();
        $data['per_page_options'] = $this->per_page_options;
        $data['view_module'] = 'licenses';
        $data['view_file'] = 'manage';
        $this->template('admin', $data);
    }

    function show() {
        $this->module('tg_security');
        $token = $this->tg_security->_make_sure_allowed();
        $update_id = segment(3);

        if ((!is_numeric($update_id)) && ($update_id != '')) {
            redirect('licenses/manage');
        }

        $data = $this->_get_data_from_db($update_id);
        $data['token'] = $token;

        if ($data == false) {
            redirect('licenses/manage');
        } else {
            $data['form_location'] = BASE_URL.'licenses/submit/'.$update_id;
            $data['update_id'] = $update_id;
            $data['headline'] = 'License Information';
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

            $this->validation_helper->set_rules('license_number', 'License Number', 'required|min_length[2]|max_length[255]');

            $result = $this->validation_helper->run();

            if ($result == true) {

                $update_id = segment(3);
                $data = $this->_get_data_from_post();

                if (is_numeric($update_id)) {
                    //update an existing record
                    $this->model->update($update_id, $data, 'licenses');
                    $flash_msg = 'The record was successfully updated';
                } else {
                    //insert the new record
                    $update_id = $this->model->insert($data, 'licenses');
                    $flash_msg = 'The record was successfully created';
                }

                set_flashdata($flash_msg);
                redirect('licenses/show/'.$update_id);

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
            $params['module'] = 'licenses';
            $this->model->query_bind($sql, $params);

            //delete the record
            $this->model->delete($params['update_id'], 'licenses');

            //set the flashdata
            $flash_msg = 'The record was successfully deleted';
            set_flashdata($flash_msg);

            //redirect to the manage page
            redirect('licenses/manage');
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
        redirect('licenses/manage');
    }

    function _get_data_from_db($update_id) {
        $licenses = $this->model->get_where($update_id, 'licenses');

        if ($licenses == false) {
            $this->template('error_404');
            die();
        } else {
            $data['license_number'] = $licenses->license_number;
            return $data;
        }
    }

    function _get_data_from_post() {
        $data['license_number'] = input('license_number', true);
        return $data;
    }

}