<?php
class Messages extends Trongate {

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
            $data['headline'] = 'Update Messages Record';
            $data['cancel_url'] = BASE_URL.'messages/show/'.$update_id;
        } else {
            $data['headline'] = 'Create New Messages Record';
            $data['cancel_url'] = BASE_URL.'messages/manage';
        }

        $data['form_location'] = BASE_URL.'messages/submit/'.$update_id;
        $data['view_file'] = 'create';
        $this->template('admin', $data);
    }

    function manage() {
        $this->module('tg_security');
        $this->tg_security->_make_sure_allowed();

        if (segment(4) !== '') {
            $data['headline'] = 'Search Results';
            $searchphrase = trim($_GET['searchphrase']);
            $params['message_subject'] = '%'.$searchphrase.'%';
            $params['code'] = '%'.$searchphrase.'%';
            $sql = 'select * from messages
            WHERE message_subject LIKE :message_subject
            OR code LIKE :code
            ORDER BY date_created_desc ';
            $all_rows = $this->model->query_bind($sql, $params, 'object');
        } else {
            $data['headline'] = 'Manage Messages';
            $all_rows = $this->model->get('id');
        }

        $pagination_data['total_rows'] = count($all_rows);
        $pagination_data['page_num_segment'] = 3;
        $pagination_data['limit'] = $this->_get_limit();
        $pagination_data['pagination_root'] = 'messages/manage';
        $pagination_data['record_name_plural'] = 'messages';
        $pagination_data['include_showing_statement'] = true;
        $data['pagination_data'] = $pagination_data;

        $data['rows'] = $this->_extract_rows($all_rows);
        $data['selected_per_page'] = $this->_get_selected_per_page();
        $data['per_page_options'] = $this->per_page_options;
        $data['view_module'] = 'messages';
        $data['view_file'] = 'manage';
        $this->template('admin', $data);
    }

    function show() {
        $this->module('tg_security');
        $token = $this->tg_security->_make_sure_allowed();
        $update_id = segment(3);

        if ((!is_numeric($update_id)) && ($update_id != '')) {
            redirect('messages/manage');
        }

        $data = $this->_get_data_from_db($update_id);
        $data['token'] = $token;

        if ($data == false) {
            redirect('messages/manage');
        } else {
            $data['form_location'] = BASE_URL.'messages/submit/'.$update_id;
            $data['update_id'] = $update_id;
            $data['headline'] = 'Messages Information';
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

            $this->validation_helper->set_rules('date_created', 'Date Created', 'required|max_length[11]|numeric|greater_than[0]|integer');
            $this->validation_helper->set_rules('sent_from', 'Sent From', 'required|max_length[11]|numeric|greater_than[0]|integer');
            $this->validation_helper->set_rules('sent_to', 'Sent To', 'required|max_length[11]|numeric|greater_than[0]|integer');
            $this->validation_helper->set_rules('message_subject', 'Message Subject', 'required|min_length[2]|max_length[255]');
            $this->validation_helper->set_rules('message_body', 'Message Body', 'required|min_length[2]');
            $this->validation_helper->set_rules('opened', 'Opened', '');
            $this->validation_helper->set_rules('from_admin', 'From Admin', '');

            $result = $this->validation_helper->run();

            if ($result == true) {

                $update_id = segment(3);
                $data = $this->_get_data_from_post();

                if (is_numeric($update_id)) {
                    //update an existing record
                    $this->model->update($update_id, $data, 'messages');
                    $flash_msg = 'The record was successfully updated';
                } else {
                    //insert the new record
                    $update_id = $this->model->insert($data, 'messages');
                    $flash_msg = 'The record was successfully created';
                }

                set_flashdata($flash_msg);
                redirect('messages/show/'.$update_id);

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
            $params['module'] = 'messages';
            $this->model->query_bind($sql, $params);

            //delete the record
            $this->model->delete($params['update_id'], 'messages');

            //set the flashdata
            $flash_msg = 'The record was successfully deleted';
            set_flashdata($flash_msg);

            //redirect to the manage page
            redirect('messages/manage');
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
        redirect('messages/manage');
    }

    function _get_data_from_db($update_id) {
        $messages = $this->model->get_where($update_id, 'messages');

        if ($messages == false) {
            $this->template('error_404');
            die();
        } else {
            $data['date_created'] = $messages->date_created;
            $data['sent_from'] = $messages->sent_from;
            $data['sent_to'] = $messages->sent_to;
            $data['message_subject'] = $messages->message_subject;
            $data['message_body'] = $messages->message_body;
            $data['opened'] = $messages->opened;
            $data['code'] = $messages->code;
            $data['from_admin'] = $messages->from_admin;
            return $data;
        }
    }

    function _get_data_from_post() {
        $data['date_created'] = input('date_created', true);
        $data['sent_from'] = input('sent_from', true);
        $data['sent_to'] = input('sent_to', true);
        $data['message_subject'] = input('message_subject', true);
        $data['message_body'] = input('message_body', true);
        $data['opened'] = input('opened', true);
        $data['from_admin'] = input('from_admin', true);
        return $data;
    }

}