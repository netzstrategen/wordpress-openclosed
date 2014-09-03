<div class="tab" id ="holidays">
    <h3>Feiertage:</h3>

    <div style="clear: both;">
        <?php

        for ($y = date('Y', current_time('timestamp')); $y <= date('Y', current_time('timestamp')) + 2; $y++) {
            for ($m = 1; $m <= 12; $m++) {
                for ($d = 1; $d <= 31; $d++) {
                    $month = str_pad($m, 2, 0, STR_PAD_LEFT);
                    $day = str_pad($d, 2, 0, STR_PAD_LEFT);

                    $holidayKey = $y . '-' . $month . '-' . $day;
                    $holidayDate = $day . '.' . $month . '.' . $y;
                    if (isset($ocData['oc_holiday_' . $holidayKey])) {
                        ?>

                        <div style="width: 350px; float: left; padding: 0 5px; line-height: 20px;">
                            <a href="javascript:;" class="remove_parent" style="text-decoration:none"><span class="dashicons dashicons-no"></span></a>
                            <label for="oc_holiday_<?php echo $holidayKey; ?>"><?php echo $holidayDate; ?>: <?php echo $ocData['oc_holiday_' . $holidayKey]; ?></label>
                            <input id="oc_holiday_<?php echo $holidayKey; ?>" name="oc_holiday_<?php echo $holidayKey; ?>" type="hidden" style="margin-right: 3px;" value="<?php echo $ocData['oc_holiday_' . $holidayKey]; ?>">
                        </div>

                    <?php
                    }
                }
            }
        }

        ?>
    </div>
    <div style="clear: both;">
        <p>
            <label>iCal-File hochladen:</label> <input name="oc_holidays[]" type="file">
        </p>
        <p id="manual_holiday_inputs">
            <label>Feiertag manuell eintragen:</label>
            <input type="text" name="oc_holidays_manual[0][date]" placeholder="dd.mm.yyyy" value="" class="datepicker"><input type="text" name="oc_holidays_manual[0][name]" placeholder="Feiertag" value=""><br>
        </p>
        <p>
            <a href="javascript:;" id="duplicate_manual_holiday" class="button-secondary"><span class="dashicons dashicons-plus"></span> <?php _e('Einen weiteren Feiertag eintragen','its_openclosed'); ?></a>
        </p>
    </div>
</div>