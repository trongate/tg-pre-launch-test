<?php
class Associated_drivers_and_licenses extends Trongate {

    function _get_modules_data() {
    	$modules_info = $this->view('modules_data', [], true);
    	$modules_data = json_decode($modules_info);
    	return $modules_data;
    }

    function _draw_association_info($token) {
    	$calling_module = segment(1);
    	$modules_data = $this->_get_modules_data();
    	$associated_module_obj = $this->_get_associated_module($calling_module, $modules_data);
        $data['associated_module'] = $associated_module_obj->module_name;
    	$data['associated_singular'] = $this->_get_associated_record_name($calling_module, $modules_data, true);
    	$data['associated_plural'] = $this->_get_associated_record_name($calling_module, $modules_data, false);
    	$data['calling_module'] = $calling_module;
    	$data['table_name'] = $this->_get_table_name($modules_data);
    	$data['update_id'] = segment(3);
    	$data['token'] = $token;
    	$data['fetch_url'] = BASE_URL.$data['table_name'].'/fetch/'.$data['update_id'].'/'.$calling_module;
    	$data['fetch_available_options_url'] = BASE_URL.$data['table_name'].'/fetch_available_options/'.$data['update_id'].'/'.$calling_module;
    	$data['create_association_url'] = BASE_URL.'api/create/'.$data['table_name'];
    	$data['rand_str'] = make_rand_str(12);
    	$this->view('association_info', $data);
    }

    function fetch($update_id) {
        //fetch associated records from the related module
        api_auth();

        if (!is_numeric($update_id)) {
            http_response_code(422);
            echo 'Non numeric update_id.'; die();
        }

        $calling_module = segment(4);
        $modules_data = $this->_get_modules_data();
        $associated_module_obj = $this->_get_associated_module($calling_module, $modules_data);
        $associated_module = $associated_module_obj->module_name;
        $identifier_column = $associated_module_obj->identifier_column;

        //fetch all from associated module
        $records = $this->model->get($identifier_column, $associated_module);
        $available_records = [];
        foreach($records as $record) {
        	$available_records[$record->id] = $record->$identifier_column;
        }

        //fetch all from $this table
        $table_name = $this->_get_table_name($modules_data);
        $rows = $this->model->get('id', $table_name);

        $results = [];
        $target_property = $associated_module.'_id';

        foreach($rows as $row) {
        	if (isset($available_records[$row->$target_property])) {
        		$row_data['recordId'] = $row->id;
        		$row_data['id'] = $row->$target_property;
        		$row_data['identifierColumn'] = $available_records[$row->$target_property];
        		$results[] = $row_data;
        	}
        	
        }

    	echo json_encode($results); die();

    }

    function fetch_available_options($update_id) {
    	api_auth();

        if (!is_numeric($update_id)) {
            http_response_code(422);
            echo 'Non numeric update_id.'; die();
        }

    	$post = file_get_contents('php://input');
        $params = json_decode($post, true);

        if (count($params)>0) {
            echo '[]'; die(); //since it's a one to one
        }

        //fetch all of the records from the other module
        $calling_module = segment(4);
        $modules_data = $this->_get_modules_data();
        $associated_module_obj = $this->_get_associated_module($calling_module, $modules_data);
        $associated_module = $associated_module_obj->module_name;
        $identifier_column = $associated_module_obj->identifier_column;

        //fetch all from associated module
        $records = $this->model->get($identifier_column, $associated_module);
        $results = [];

        $selected_options = [];
        foreach($params as $selected_option) {
        	$selected_options[$selected_option['id']] = '';
        }

        $available_records = [];
        foreach($records as $record) {

        	if (!isset($selected_options[$record->id])) {
	        	$row_data['key'] = $record->id;
	        	$row_data['value'] = $record->$identifier_column;
	        	$available_records[] = $row_data;
        	}

        }

        echo json_encode($available_records);
 
    }

    function _get_associated_module($calling_module, $modules_data) {
    	foreach($modules_data as $module_data) {
    		if ($module_data->module_name !== $calling_module) {
    			return $module_data;
    		}
    	}
    }

    function _get_associated_record_name($calling_module, $modules_data, $singular) {
    	foreach($modules_data as $module_data) {
    		if ($module_data->module_name !== $calling_module) {
    			if ($singular == true) {
    				$value = $module_data->record_name_singular;
    			} else {
    				$value = $module_data->record_name_plural;
    			}
    		}
    	}
    	return $value;
    }

    function _get_table_name($module_data) {
    	$first_module = $module_data[0]->module_name;
    	$second_module = $module_data[1]->module_name;
    	$table_name = 'associated_'.$first_module.'_and_'.$second_module;
    	return $table_name;
    }

}