<?php  
class Apples extends Trongate {

	function manage() {
		$data['rows'] = $this->model->get('date_created');
		$data['view_file'] = 'manage';
		$this->template('admin', $data);
	}

}