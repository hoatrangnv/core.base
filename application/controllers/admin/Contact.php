<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Contact extends MY_Controller {

	/**
	 * Ham khoi dong
	 */
	function __construct()
	{
		parent::__construct();

		// Tai cac file thanh phan
		$this->load->model('contact_model');
		$this->lang->load('admin/contact');
	}

	/**
	 * Remap method
	 */
	function _remap($method)
	{
		if (in_array($method, array('view','reply', 'del')))
		{
			$this->_action($method);
		}
		elseif (method_exists($this, $method))
		{
			$this->{$method}();
		}
		else
		{
			show_404('', FALSE);
		}
	}

	/**
	 * Gan dieu kien cho cac bien
	 */
	protected function _set_rules($params)
	{
		$rules = array();
		$rules['content'] = array('content', 'required|trim|xss_clean|filter_html|min_length[6]|max_length[255]');

		$this->form_validation->set_rules_params($params, $rules);
	}

	/*
     * ------------------------------------------------------
     *  List handle
     * ------------------------------------------------------
     */
	/**
	 * Danh sach
	 */
	function index()
	{
		// Tai cac file thanh phan
		$this->load->helper('form');

		// Tao filter
		$filter_input 	= array();
		$filter_fields 	= array('id', 'name', 'email', 'subject', 'created', 'created_to', 'read');
		$filter = $this->contact_model->filter_create($filter_fields, $filter_input);
		$this->data['filter'] = $filter_input;

		// Lay tong so
		$total = $this->contact_model->filter_get_total($filter);
		$page_size = config('list_limit', 'main');
		$limit = $this->input->get('per_page');
		$limit = max(0, min($limit, get_limit_page_last($total, $page_size)));

		// Lay danh sach
		$input = array();
		$input['limit'] = array($limit, $page_size);
		$list = $this->contact_model->filter_get_list($filter, $input);

		$actions = array('view', 'del');
		$list = admin_url_create_option($list, strtolower(__CLASS__), 'id', $actions);
		foreach ($list as $row)
		{
			$row->_created = get_date($row->created);
			$row->_created_full = get_date($row->created, 'full');
			foreach ($actions as $action)
			{
				$row->{'_can_'.$action} = ($this->_can_do($row, $action) && admin_permission_url($row->{'_url_'.$action})) ? TRUE : FALSE;
			}
		}
		$this->data['list'] = $list;

		// Tao chia trang
		$pages_config = array();
		$pages_config['page_query_string'] = TRUE;
		$pages_config['base_url'] 	= current_url().'?'.url_build_query($filter_input);
		$pages_config['total_rows'] = $total;
		$pages_config['per_page'] 	= $page_size;
		$pages_config['cur_page'] 	= $limit;
		$this->data['pages_config'] = $pages_config;


		// Tao action list
		$actions = array();
		foreach (array('del') as $v)
		{
			$url = admin_url(strtolower(__CLASS__).'/'.$v);
			if ( ! admin_permission_url($url)) continue;

			$actions[$v] = $url;
		}
		$this->data['actions'] = $actions;

		// Luu bien gui den view
		$this->data['action'] 	= current_url();
		$this->data['verify'] 	= config('verify', 'main');

		// Breadcrumbs
		$breadcrumbs = array();
		$breadcrumbs[] = array(admin_url('contact'), lang('mod_contact'));
		$breadcrumbs[] = array(current_url(), lang('list'));
		$this->data['breadcrumbs'] = $breadcrumbs;

		// Hien thi view
		$this->_display();
	}


	/*
     * ------------------------------------------------------
     *  Action handle
     * ------------------------------------------------------
     */
	/**
	 * Thuc hien tuy chinh
	 */
	function _action($action)
	{
		// Lay input
		$ids = $this->uri->rsegment(3);
		$ids = ( ! $ids) ? $this->input->post('id') : $ids;
		$ids = ( ! is_array($ids)) ? array($ids) : $ids;

		// Thuc hien action
		foreach ($ids as $id)
		{
			// Xu ly id
			$id = ( ! is_numeric($id)) ? 0 : $id;

			// Kiem tra id
			$info = $this->contact_model->get_info($id);
			if ( ! $info) continue;

			// Kiem tra co the thuc hien hanh dong nay khong
			if ( ! $this->_can_do($info, $action)) continue;

			// Chuyen den ham duoc yeu cau
			$this->{'_'.$action}($info);
		}
	}

	/**
	 * Kiem tra co the thuc hien hanh dong hay khong
	 */
	function _can_do($info, $action)
	{
		switch ($action)
		{
			case 'view':
			case 'del':
			case 'reply':
			{
				return TRUE;
			}
		}

		return FALSE;
	}

	/**
	 * Xem chi tiet
	 */
	function _view($info)
	{
		// Gan trang thai
		if ($info->read == config('verify_no', 'main'))
		{
			$this->contact_model->update_field($info->id, 'read', config('verify_yes', 'main'));
		}

		// Xu ly thong tin
		$info->_created = get_date($info->created);
		$info->_created_full = get_date($info->created, 'full');

		// Luu bien gui den view
		$this->data['info'] = $info;

		// Hien thi view
		$this->_display('view', NULL);
	}
	function _reply($info)
	{
		// Tai cac file thanh phan
		$this->load->library('form_validation');
		$this->load->helper('form');


		// Gan dieu kien cho cac bien
		$params = array('content');
		$this->_set_rules($params);
		// Xu ly du lieu
		$result = array();
		if ($this->form_validation->run()) {
			$admin =admin_get_account_info();
			// Lay content
			$content = $this->input->post('content',true);
			$content = strip_tags($content);

			// Them du lieu vao data
			$data = array();
			$data['replyed_by_admin_id'] = $admin->id;
			$data['replyed_by_admin_name'] =$admin->name;;
			$data['replyed_content'] = $content;
			$data['replyed_at'] = now();
			set_message(lang('notice_update_success'));
			model("contact")->update($info->id,$data);


			// gui email
			mod('email')->send('contact_reply', $info->email, array(
				'content' => $content,
			));
			// Khai bao du lieu tra ve
			$result['complete'] = TRUE;

		} else {
			foreach ($params as $param) {
				$result[$param] = form_error($param);
			}
		}
		//Form Submit
		$this->_form_submit_output($result);
	}

	/**
	 * Xoa du lieu
	 */
	function _del($info)
	{
		// Thuc hien xoa
		$this->contact_model->del($info->id);

		// Gui thong bao
		set_message(lang('notice_del_success'));
	}

}