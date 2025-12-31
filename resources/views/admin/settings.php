<div class="mb-5">
<div class="mb-4">
    <a href="<?php echo URL_ROOT; ?>/admin" class="btn btn-outline-secondary btn-sm">
        &larr; <?php echo __('set_back_dash'); ?>
    </a>
</div>

<div class="row mb-4">
    <div class="col-md-12">
        <h1 class="h3 mb-3 font-weight-bold text-dark"><i class="fas fa-cogs mr-2 text-primary"></i><?php echo __('set_title'); ?></h1>
        <p class="text-muted"><?php echo __('set_subtitle'); ?></p>
    </div>
</div>

<?php Session::flash('settings_msg'); ?>

<form action="<?php echo URL_ROOT; ?>/admin/settings" method="post">
    <div class="row">
        <?php 
        $currentGroup = '';
        foreach($data['settings'] as $setting): 
            if ($currentGroup != $setting->setting_group):
                if ($currentGroup != '') echo '</div></div></div>';
                $currentGroup = $setting->setting_group;
        ?>
            <div class="col-md-6 mb-4">
                <div class="card shadow-sm border-0 h-100">
                    <div class="card-header bg-white py-3 border-bottom-0">
                        <h5 class="mb-0 font-weight-bold text-uppercase small text-primary">
                            <i class="fas fa-folder-open mr-2"></i><?php echo __('set_config_suffix'); ?> <?php echo $currentGroup; ?>
                        </h5>
                    </div>
                    <div class="card-body pt-0">
        <?php endif; ?>

            <div class="form-group mb-4">
                <label class="font-weight-bold text-dark small mb-1"><?php echo ucwords(str_replace('_', ' ', $setting->setting_key)); ?></label>
                <p class="x-small text-muted mb-2"><?php echo $setting->description; ?></p>
                
                <?php if ($setting->setting_key == 'allow_student_registration'): ?>
                    <select name="settings[<?php echo $setting->setting_key; ?>]" class="form-control">
                        <option value="1" <?php echo $setting->setting_value == '1' ? 'selected' : ''; ?>><?php echo __('set_opt_enabled'); ?></option>
                        <option value="0" <?php echo $setting->setting_value == '0' ? 'selected' : ''; ?>><?php echo __('set_opt_disabled'); ?></option>
                    </select>
                <?php else: ?>
                    <input type="text" name="settings[<?php echo $setting->setting_key; ?>]" value="<?php echo $setting->setting_value; ?>" class="form-control">
                <?php endif; ?>
            </div>

        <?php endforeach; ?>
        <?php if ($currentGroup != '') echo '</div></div></div>'; ?>
    </div>

    <div class="row mt-4">
        <div class="col-12 text-right">
            <button type="submit" class="btn btn-primary px-5 shadow-sm font-weight-bold">
                <i class="fas fa-save mr-2"></i> <?php echo __('set_btn_save'); ?>
            </button>
        </div>
    </div>
</form>
</div>
