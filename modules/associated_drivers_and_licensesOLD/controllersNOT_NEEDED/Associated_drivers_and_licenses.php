<?php
class Associated_drivers_and_licenses extends Trongate {

	function prep_inbound($input) {
		$params = $input['params'];

		foreach($params as $param_key => $param_value) {
			$last_three = substr($param_key, strlen($param_key)-3, strlen($param_key));

			if ($last_three !== '_id') {
				unset($params[$param_key]);
			}
		}

		$input['params'] = $params;
		return $input;
	}

/*
{
  "licenses_id":2,
  "associated_module":"drivers",
  "identifier_column":"last_name"
}
*/

	function prep_outbound($output) {

		$response_text = $output['body'];
		$rows = json_decode($response_text); //the rows that were found by the GET request
		$params = (array) json_decode(file_get_contents('php://input'));
		$foreign_key_column = $params['associated_module'].'_id';

		if (gettype($rows) == 'array') {

			$found = [];
			foreach($rows as $row) {

				if (!isset($found[$row->$foreign_key_column])) {
					$found_data['id'] = $row->id;
					$found_data['foreign_key_value'] = $row->$foreign_key_column;
					$found[$row->$foreign_key_column] = $found_data;
				}

			}

			$params = (array) json_decode(file_get_contents('php://input'));
			$alt_module_records = $this->model->get($params['identifier_column'], $params['associated_module']);
			$identifier_column = $params['identifier_column'];

			foreach($alt_module_records as $alt_module_record) {
				$id = $alt_module_record->id;

				if (isset($found[$id])) {
					$row_data['id'] = $found[$id]['id'];
					$row_data['key'] = $id;
					$row_data['value'] = $alt_module_record->$identifier_column;
					$results[] = $row_data;
				}

			}

			if (isset($results)) {
				$output['body'] = json_encode($results);
			} else {
				$output['body'] = '[]';
			}

		}

		return $output;

	}

}