<div class="tab" id ="open_times">
    <h3 class="nav-tab-wrapper">
        <a class="nav-tab switchtab nav-tab-active" href="javascript:;" data-target="#times_1"><?php _e('1. Öffnungszeiten (z.B. Sommer)'); ?></a>
        <a class="nav-tab switchtab" href="javascript:;" data-target="#times_2"><?php _e('2. Öffnungszeiten (z.B. Winter)'); ?></a>
        <a class="nav-tab switchtab small" href="javascript:;" data-target="#times_2,#times_1"><?php _e('Beide anzeigen'); ?></a>
    </h3>
    <?php
    for ($j = 1; $j <= 2; $j++) {
        ?>

        <div class="tab<?php if ($j == 1){echo ' active';} ?>" id ="times_<?php echo $j; ?>">
            <strong><?php echo $j; ?>. Öffnungszeiten</strong> gültig von
            <select name="oc_opentimes_from_day_<?php echo $j; ?>">
                <?php foreach ($days as $currentDay) { ?>
                    <option name="oc_day"
                            value="<?php echo $currentDay; ?>" <?php if ($ocData['oc_opentimes_from_day_' . $j] == $currentDay) {
                        echo 'selected';
                    } ?> ><?php echo $currentDay; ?></option>
                <?php } ?>
            </select>
            <select name="oc_opentimes_from_month_<?php echo $j; ?>">
                <?php foreach ($months as $month) { ?>
                    <option name="oc_month"
                            value="<?php echo $month; ?>" <?php if ($ocData['oc_opentimes_from_month_' . $j] == $month) {
                        echo 'selected';
                    } ?> ><?php echo $month; ?></option>
                <?php } ?>
            </select>
            bis
            <select name="oc_opentimes_until_day_<?php echo $j; ?>">
                <?php foreach ($days as $currentDay) { ?>
                    <option name="oc_day"
                            value="<?php echo $currentDay; ?>" <?php if ($ocData['oc_opentimes_until_day_' . $j] == $currentDay) {
                        echo 'selected';
                    } ?> ><?php echo $currentDay; ?></option>
                <?php } ?>
            </select>
            <select name="oc_opentimes_until_month_<?php echo $j; ?>">
                <?php foreach ($months as $month) { ?>
                    <option name="oc_month"
                            value="<?php echo $month; ?>" <?php if ($ocData['oc_opentimes_until_month_' . $j] == $month) {
                        echo 'selected';
                    } ?> ><?php echo $month; ?></option>
                <?php } ?>
            </select>

            <ul>

                <?php foreach ($weekdays as $key => $weekday) { ?>

                    <li>
                        <strong style="display:inline-block; width: 100px;"><?php echo $weekday; ?>:</strong>
                        <select name="oc_<?php echo $key; ?>_from_<?php echo $j; ?>_1" style="width: 65px;">
                            <?php foreach ($times as $timeString) { ?>
                                <option
                                    value="<?php echo $timeString; ?>" <?php if ($ocData['oc_' . $key . '_from_' . $j . '_1'] == $timeString) {
                                    echo 'selected';
                                } ?> ><?php echo $timeString; ?></option>
                            <?php } ?>
                        </select>
                        bis
                        <select name="oc_<?php echo $key; ?>_until_<?php echo $j; ?>_1" style="width: 65px;">
                            <?php foreach ($times as $timeString) { ?>
                                <option
                                    value="<?php echo $timeString; ?>" <?php if ($ocData['oc_' . $key . '_until_' . $j . '_1'] == $timeString) {
                                    echo 'selected';
                                } ?> ><?php echo $timeString; ?></option>
                            <?php } ?>
                        </select>
                        und
                        <select name="oc_<?php echo $key; ?>_from_<?php echo $j; ?>_2" style="width: 65px;">
                            <?php foreach ($times as $timeString) { ?>
                                <option
                                    value="<?php echo $timeString; ?>" <?php if ($ocData['oc_' . $key . '_from_' . $j . '_2'] == $timeString) {
                                    echo 'selected';
                                } ?> ><?php echo $timeString; ?></option>
                            <?php } ?>
                        </select>
                        bis
                        <select name="oc_<?php echo $key; ?>_until_<?php echo $j; ?>_2" style="width: 65px;">
                            <?php foreach ($times as $timeString) { ?>
                                <option
                                    value="<?php echo $timeString; ?>" <?php if ($ocData['oc_' . $key . '_until_' . $j . '_2'] == $timeString) {
                                    echo 'selected';
                                } ?> ><?php echo $timeString; ?></option>
                            <?php } ?>
                        </select>
                        <input name="oc_closed_<?php echo $key; ?>_<?php echo $j; ?>" value="closed"
                               type="checkbox" <?php if ( isset($ocData['oc_closed_' . $key . '_' . $j]) ) {
                            echo 'checked';
                        } ?> >&nbsp;<label>geschlossen</label>
                    </li>

                <?php } ?>

            </ul>
        </div>

    <?php
    }
    ?>
</div>