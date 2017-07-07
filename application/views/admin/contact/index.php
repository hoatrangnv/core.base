<!-- Main content wrapper -->
<?php
$_data_action = function ($row) {
    ob_start()
    ?>
    <?php if ($row->_can_view): ?>
        <a href="<?php echo $row->_url_view; ?>" data-width="70%" title="<?php echo lang('detail'); ?>"
           class="tipS lightbox">
            <img src="<?php echo public_url('admin') ?>/images/icons/color/view.png"/>
        </a>
    <?php endif; ?>

    <?php if ($row->_can_del): ?>
        <a href="" _url="<?php echo $row->_url_del; ?>" title="<?php echo lang('delete'); ?>" class="tipS verify_action"
           notice="<?php echo lang('notice_confirm_delete'); ?>:<br><b><?php echo htmlentities($row->subject); ?></b>"
            >
            <img src="<?php echo public_url('admin') ?>/images/icons/color/delete.png"/>
        </a>
    <?php endif; ?>

    <?php return ob_get_clean();
};

$_data_status = function ($row) {
    ob_start();

    $readed = ($row->read) ? 'on' : 'off';
    $readed_text = ($row->read) ? 'read_yes' : 'read_no';
    $replyed_status = ($row->replyed_at) ? 'on' : 'off';
    $replyed_text = ($row->replyed_at) ? 'reply_yes' : 'reply_no';
    echo '<div class="mb5">' . macro()->status_color($readed, $readed_text) . '</div>';
    echo '<div class="mb5">' . macro()->status_color($replyed_status, $replyed_text) . '</div>';
    echo isset($row->_created_full) ? $row->_created_full : '';
    ?>

    <?php return ob_get_clean();
};
$_data_content = function ($row) {
    ob_start(); ?>
    <div>
        <?php
        $subject = htmlentities($row->subject);
        $message = htmlentities($row->message);

        ?>
        <b>Tiêu đề:</b><?php echo character_limiter($subject, 150) ?><br>
        <b>Nội dung:</b><?php echo character_limiter($message, 150) ?><br>

    </div>
    <?php if($row->replyed_at): ?>
    <b>Phản hồi bởi <?php echo $row->replyed_by_admin_name ?>:</b>

    <span>(<?php echo get_date($row->replyed_at, 'full'); ?>)</span>
    <?php echo character_limiter($row->replyed_content, 150) ?>
        <?php endif; ?>
    <?php return ob_get_clean();
};
$_macro = $this->data;
$_macro['table'] = array_only($this->data, array('total', 'actions', 'pages_config'));
$_macro['toolbar'] = array();
$_macro['table']['filters'] = array(
    array('param' => 'id',
        'value' => $filter['id'],
    ),
    array('param' => 'email',
        'value' => $filter['email'],
    ),
    array(
        'param' => 'read', 'name' => lang('status'), 'type' => 'select',
        'value' => $filter['read'],
        'values_single' => $verify, 'values_opts' => array('name_prefix' => 'read_'),
    ),

);

$_macro['table']['columns'] = array(
    'id' => lang('id'),
    'email' => lang('email'),
    'content' => lang('content'),
    'status' => lang('status'),
    'action' => lang('action'),
);

$_rows = array();
foreach ($list as $row) {
    $r = (array)$row;
    $r['content'] = $_data_content($row);
    $r['status'] = $_data_status($row);
    $r['action'] = $_data_action($row);
    $_rows[] = $r;
}
$_macro['table']['rows'] = $_rows;

echo macro()->page($_macro);

