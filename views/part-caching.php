<?php
$live = checked($ocData['oc_caching']['live'], 'cache', false);
$longterm = checked($ocData['oc_caching']['longterm'], 'cache', false)
?>
<div class="tab" id ="caching">
    <h3>Varnish / ESI</h3>


    <p>
        Wenn Sie dieses Wordpress hinter einen Varnish Cache verwenden, können Sie durch einen einfachen klick die Ausgabe von "ESI-Tags" aktivieren.
        Gerade die Echtzeitausgaben von OpenClosed profitieren von dieser Einstellung, da durch sie das Caching für diese Elemente aufgehoben oder angepasst werden kann.
    </p>

    <table class="form-table">
        <colgroup>
            <col style="width:170px;">
        </colgroup>
        <tbody>
        <tr>
            <th scope="row">Caching Ausnahmen von Echtzeit Ausgaben aktivieren?</th>
            <td>
                <fieldset>
                    <p>
                        <label for="cache_live">
                            <input type="checkbox" name="oc_caching[live]" id="cache_live" <?php echo $live; ?> value="cache">
                            Ja Caching Ausnahmen für Echtzeitelemente aktivieren<br>
                            Wenn der Haken gesetzt ist, kann dem Varnish mitgeteilt werden, das die Echtzeitelemente aus dem Caching ausgenommen sein sollen, oder eine kürzere Cachzeit haben.
                        </label>
                    </p>
                    <p>
                        Das sind alle Minutengenauen angaben wie "Gerade geöffnet bis..." / "Gerade geschlossen bis ..." und die Öffnungschilder
                    </p>
                </fieldset>
            </td>
        </tr>
        <tr>
            <th scope="row">Caching von Langzeit Ausgaben aktivieren?</th>
            <td>
                <fieldset>
                    <p>
                        <label for="cache_longterm">
                            <input type="checkbox" name="oc_caching[longterm]" id="cache_longterm" <?php echo $longterm; ?> value="cache">
                            Ja Caching Ausnahmen für Langzeitelemente aktivieren <br>
                            Wenn der Haken gesetzt ist, kann dem Varnish mitgeteilt werden, das die Langzeitelemente aus dem Caching ausgenommen sein sollen, oder eine kürzere Cachzeit haben.
                        </label>
                    </p>
                    <p>
                        Das sind alle länger gültigen Ausgaben wie die Wochen-Öffnungszeiten.
                    </p>
                </fieldset>
            </td>
        </tr>
        </tbody>
    </table>
    <p>
        <strong>ESI URLs und vorgeschlagene Caching zeiten:</strong>
    </p>
    <table>
        <thead>
        <tr style="text-align:left;">
            <th>Aktiv?</th>
            <th>Caching URL</th>
            <th>Empfohlene Caching Dauer</th>
        </tr>
        </thead>
        <tbody>
        <tr>
            <td><span class="dashicons dashicons-<?php echo (!empty($live)) ? 'yes' : 'no'; ?> live"></span></td>
            <td><code><?php echo OC_PLUGIN_ESI_URL . 'snippet.php'; ?></code></td>
            <td>Dauer: 1-5 Minuten</td>
        </tr>
        <tr>
            <td><span class="dashicons dashicons-<?php echo (!empty($longterm)) ? 'yes' : 'no'; ?> longterm"></span></td>
            <td><code><?php echo OC_PLUGIN_ESI_URL . 'overview.php'; ?></code></td>
            <td>Dauer: 1-10 Tage</td>
        </tr>
        </tbody>
    </table>
    <p>
        Bitte beachten Sie, dass an die URLs vom Plugin noch GET-Parameter angehängt werden.<br>
        Ein vollständiger ESI call sieht z.B. so aus:
        <code style="display:block">
            <?php echo nl2br(htmlentities(oc_add_esitags('filename','oc_shopname','Lorem ipsum dolor set ...'))); ?><br>
        </code>
    </p>
</div>