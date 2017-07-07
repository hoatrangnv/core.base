<div class="portlet">
	<div class="portlet-heading dark">
		<div class="portlet-title">
			<h4><i class="fa fa-info"></i> <?php echo lang('title_support_view'); ?></h4>
		</div>

	</div>
	<div class="portlet-body ">
		<table class="table table-bordered table-striped table-hover tc-table">
			<tbody>
			<tr>
				<td class="row_label"><?php echo lang('id'); ?></td>
				<td class="row_item">
					<?php echo $info->id; ?>
				</td>
			</tr>

			<tr>
				<td class="row_label"><?php echo lang('name'); ?></td>
				<td class="row_item">
					<?php echo htmlentities($info->name); ?>
				</td>
			</tr>

			<tr>
				<td class="row_label"><?php echo lang('email'); ?></td>
				<td class="row_item">
					<?php echo $info->email; ?>
				</td>
			</tr>

			<tr>
				<td class="row_label"><?php echo lang('date'); ?></td>
				<td class="row_item">
					<?php echo $info->_created_full; ?>
				</td>
			</tr>

			<tr>
				<td class="row_label"><?php echo lang('subject'); ?></td>
				<td class="row_item">
					<?php echo htmlentities($info->subject); ?>
				</td>
			</tr>

			<tr>
				<td colspan="2">
					<form action="<?php echo admin_url('contact/reply/'.$info->id); ?>" class="form" id="form" method="post">
						<b class=" mb20">Nội dung liên hệ:</b><br>
						<?php echo nl2br(htmlentities($info->message)); ?><br>
						<textarea style='width:100%; margin-bottom:10px ' name="content" placeholder="Nhập phản hồi của bạn"></textarea>
						<span name="content_autocheck" class="autocheck"></span>
						<div name="content_error" class="clear error"></div>
						<input type="submit" value="Trả lời" class="btn btn-info btn-sm" />
					</form>

				</td>
			</tr>
			</tbody>
		</table>
	</div>
</div>
<script type="text/javascript">
	$(document).ready(function(){
		// Form handle
		$('#form, .form_action').each(function()
		{
			$(this).nstUI({
				method:	'formAction',
				formAction:	{
					field_load: $(this).attr('_field_load')
				}
			});
		});
		$('.verify_action').nstUI('verifyAction');
	});
</script>
