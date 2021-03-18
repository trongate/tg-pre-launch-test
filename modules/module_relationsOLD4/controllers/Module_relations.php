<?php
class Module_relations extends Trongate {

	function _draw_summary_panel($alt_module_name, $token) {
		$calling_module = segment(1);
		$relation_settings = $this->_get_relation_settings($calling_module, $alt_module_name);

		if ($relation_settings == false) {
			echo '<p style="color: red;">Could not find module relation with '.$alt_module_name.' module!</p>';
		} else {

			$first_module = $relation_settings[0];
			$second_module = $relation_settings[1];

			if ($first_module->module_name == $calling_module) {
				$associated_module = $second_module;
			} else {
				$associated_module = $first_module;
			}

			$data['update_id'] = segment(3);
			$data['token'] = $token;
			$data['associated_singular'] = $associated_module->record_name_singular;
			$data['associated_plural'] = $associated_module->record_name_plural;
			$data['relation_name'] = $this->_build_relation_name($relation_settings);
			$this->view('summary_panel', $data);
		}

	}

	function _get_relation_settings($calling_module, $alt_module_name) {
		$settings_file_path = '';
		$relation_names[] = $calling_module.'_and_'.$alt_module_name.'.json';
		$relation_names[] = $alt_module_name.'_and_'.$calling_module.'.json';

		$dirpath = APPPATH.'modules/module_relations/assets/module_relations';

		if (is_dir($dirpath)) {
			$files = scandir($dirpath);
			foreach($files as $filename) {
				if (($filename == $relation_names[0]) || ($filename == $relation_names[1])) {
					$settings_file_path = $dirpath.'/'.$filename;
				}
			}
		}

		if ($settings_file_path == '') {
			return false;
		} else {
			$relation_settings = json_decode(file_get_contents($settings_file_path));
			return $relation_settings;
		}

	}

	function _get_associated_module($calling_module, $relation_settings) {
		if ($relation_settings[0]->module_name == $calling_module) {
			$associated_module = $relation_settings[1];
		} else {
			$associated_module = $relation_settings[0];
		}

		return $associated_module;
	}

	function _build_relation_name($relation_settings) {
		$first_module_name = $relation_settings[0]->module_name;
		$second_module_name = $relation_settings[1]->module_name;
		$relation_name = 'associated_'.$first_module_name.'_and_'.$second_module_name;
		return $relation_name;
	}

	function fetch_associated_records() {
		api_auth();
		$posted_data = $this->_get_posted_data();
		$update_id = $posted_data['updateId'];
		$relation_name = $posted_data['relationName'];
		$calling_module = $posted_data['callingModule'];
		$target_str = str_replace($calling_module, '', $relation_name);
		$target_str = substr($target_str, 11, strlen($target_str));
		$alt_module_name = str_replace('_and_', '', $target_str);
		$relation_settings = $this->_get_relation_settings($calling_module, $alt_module_name);

		$first_module = $relation_settings[0];
		$second_module = $relation_settings[1];

		if ($first_module->module_name == $calling_module) {
			$associated_module = $second_module;
		} else {
			$associated_module = $first_module;
		}		

		$alt_module_table = $associated_module->module_name;
		$identifier_column = $associated_module->identifier_column;
		$foreign_key = $alt_module_table.'_id';

		$this->_make_sure_table_exists($relation_name, $first_module->module_name, $second_module->module_name);

		$sql = 'SELECT '.$relation_name.'.id, '.$alt_module_table.'.'.$identifier_column.' as value  
				FROM '.$relation_name.'  
				INNER JOIN '.$alt_module_table.'  
				ON '.$relation_name.'.'.$foreign_key.' = '.$alt_module_table.'.id 
				WHERE '.$relation_name.'.'.$calling_module.'_id = '.$update_id.' 
				ORDER BY '.$alt_module_table.'.'.$identifier_column;

		$rows = $this->model->query($sql, 'object');
		echo json_encode($rows);
		die();
	}

	function _make_sure_table_exists($relation_name, $first_module_name, $second_module_name) {
		$params['tablename'] = $relation_name;
		$sql = 'SHOW TABLES LIKE :tablename';
		$rows = $this->model->query_bind($sql, $params, 'object');

		if (count($rows) == 0) {

			$queries[] = 'CREATE TABLE `tablename` (
			  `id` int(11) NOT NULL,
			  `first_column` int(11) NOT NULL DEFAULT 0,
			  `second_column` int(11) NOT NULL DEFAULT 0
			) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4';

			$queries[] = 'ALTER TABLE `tablename`
			  ADD PRIMARY KEY (`id`)';

			$queries[] = 'ALTER TABLE `tablename`
			  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT';

			foreach($queries as $query) {
				$sql = str_replace('tablename', $relation_name, $query);
				$sql = str_replace('first_column', $first_module_name.'_id', $sql);
				$sql = str_replace('second_column', $second_module_name.'_id', $sql);
				$this->model->query($sql);
			}

		}

	}

	function _get_posted_data() {
        $post = file_get_contents('php://input');

        if (strlen($post) == 0) {
            $post = '[]';
        }

        $params = json_decode($post, true);
		return $params;
	}

	function disassociate() {
		api_auth();
		$posted_data = $this->_get_posted_data();
		$id = $posted_data['updateId'];
		$target_tbl = $posted_data['relationName'];
		$this->model->delete($id, $target_tbl);
	}

    function fetch_available_options($update_id) {
    	api_auth();
    	$posted_data = $this->_get_posted_data();
		$update_id = $posted_data['updateId'];
		$relation_name = $posted_data['relationName'];
		$calling_module = $posted_data['callingModule'];
		$target_str = str_replace($calling_module, '', $relation_name);
		$target_str = substr($target_str, 11, strlen($target_str));
		$alt_module_name = str_replace('_and_', '', $target_str);
		$relation_settings = $this->_get_relation_settings($calling_module, $alt_module_name);
		$relationship_type = $relation_settings[2]->relationship_type;
    	
        if (!is_numeric($update_id)) {
            http_response_code(422);
            echo 'Non numeric update_id.'; die();
        }

        if ($relationship_type == 'one to one') {
        	$results = $posted_data['results'];

        	if (count($results)>0) {
        		echo '[]'; //no results available since already have associated record
        		die();
        	} else {
        		//fetch all from alt_module
        		$all_alt_records = $this->model->get('id', $alt_module_name);

        		//get the identifier_column from the alt_module
				$associated_module_obj = $this->_get_associated_module($calling_module, $relation_settings);
        		$identifier_column = $associated_module_obj->identifier_column;

        		$available_records = []; //start a new array of available records
        		foreach($all_alt_records as $alt_record) {
        			$row_data['key'] = $alt_record->id;
        			$row_data['value'] = $alt_record->$identifier_column;
        			$available_records[$alt_record->id] = $row_data;
        		}

        		//fetch all associations
        		$all_associations = $this->model->get('id', $relation_name);
        		$target_property = $alt_module_name.'_id';
        		foreach($all_associations as $association) {
        			unset($available_records[$association->$target_property]);
        		}

        		//prepare array for the 'show' page select menu
        		$available_records = array_values($available_records);
        		echo json_encode($available_records);
        		die();
        	}

        }

    }

    function submit() {
    	api_auth();
    	$posted_data = $this->_get_posted_data();
    	$update_id = $posted_data['updateId'];

        if (!is_numeric($update_id)) {
            http_response_code(422);
            echo 'Non numeric update_id.'; die();
        }

    	$relation_name = $posted_data['relationName'];
		$calling_module = $posted_data['callingModule'];
		$target_str = str_replace($calling_module, '', $relation_name);
		$target_str = substr($target_str, 11, strlen($target_str));
		$alt_module_name = str_replace('_and_', '', $target_str);
		$relation_settings = $this->_get_relation_settings($calling_module, $alt_module_name);

		$data[$calling_module.'_id'] = $update_id;
		$data[$alt_module_name.'_id'] = $posted_data['value'];
		$this->model->insert($data, $relation_name);
    }

}