<?php
function perseo_multilang_gpt_widget() {
    echo '<div id="perseo-multilang-gpt" class="perseo-multilang-widget">';
    echo '<div class="current-lang-wrapper">';  
    echo '<img src="' . plugin_dir_url(__FILE__) . '../assets/it.svg" id="current-lang" />';
    echo '</div>';
    echo '<div id="lang-dropdown" style="display:none;">';
    echo '<img src="' . plugin_dir_url(__FILE__) . '../assets/en.svg" data-lang="en" />';
    echo '<img src="' . plugin_dir_url(__FILE__) . '../assets/es.svg" data-lang="es" />';
    echo '</div>';
    echo '</div>';
}
add_shortcode('perseo_multilanguage_gpt', 'perseo_multilang_gpt_widget');
