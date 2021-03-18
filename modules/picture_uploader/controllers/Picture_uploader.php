<?php
class Picture_uploader extends Trongate {

    function _get_folder_path($dir, $update_id) {
        $folder_path = $dir.'/'.$update_id;
        return $folder_path;
    }

    function _draw_picture_summary_panel($calling_module, $update_id) {

        $this->module($calling_module);
        $picture_settings = $this->$calling_module->_init_picture_settings();

        $picture_settings['destination'] = $this->_get_folder_path($picture_settings['destination'], $update_id);
        $picture_settings['thumbnailDir'] = $this->_get_folder_path($picture_settings['thumbnailDir'], $update_id);

        //generate upload folders, if required
        $this->_make_sure_got_destination_folders($update_id, $picture_settings);

        //attempt to get the current picture
        $item = $this->model->get_where($update_id, $calling_module);
        $column_name = $picture_settings['targetColumnName'];

        $data['update_id'] = $update_id;
        $data['calling_module'] = $calling_module;

        if ($item->$column_name !== '') {
            $data['picture_name'] = $item->$column_name;
            $data['picture_path'] = BASE_URL.$picture_settings['destination'].'/'.$item->$column_name;
            $data['draw_picture_uploader'] = false;
            $data['form_location'] = BASE_URL.'picture_uploader/ditch/'.$update_id;
            $data['targetColumnName'] = $picture_settings['targetColumnName'];
            $data['destination'] = $picture_settings['destination'];
            $data['thumbnailDir'] = $picture_settings['thumbnailDir'];
        } else {
            $data['form_location'] = BASE_URL.'picture_uploader/submit_upload_picture/'.$update_id;
            $data['draw_picture_uploader'] = true;
            $data = array_merge($data, $picture_settings);
        }

        $this->view('picture_summary_panel', $data);
    }

    function ditch($update_id) {

        if (!is_numeric($update_id)) {
            die();
        }

        $this->module('tg_security');
        $this->tg_security->_make_sure_allowed();

        $picture_name = input('picture_name');
        $calling_module = input('calling_module');
        $targetColumnName = input('targetColumnName');
        $destination = input('destination');
        $thumbnailDir = input('thumbnailDir');
        
        $picture_path = APPPATH.'public/'.$destination.'/'.$picture_name;

        if (file_exists($picture_path)) {
            unlink($picture_path);
        } else {
            echo "could not find $picture_path"; die();
        }

        $thumbnail_path = APPPATH.'public/'.$thumbnailDir.'/'.$picture_name;

        if (file_exists($thumbnail_path)) {
            unlink($thumbnail_path);
        }

        $sql = 'update '.$calling_module.' set '.$targetColumnName.' = \'\' where id = '.$update_id;
        $this->model->query($sql);
        
        $flash_msg = 'The picture was successfully deleted';
        set_flashdata($flash_msg);
        redirect($_SERVER['HTTP_REFERER']);
    }

    function submit_upload_picture($update_id) {

        $this->module('tg_security');
        $this->tg_security->_make_sure_allowed();

        $submit = input('submit', true);

        if ($_FILES['picture']['name'] == '') {
            redirect($_SERVER['HTTP_REFERER']);
        }

        if ($submit == 'Upload') {

            $max_file_size = input('maxFileSize', true);
            $max_width = input('maxWidth', true);
            $max_height = input('maxHeight', true);

            $validation_str = 'allowed_types[gif,jpg,jpeg,png]|max_size['.$max_file_size.']|max_width['.$max_width.']|max_height['.$max_height.']';
            $this->validation_helper->set_rules('picture', 'item picture', $validation_str);

            $result = $this->validation_helper->run();

            if ($result == true) {

                $config['destination'] = input('destination', true);

                $config['max_width'] = input('resizedMaxWidth', true);
                $config['max_height'] = input('resizedMaxHeight', true);

                $thumbnail_dir = trim(input('thumbnailDir', true));
                $thumbnail_dir = str_replace(' ', '', $thumbnail_dir);
                if ($thumbnail_dir !== '') {
                    $config['thumbnail_dir'] = input('thumbnailDir', true);
                    $config['thumbnail_max_width'] = input('thumbnailMaxWidth', true);
                    $config['thumbnail_max_height'] = input('thumbnailMaxHeight', true);
                }

                //upload the picture
                $this->upload_picture($config);

                //update the database
                $table = input('targetModule', true);
                $column = input('targetColumnName', true);
                $data[] = $_FILES['picture']['name'];
                $data[] = input('update_id', true);

                $sql = 'update '.$table.' set '.$column.' = ? where id = ?';
                $this->model->query_bind($sql, $data);

                $flash_msg = 'The picture was successfully uploaded';
                set_flashdata($flash_msg);
                redirect($_SERVER['HTTP_REFERER']);

            } else {
                redirect($_SERVER['HTTP_REFERER']);
            }
        }

    }

    function _make_sure_got_destination_folders($update_id, $picture_settings) {

        $destination = $picture_settings['destination'];
        $target_dir = APPPATH.'public/'.$destination;

        if (!file_exists($target_dir)) {
            //generate the image folder
            mkdir($target_dir, 0777, true);
        }

        //attempt to create thumbnail directory
        $thumbnail_dir = trim($picture_settings['thumbnailDir']);

        if (strlen($thumbnail_dir)>0) {

            $target_dir = APPPATH.'public/'.$thumbnail_dir;

            if (!file_exists($target_dir)) {
                //generate the image folder
                mkdir($target_dir, 0777, true);
            }
            
        }
    }

}