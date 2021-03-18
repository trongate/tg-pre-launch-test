<?php
class Module_relations extends Trongate {

	function _draw_summary_panel($alt_module_name, $token) {
		$calling_module_name = segment(1);
		$relation_settings = $this->_get_relation_settings($calling_module_name, $alt_module_name);

		if ($relation_settings == false) {
			echo '<p style="color: red;">Could not find module relation with '.$alt_module_name.' module!</p>';
		} else {

			$first_module = $relation_settings[0];
			$second_module = $relation_settings[1];

			if ($first_module->module_name == $calling_module_name) {
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

	function _get_relation_settings($calling_module_name, $alt_module_name) {
		$settings_file_path = '';
		$relation_names[] = $calling_module_name.'_and_'.$alt_module_name.'.json';
		$relation_names[] = $alt_module_name.'_and_'.$calling_module_name.'.json';

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

	function _get_associated_module($calling_module_name, $relation_settings) {
		if ($relation_settings[0]->module_name == $calling_module_name) {
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
		$calling_module_name = $posted_data['callingModule'];
		$target_str = str_replace($calling_module_name, '', $relation_name);
		$target_str = substr($target_str, 11, strlen($target_str));
		$alt_module_name = str_replace('_and_', '', $target_str);
		$relation_settings = $this->_get_relation_settings($calling_module_name, $alt_module_name);

		$relationship_type = $relation_settings[2]->relationship_type;

		$first_module = $relation_settings[0];
		$second_module = $relation_settings[1];

		if ($relationship_type == 'one to many') {
			$parent_module_name = $relation_settings[0]->module_name;
			$child_module = $relation_settings[1];
			$identifier_column = $child_module->identifier_column;
			$foreign_key = $parent_module_name.'_id';
			$params['update_id'] = $update_id;
			$sql = 'select id, '.$identifier_column.' as value  from '.$child_module->module_name.' where '.$foreign_key.'=:update_id';
			$rows = $this->model->query_bind($sql, $params, 'object');
		} else {
			if ($first_module->module_name == $calling_module_name) {
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
					WHERE '.$relation_name.'.'.$calling_module_name.'_id = '.$update_id.' 
					ORDER BY '.$alt_module_table.'.'.$identifier_column;

			$rows = $this->model->query($sql, 'object');
		}

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

		//get the table names
		$str = str_replace('associated_', '', $target_tbl);
		$tables = explode('_and_', $str);

		//get the relationship type
		$relation_settings = $this->_get_relation_settings($tables[0], $tables[1]);
		$relationship_type = $relation_settings[2]->relationship_type;

		if ($relationship_type == 'one to many') {
			$child_module_table = $relation_settings[1]->module_name;
			$foreign_key = $relation_settings[0]->module_name.'_id';
			$params['update_id'] = $id;
			$sql = 'UPDATE '.$child_module_table.' SET '.$foreign_key.'=0 WHERE id=:update_id';
			$this->model->query_bind($sql, $params, 'object');
		} else {
			$this->model->delete($id, $target_tbl);
		}

	}

    function fetch_available_options($update_id) {
    	api_auth();
     	$posted_data = $this->_get_posted_data();

        if (!is_numeric($posted_data['updateId'])) {
            http_response_code(422);
            echo 'Non numeric update_id.'; die();
        }

        extract($posted_data);
		$target_str = str_replace($callingModule, '', $relationName);
		$target_str = substr($target_str, 11, strlen($target_str));
		$posted_data['alt_module_name'] = str_replace('_and_', '', $target_str);
		$posted_data['relation_settings'] = $this->_get_relation_settings($callingModule, $posted_data['alt_module_name']);
		$relationship_type = $posted_data['relation_settings'][2]->relationship_type;

        switch ($relationship_type) {
        	case 'one to one':
        		$this->_fetch_available_one_to_one($posted_data);
        		break;
        	case 'many to many':
        		$this->_fetch_available_many_to_many($posted_data);
        		break;
        	
        	default:
        		$this->_fetch_available_one_to_many($posted_data);
        		break;
        }

        echo 'You should not be here'; die();

        if ($relationship_type == 'one to one') {


        }



    }

    function _fetch_available_one_to_one($posted_data) {
    	extract($posted_data);

    	if (count($results)>0) {
    		echo '[]'; //no results available since already have associated record
    		die();
    	} else {
    		//fetch all from alt_module
    		$all_alt_records = $this->model->get('id', $alt_module_name);

    		//get the identifier_column from the alt_module
			$associated_module_obj = $this->_get_associated_module($callingModule, $relation_settings);
    		$identifier_column = $associated_module_obj->identifier_column;

    		$available_records = []; //start a new array of available records
    		foreach($all_alt_records as $alt_record) {
    			$row_data['key'] = $alt_record->id;
    			$row_data['value'] = $alt_record->$identifier_column;
    			$available_records[$alt_record->id] = $row_data;
    		}

    		//fetch all associations
    		$all_associations = $this->model->get('id', $relationName);
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

    function _fetch_available_many_to_many($posted_data) {
    	extract($posted_data);
		//fetch all from alt_module
		$all_alt_records = $this->model->get('id', $alt_module_name);

		//get the identifier_column from the alt_module
		$associated_module_obj = $this->_get_associated_module($callingModule, $relation_settings);
		$identifier_column = $associated_module_obj->identifier_column;

		$available_records = []; //start a new array of available records
		foreach($all_alt_records as $alt_record) {
			$row_data['key'] = $alt_record->id;
			$row_data['value'] = $alt_record->$identifier_column;
			$available_records[$alt_record->id] = $row_data;
		}

		//fetch all associations
		$all_associations = $this->model->get('id', $relationName);
		$target_property = $callingModule.'_id';
		$alt_module_fk = $alt_module_name.'_id';

		foreach($all_associations as $association) {
			if ($updateId == $association->$target_property) {
				unset($available_records[$association->$alt_module_fk]);
			}			
		}

		//prepare array for the 'show' page select menu
		$available_records = array_values($available_records);
		echo json_encode($available_records);
		die();
    }

    function _fetch_available_one_to_many($posted_data) {

		/*

			SAMPLE DATA...

				{
				  "updateId": "1",
				  "relationName": "associated_drivers_and_licenses",
				  "results": [],
				  "callingModule": "drivers",
				  "alt_module_name": "licenses",
				  "relation_settings": [
				    {
				      "module_name": "drivers",
				      "record_name_singular": "driver",
				      "record_name_plural": "drivers",
				      "identifier_column": "last_name"
				    },
				    {
				      "module_name": "licenses",
				      "record_name_singular": "license",
				      "record_name_plural": "licenses",
				      "identifier_column": "license_number"
				    },
				    {
				      "relationship_type": "one to many"
				    }
				  ]
				}

		*/

		extract($posted_data);

		//is the calling module the parent or child?
		$calling_module_name = $callingModule;
		$parent_module_name = $relation_settings[0]->module_name;
		$parent_module = $relation_settings[0];
		$child_module = $relation_settings[1];
		$child_module->foreign_key = $parent_module_name.'_id';

		if ($parent_module_name == $calling_module_name) {			
			$available_records = $this->_fetch_available_for_parent($child_module);
		} else {
			$available_records = $this->_fetch_available_for_child($parent_module);
		}

		http_response_code(200);
		echo json_encode($available_records);
		die();
    }

    function _fetch_available_for_parent($child_module) {
		$foreign_key = $child_module->foreign_key;
		$identifier_column = $child_module->identifier_column;
		$sql = 'SELECT * from '.$child_module->module_name.' WHERE '.$foreign_key.'=0 ';
		$sql.= 'ORDER BY '.$identifier_column;
		$results = $this->model->query($sql, 'object');

		$available_records = [];
		foreach($results as $result) {
			$row_data['key'] = $result->id;
			$row_data['value'] = $result->$identifier_column;
			$available_records[] = $row_data;
		}

		return $available_records;
    }

    function _fetch_available_for_child($parent_module) {
		$identifier_column = $parent_module->identifier_column;
		$sql = 'SELECT drivers.id, drivers.last_name   from drivers   
				LEFT JOIN licenses ON drivers.id = licenses.drivers_id 
				UNION
				SELECT drivers.id, drivers.last_name FROM drivers
				RIGHT JOIN licenses ON drivers.id = licenses.drivers_id  
				WHERE drivers.last_name !=\'\'
				ORDER BY `last_name` ASC';
		$results = $this->model->query($sql, 'object');

		$available_records = [];
		foreach($results as $result) {
			$row_data['key'] = $result->id;
			$row_data['value'] = $result->$identifier_column;
			$available_records[] = $row_data;
		}

		return $available_records;
    }

    function _fetch_options($selected_key, $calling_module_name, $alt_module_name) {

        if (($selected_key == '') || ($selected_key == 0) || ($selected_key == '0')) {
            $options[''] = 'Select...';
        }

		$relation_settings = $this->_get_relation_settings($calling_module_name, $alt_module_name);

		//get the alt module idenfifier column
		if ($relation_settings[0]->module_name == $alt_module_name) {
			$identifier_column = $relation_settings[0]->identifier_column;
		} else {
			$identifier_column = $relation_settings[1]->identifier_column;
		}

		$sql = 'select id, '.$identifier_column.' from '.$alt_module_name.' order by '.$identifier_column;
        $rows = $this->model->query($sql, 'object');

        foreach ($rows as $row) {
            $row_desc = $row->$identifier_column;
            $options[$row->id] = $row_desc;
        }

        if ($selected_key>0) {
            $row_label = $options[$selected_key];
            $options[0] = strtoupper('*** Disassociate with '.$row_label.' ***');
        }

        return $options;
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
		$calling_module_name = $posted_data['callingModule'];
		$target_str = str_replace($calling_module_name, '', $relation_name);
		$target_str = substr($target_str, 11, strlen($target_str));
		$alt_module_name = str_replace('_and_', '', $target_str);
		$relation_settings = $this->_get_relation_settings($calling_module_name, $alt_module_name);
		$relationship_type = $relation_settings[2]->relationship_type;
		$first_module = $relation_settings[0];
		$second_module = $relation_settings[1];

		if ($relationship_type == 'one to many') {
			$parent_module_name = $relation_settings[0]->module_name;
			$child_module = $relation_settings[1];
			$identifier_column = $child_module->identifier_column;
			$foreign_key = $parent_module_name.'_id';
			$data[$foreign_key] = $update_id;
			$this->model->update($posted_data['value'], $data, $child_module->module_name);
		} else {
			$data[$calling_module_name.'_id'] = $update_id;
			$data[$alt_module_name.'_id'] = $posted_data['value'];
			$this->model->insert($data, $relation_name);
		}

    }

}