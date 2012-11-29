<div class="page-header">
    <h1>Change Password</h1>
</div>
<?=show_messages_from_session();?>
<?=validation_errors('<div class="infoblock error">', '</div>');?>

<?=form_open('auth/reset_password/' . $code, array('id'=>'form', 'class'=>'form-horizontal'));?>
	<?=form_input($user_id);?>
	<?=form_hidden($csrf); ?>
    <div class="control-group">
        <label class="control-label" for="new">New Password (at least <?=$min_password_length;?> characters long):</label>
        <div class="controls">
            <?=form_input($new_password);?>
        </div>
    </div>
    <div class="control-group">
        <label class="control-label" for="new_confirm">Confirm New Password:</label>
        <div class="controls">
            <?=form_input($new_password_confirm);?>
        </div>
    </div>
    <div class="control-group">
        <div class="controls">
            <?=form_submit('submit', 'Change', 'class="btn"');?>
        </div>
    </div>
<?php echo form_close();?>