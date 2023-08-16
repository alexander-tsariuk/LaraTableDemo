<div class="modal-dialog modal-lg select-list" role="document">
	<div class="modal-content">
		<div class="modal-header">
			<h4 class="modal-title">{{ __($model->getTitle()) }}</h4>
			<x-modal.close />
		</div>
		<div class="modal-body">
			<div class="table-outer">
    			<table class="table items-table">
    				<thead>
    					<tr>
    						<th width="30px"><?php echo __('APP.ID'); ?></th>
    						<th><?php echo __('APP.TITLE'); ?></th>
    						<th width="100px"><?php echo __('APP.CODE_OKIE'); ?></th>
    						<th width="80px"></th>
    					</tr>
    				</thead>
    				<?php if (!empty($data)) { ?>
    				<tbody>
    					<?php $i = 0 ; ?>
    					<?php foreach ($data as $value) { ?>
    						<?php 
    						$i++;
    						$class = $i % 2 == 0 ? 'gray' : ''; 
    						?>
        					<tr class="<?php echo $class; ?>">
        						<td><?php echo $value->id;?></td>
        						<td>
        							<span class="val"><?php echo __($value->name);?></span>
        							<input type="text" name="name" value="<?php echo __($value->name);?>" />
        							<input type="hidden" name="id" value="<?php echo $value->id;?>" />
        						</td>
        						<td>
        							<span class="val"><?php echo __($value->code);?></span>
        							<input type="text" name="code" value="<?php echo __($value->code);?>" />
        						</td>
        						<td class="icon text-center">
									<x-forms.select.list-edit :data="$value" :model="$model" />
        						</td>        						
         					</tr>		
    
    					<?php } ?>
    				</tbody>
    				<?php } ?>
    			</table>
			</div>
			{{ $data->links() }}
		</div>
		<div class="modal-footer">

		</div>
	</div>
</div>