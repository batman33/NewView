<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Smileys extends CI_Controller {


	function index()
	{
		$image_array = get_clickable_smileys(base_url() . 'images/smileys/');

		$col_array = $this->table->make_columns($image_array, 8);		
			
		$data['smiley_table'] = $this->table->generate($col_array);
		
		$this->load->view('smiley_view', $data);
	}
	
}