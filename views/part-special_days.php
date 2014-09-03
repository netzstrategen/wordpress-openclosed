<div class="tab" id ="special_days">
    <h3>Sonder-Öffnungszeiten (Schausonntage, verkaufsoffene Sonntage):</h3>
    <ul id="special_day_listing">
        <li style="display:none;">
            <input type="text" name="oc_special_days[X][date]" placeholder="dd.mm.yyyy" value="" class="datepicker">
            von
            <select name="oc_special_days[X][from]" style="width: 65px;">
                <?php foreach ($times as $timeString) { ?>
                    <option value="<?php echo $timeString; ?>">
                        <?php echo $timeString; ?>
                    </option>
                <?php } ?>
            </select>
            bis
            <select name="oc_special_days[X][until]" style="width: 65px;">
                <?php foreach ($times as $timeString) { ?>
                    <option value="<?php echo $timeString; ?>">
                        <?php echo $timeString; ?>
                    </option>
                <?php } ?>
            </select>

            <legend class="screen-reader-text"><span>Linktext &amp; Link "offen":</span></legend>
            <input type="text" name="oc_special_days[X][text]" placeholder="Link Text" value="">
            <input type="text" name="oc_special_days[X][link]" placeholder="http://URL" value="">
            <a href="javascript:;" class="remove_parent" style="text-decoration:none"><span class="dashicons dashicons-no"></span></a>
        </li>
        <?php
        $specialday_count = 0;
        foreach ($ocData['oc_special_days'] as $special_day) {
            ?>
            <li>
                <?php

                $date = $special_day['date'];
                $from = $special_day['from'];
                $until = $special_day['until'];
                $text = $special_day['text'];
                $link = $special_day['link'];
                ?>

                <input type="text" name="oc_special_days[<?php echo $specialday_count; ?>][date]" placeholder="dd.mm.yyyy" value="<?php echo $date;?>" class="datepicker">
                <?php _e('von'); ?>
                <select name="oc_special_days[<?php echo $specialday_count; ?>][from]" style="width: 65px;">
                    <?php foreach ($times as $timeString) { ?>
                        <option value="<?php echo $timeString; ?>"<?php if ($from == $timeString) { echo 'selected';} ?> >
                            <?php echo $timeString; ?>
                        </option>
                    <?php } ?>
                </select>
                <?php _e('bis'); ?>
                <select name="oc_special_days[<?php echo $specialday_count; ?>][until]" style="width: 65px;">
                    <?php foreach ($times as $timeString) { ?>
                        <option value="<?php echo $timeString; ?>"<?php if ($until == $timeString) {echo ' selected';} ?> >
                            <?php echo $timeString; ?>
                        </option>
                    <?php } ?>
                </select>
                <input type="text" name="oc_special_days[<?php echo $specialday_count; ?>][text]" placeholder="Link Text" value="<?php echo $text; ?>">
                <input type="text" name="oc_special_days[<?php echo $specialday_count; ?>][link]" placeholder="http://URL" value="<?php echo $link; ?>">
                <a href="javascript:;" class="remove_parent" style="text-decoration:none"><span class="dashicons dashicons-no"></span></a>
            </li>
            <?php
            $specialday_count++;
        }
        ?>
    </ul>
    <p>
        <a href="javascript:;" id="duplicate_special_date" class="button-secondary"><span class="dashicons dashicons-plus"></span> <?php _e('Einen weiteren Sondertermin eintragen','its_openclosed'); ?></a>
    </p>
</div>

