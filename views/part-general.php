<div class="tab active" id ="general">
    <table class="form-table">
        <tbody>
        <tr>
            <th>Linktext &amp; Link "offen":</th>
            <td>
                <fieldset>
                    <input  class="regular-text" type="text" name="oc_open_text" id="" placeholder="&quot;Offen&quot; Link-Text" value="<?php echo $ocData['oc_open_text']; ?>">
                    <input  class="regular-text code" type="text" name="oc_open_link" id="" placeholder="&quot;Offen&quot; Link-Ziel" value="<?php echo $ocData['oc_open_link']; ?>">
                    enspricht {LinktextOpen}
                </fieldset>
            </td>
        </tr>
        <tr>
            <th>Linktext &amp; Link "geschlossen":</th>
            <td>
                <fieldset>
                    <input class="regular-text" type="text" name="oc_closed_text" id=""  placeholder="&quot;Geschlossen&quot; Link-Text" value="<?php echo $ocData['oc_closed_text']; ?>">
                    <input  class="regular-text code" type="text" name="oc_closed_link" id="" placeholder="&quot;Geschlossen&quot; Link-Ziel" value="<?php echo $ocData['oc_closed_link']; ?>">
                    entspricht {LinktextClosed}
                </fieldset>
            </td>
        </tr>
        <tr>
            <th>Text Frontend "offen":</th>
            <td>
                <fieldset>
                    <legend class="screen-reader-text"><span>Text Frontend "offen":</span></legend>
                    <input class="regular-text" type="text" placeholder="Text der um den &quot;Geöffnet&quot;-Link angezeigt wird" name="oc_open_frontend_text" id=""
                           value="<?php echo $ocData['oc_open_frontend_text']; ?>">
                </fieldset>
            </td>
        </tr>
        <tr>
            <th>Text Frontend "geschlossen":</th>
            <td>
                <fieldset>
                    <legend class="screen-reader-text"><span>Text Frontend "geschlossen":</span></legend>
                    <input class="regular-text" type="text" placeholder="Text der um den &quot;Geschlossen&quot;-Link angezeigt wird" name="oc_closed_frontend_text" id="oc_closed_frontend_text"
                           value="<?php echo $ocData['oc_closed_frontend_text']; ?>">
                </fieldset>
            </td>
        </tr>
        <tr>
            <th>Verfügbare Makros:</th>
            <td>
                <dl>
                    <dt class="code">
                    <dt class="code">{LinktextOpen}</dt><dd>Enthält den Hinweis-Link oder Text wenn gerade offen ist.</dd>
                    <dt class="code">{LinktextClosed}</dt><dd>Enthält den Hinweis-Link oder Text wenn gerade geschlossen ist.</dd>
                    <dt class="code">{DayNextOpen}</dt><dd>Gibt das nächste Datum und den Tag Namen aus, an dem wieder geöffnet ist.</dd>
                    <dt class="code">{OpenTime}</dt><dd>Gibt die Uhrzeit aus, ab der wieder geöffnet ist.</dd>
                    <dt class="code">{CloseTime}</dt><dd>Gibt die Uhrzeit aus, bis wann heute geöffnet ist.</dd>
                    <dt class="code">{sign}</dt><dd>Gibt das entsprechende "Ampel-Bild" aus und zeigt an, ob gerade geöffnet oder geschlossen ist.</dd>
                </dl>
            </td>
        </tr>
        <tr>
            <th rowspan="2">
                Darstellung:<br>
                <p>
                    <span class="small" style="font-weight:normal;">Optimale Größe: 24 x 16 px</span><br>
                </p>
            </th>
            <td>
                <label  class="upload_form" for="upload_open_image"><strong>Offen</strong><br>
                    <?php


                    if(!empty($ocData['oc_images']['open']))
                        echo '<img src="' . $ocData['oc_images']['open'] . '" style="max-width:100px;height:auto;"><br>';
                    ?>
                    <input class="regular-text code file" id="upload_open_image" type="text" size="36" name="oc_images[open]" placeholder="http://..." value="<?php echo ( isset($ocData['oc_images']['open']) ) ? $ocData['oc_images']['open'] : $default_open; ?>" />
                    <input id="upload_open_image_button" class="button" type="button" value="Upload Image" />
                </label>
                <br /> Default Wert: <code><?php echo $default_image['open']; ?></code>
                <br /> Wert (neutral): <code><?php echo $default_image['neutral']; ?></code>
            </td>
        </tr>
        <tr>
            <td>
                <label class="upload_form" for="upload_closed_image"><strong>Geschlossen</strong><br>
                    <?php
                    if(!empty($ocData['oc_images']['closed']))
                        echo '<img src="' . $ocData['oc_images']['closed'] . '" style="max-width:100px;height:auto;"><br>';
                    ?>
                    <input class="regular-text code file" id="upload_closed_image" type="text"  size="36" name="oc_images[closed]" placeholder="http://..." value="<?php echo ( isset($ocData['oc_images']['closed']) ) ? $ocData['oc_images']['closed'] : $default_close; ?>" />
                    <input id="upload_closed_image_button" class="button" type="button" value="Upload Image" />
                </label>
                <br /> Default Wert: <code><?php echo $default_image['closed']; ?></code>
                <br /> Wert (neutral): <code><?php echo $default_image['neutral']; ?></code>
            </td>
        </tr>
        </tbody>
    </table>
</div>