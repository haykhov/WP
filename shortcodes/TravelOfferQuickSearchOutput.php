<?php
/* @var string $airportsDropdownHtml */

/* @var string $durationOptions */
?>
<div id="pwr-travel-search-container">
    <div id="pwr-travel-search-form">
        <div class="pwr-travel-field">
            <input type="text" id="pwr-destination-hotel" name="destination" placeholder="Ziel/Hotel">
            <input type="hidden" id="pwr-nid" name="nid">
            <input type="hidden" id="pwr-rgid" name="rgid">
            <input type="hidden" id="pwr-rid" name="rid">
            <input type="hidden" id="pwr-cyid" name="cyid">
            <input type="hidden" id="pwr-aid" name="aid">

            <!-- Autocomplete Modal Container -->
            <div id="pwr-autocomplete-modal" class="pwr-hidden"></div>
        </div>

        <div class="pwr-travel-field">
            <select id="pwr-departure-airports" name="depap[]" multiple="multiple">
                <?= $airportsDropdownHtml; ?>
            </select>
        </div>

        <div class="pwr-travel-field">
            <input type="text" id="pwr-travel-dates" name="travel-dates" >
            <div class="pwr-travel-duration-container pwr-hidden">
                <label>Reisedauer</label>
                <select class="pwr-travel-duration">
                    <?= $durationOptions; ?>
                </select>
            </div>

            <input type="hidden" id="pwr-ddate" name="ddate">
            <input type="hidden" id="pwr-rdate" name="rdate">
            <input type="hidden" id="pwr-dur" name="dur" value="7">
        </div>

        <div class="pwr-travel-field">
            <input type="text" id="pwr-travelers" readonly>

            <div id="pwr-travelers-modal" class="pwr-hidden">
                <div class="pwr-travelers-section">
                    <label for="pwr-adults" class="pwr-label">Erwachsene</label>
                    <button type="button" class="pwr-decrease">-</button>
                    <input type="number" id="pwr-adults" name="adult" value="2" min="1" max="4">
                    <button type="button" class="pwr-increase">+</button>
                </div>
                <div class="pwr-travelers-section">
                    <label for="pwr-children" class="pwr-label">Kinder</label>
                    <button type="button" class="pwr-decrease">-</button>
                    <input type="number" id="pwr-children" value="0" min="0" max="4">
                    <input type="hidden" id="pwr-children-values" name="child">

                    <button type="button" class="pwr-increase">+</button>
                </div>
                <div id="pwr-children-ages"></div>
            </div>
        </div>

        <div class="pwr-travel-field">
            <button type="submit" id="pwr-travel-search-submit">Suchen</button>
        </div>
    </div>
</div>
