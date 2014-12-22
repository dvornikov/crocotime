<?php defined('SYSPATH') or die('No direct script access.');

class Controller_Welcome extends Controller {
	private $format = 'html';

	public function before()
	{
		if ($this->request->is_ajax())
		{
			$this->format = 'json';
			$this->response->headers('content-type', 'application/json'); 
			// $this->response->headers('Content-Type', 'application/json; charset=utf-8');
		}
	}

	public function action_index()
	{
		$jedis = ORM::factory('Jedi');

		$form_inputs = array('name', 'strain', 'rank');
		foreach ($form_inputs as $name) 
		{
		    $value = Arr::get($_GET, $name, FALSE);
		    if ($value !== FALSE AND $value != '')
		    {
		        $jedis->where($name, 'like', $value.'%');
		    }
		}

		if ($sortby = Arr::get($_GET, 'sortby', FALSE)) {
			$jedis->order_by($sortby, 'asc');
		}

		$jedis_array = array();

		foreach ($jedis->find_all() as $jedi) {
			$jedis_array[] = $jedi->as_array();
		}

		$view = View::factory('jedis.'.$this->format)->set('jedis', $jedis_array);
		$this->response->body($view);
	}

	public function action_create()
	{
		$jedi = ORM::factory('Jedi');
		$jedi->values($_POST)->save();
	}

	public function action_delete()
	{
		ORM::factory('Jedi')->delete_by_ids(Arr::get($_POST, 'selected'));
	}

} // End Welcome
