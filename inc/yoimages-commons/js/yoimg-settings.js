jQuery(document).ready(function($) {
	function yoimgSetupSettingsInputsDisplay() {
		$('#yoimg-settings-wrapper input[type="checkbox"]').each(function() {
			var currCheckbox = $(this);
			var isChecked = currCheckbox.is(':checked');
			var dependentFields = $('.' + currCheckbox.attr('id') + '-dep').parents('tr');
			if (isChecked) {
				dependentFields.show();
			} else {
				dependentFields.hide();
			}
		});
	}
	yoimgSetupSettingsInputsDisplay();
	$('#yoimg-settings-wrapper input[type="checkbox"]').change(yoimgSetupSettingsInputsDisplay);
});