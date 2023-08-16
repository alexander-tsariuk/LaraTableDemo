<div id="edit-notify" class="modal fade" tabindex="-1" role="dialog">
	<div class="modal-dialog" role="document">
		<div class="modal-content">
			<div class="modal-header">
				<x-modal.close />
				<h4 class="modal-title"><?php echo __('APP.SAVING_CHANGES'); ?></h4>
			</div>
			<div class="modal-body">
				<p><?php echo __('APP.DATA_HAS_BEEN_CHANGED')?></p>
				<p><?php echo __('APP.SAVE_CHANGES')?></p>
			</div>
			<div class="modal-footer">
				<button type="button" class="btn btn-default save-changes"><?php echo __('APP._YES'); ?></button>
				<button type="button" class="btn btn-default not-save"><?php echo __('APP._NOT'); ?></button>
				<button type="button" class="btn btn-default" data-dismiss="modal"><?php echo __('APP.CANCEL_2')?></button>
			</div>
		</div>
	</div>
</div>
