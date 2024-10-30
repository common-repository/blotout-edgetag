jQuery(document).ready(function($) {
    // Find the input element with the specific value
    var input = $('input[value="blotout-edgetag/blotout-edgetag.php"]');

    // Go to the nearest parent tr
    var tr = input.closest('tr');

    // Find the element with the specific class within the tr
    var icon = tr.find('.dashicons-admin-plugins');

    // Add the new class to the element
    icon.addClass('edgetag-image-cont');
});