<div class="wrap openclosed">

    <?php if (!empty($error)) {echo '<div id="message" class="error"><p>'. $error .'</p></div>';} ?>
    <h2>OpenClosed - Das Öffnungszeiten Plugin</h2>

    <div class="ui-helper-clearfix">
        <ul class="subsubsub">
            <?php
            for ($currentId = 1; $currentId <= OC_MAX_SHOPS_NUMBER; $currentId++) {
                $active = $currentId == $shopId ? ' class="current"' : '';
                $shopname = (isset($ocData['oc_shopname']))? $ocData['oc_shopname'] : '';

            ?>
                <li><a href="admin.php?page=oc-toplevel&shop=<?php echo $currentId; ?>"<?php echo ' ' . $active;?>>Shop <?php echo $currentId; ?></a></li>

            <?php
            }
            ?>
        </ul>
        <div class="info">(aktuelle Uhrzeit: <?php echo date('H:i', current_time('timestamp')); ?> Uhr)</div>
    </div>
    <form enctype="multipart/form-data" method="post" action="">

        <p>
            <h3 style="display:inline-block;">Shop <?php echo $shopId; ?> <input type="text" name="oc_shopname" placeholder="Shop Name" value="<?php echo $shopname; ?>">:</h3>

            <?php if (!empty($ocData['oc_shopname'])) : ?>

                Shortcode: [<?php echo $ocData['oc_shortcode_' . $shopId]; ?>]
                / alle Öffnungszeiten: [<?php echo $ocData['oc_shortcode_' . $shopId] . '_overview'; ?>]

            <?php else : ?>

                <?php _e('Bitte zuerst Shopname eingeben!','openclosed'); ?>

            <?php endif; ?>

        </p>


        <?php if (!empty($ocData['oc_shopname'])) : ?>

            <h2 class="nav-tab-wrapper">
                <a class="nav-tab switchtab nav-tab-active" href="javascript:;" data-target="#general"><?php _e('Allgemein'); ?></a>
                <a class="nav-tab switchtab" href="javascript:;" data-target="#open_times"><?php _e('Reguläre Öffnungszeiten'); ?></a>
                <a class="nav-tab switchtab" href="javascript:;" data-target="#special_days"><?php _e('Sonder-Öffnungszeiten'); ?></a>
                <a class="nav-tab switchtab" href="javascript:;" data-target="#holidays"><?php _e('Feiertage'); ?></a>
                <a class="nav-tab switchtab" href="javascript:;" data-target="#caching"><?php _e('Caching'); ?></a>
                <a class="nav-tab switchtab small" href="javascript:;" data-target="#general,#special_days,#open_times,#holidays,#caching"><?php _e('Alle untereiander'); ?></a>
            </h2>

            <?php require_once(dirname(__FILE__) . '/part-general.php') ?>
            <?php require_once(dirname(__FILE__) . '/part-open_until.php') ?>
            <?php require_once(dirname(__FILE__) . '/part-special_days.php') ?>
            <?php require_once(dirname(__FILE__) . '/part-holidays.php') ?>
            <?php require_once(dirname(__FILE__) . '/part-caching.php') ?>

        <?php endif; ?>

        <p class="submit">
            <input type="submit" name="oc_save" id="save-background-options" class="button-primary" value="<?php _e('Save all changes'); ?>">
        </p>
    </form>
</div>