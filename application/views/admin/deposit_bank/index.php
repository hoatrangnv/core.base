
<?php
	$_status_options = function() use ($statuss)
	{
		$result = array();
		
		foreach ($statuss as $v)
		{
			$result[$v] = lang('status_'.$v);
		}
		
		return $result;
	};

	$_time = array();
	foreach ($renewtv_times as  $val)
	{
	    $_types[$val] = $val.' '.lang('month');
	}

    $_types = array();
    foreach ($renewtv_types as $key => $val)
    {
        $_types[$key] = lang('renewtv_type_'.$val);
    }
    
	$_macro = $this->data;
	$_macro['toolbar'] = array();
	$_macro['table'] = array_only($this->data, array('total', 'actions', 'pages_config'));
	
	$_macro['table']['filters'][] = array(
	    'param' 	=> 'user_id',
	    'name' 		=> lang('user'),
	    'value' 	=> $filter['user_id'],
	);
	
	$_macro['table']['filters'][] = array(
	    'param' 	=> 'code',
	    'name' 		=> lang('code'),
	    'value' 	=> $filter['code'],
	);
	
	$_macro['table']['filters'][] = array(
	    'param' 	=> 'type',
	    'type' 		=> 'select',
	    'value' 	=> $filter['type'],
	    'values' 	=> $_types,
	);
	
	$_macro['table']['filters'][] = array(
	    'param' 	=> 'time',
	    'type' 		=> 'select',
	    'value' 	=> $filter['time'],
	    'values' 	=> $_time,
	);
	
	$_macro['table']['filters'][] = array(
	    'param' 	=> 'status',
	    'type' 		=> 'select',
	    'value' 	=> $filter['status'],
	    'values' 	=> $_status_options(),
	);

	$_macro['table']['filters'][] = array('name' => lang('from_date'), 'param' => 'created', 'type' => 'date',
	    'value' => $filter['created']
	);
	
	$_macro['table']['filters'][] = array('name' => lang('to_date'), 'param' => 'created_to', 'type' => 'date',
	    'value' => $filter['created_to'],
	);
	
	$_macro['table']['columns'] = array(
		'id' 		=> lang('id'),
	    'code' 		=> lang('code'),
		'type' 		=> lang('type'),
		'time'    	=> lang('time'),
		'amount' 	=> lang('amount'),
		'status' 	=> lang('status'),
		'user' 		=> lang('user'),
		'created'	=> lang('created'),
	);

	$_rows = array();
	foreach ($list as $row)
	{
		$r = (array) $row;
		$r['user'] 		= '<a href="'.admin_url('renewtv').'?user_id='.$row->user_id.'">'.$row->user->email.'<br/>'.$row->user->phone.'</a>';
		$r['status']	= macro()->status_color($row->_status);
		$r['amount'] 	= $row->amount ? $row->_amount : '';
		$r['created'] 	= $row->_created_full;
		$_rows[] = $r;
	}
	$_macro['table']['rows'] = $_rows;
	
	echo macro()->page($_macro);
	
	