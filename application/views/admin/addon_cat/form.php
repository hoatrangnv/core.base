<?php
$_macro = $this->data;
$_macro['toolbar_sub'] = array(
    array('url' => admin_url('addon'), 'title' => lang('mod_addon')),
    array('url' => admin_url('addon_cat'), 'title' => lang('mod_addon_cat'),'attr'=>array('class'=>'active') )
);
echo macro()->page($_macro);
?>
	<!--<style type="text/css">
        .form-group {
            border-bottom: 1px solid #f1f1f1;
            margin-bottom: 15px;
            padding: 10px 0 20px;
        }
    </style>-->
	<!-- Main content wrapper -->
	<div class="portlet">
		<div class="portlet-heading dark">
			<div class="portlet-title">
				<h4><i class="fa fa-pencil"></i> <?php echo lang('mod_addon_cat'); ?></h4>
			</div>

		</div>
		<div class="portlet-body ">

			<!-- Form -->
			<form class="form form-horizontal" id="form" method="post" action="<?php echo $action; ?>">
				<div class="tc-tabs">
					<ul class="nav nav-tabs tab-color-dark background-dark white">
						<li class="active"><a href="#tab_general" data-toggle="tab"><i class="fa fa-dashboard"></i> <?php echo lang('tab_general') ?></a></li>
					</ul>
					<div class="tab-content">
						<div class="tab-pane active" id="tab_general"><?php t('view')->load('tpl::addon_cat/form/general'); ?></div>
						<div class="form-actions">
							<div class="form-group formSubmit">
								<div class="col-sm-offset-2 col-sm-10">
									<input type="submit" value="<?php echo lang('button_update'); ?>"
										   class="btn btn-primary"/>
                                           									<input value="<?php echo lang("button_reset") ?>" class="btn" type="reset">

								</div>
							</div>
						</div>
						<div class="clearfix"></div>
					</div>

				</div>


				<div class="clear"></div>
			</form>
		</div>
	</div>

<?php t('view')->load('tpl::addon_cat/_js') ?>