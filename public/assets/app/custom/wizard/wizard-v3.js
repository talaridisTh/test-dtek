"use strict";

// Class definition
var KTWizard3 = function () {
	// Base elements
	var wizardEl;
	var formEl;
	var validator;
	var wizard;

	// Private functions
	var initWizard = function () {
		// Initialize form wizard
		wizard = new KTWizard('kt_wizard_v3', {
			startStep: 1,
		});

		// Validation before going to next page
		wizard.on('beforeNext', function(wizardObj) {
			if (validator.form() !== true) {
				wizardObj.stop();  // don't go to the next step
			}
		})

		// Change event
		wizard.on('change', function(wizard) {
			KTUtil.scrollTop();
		});
	}

	var initValidation = function() {
		validator = formEl.validate({
			// Validate only visible fields
			ignore: ":hidden",

			// Validation rules
			rules: {
				//= Step 1
				address1: {
					required: true
				},
				state: {
					required: true
				},
				country: {
					required: true
				},
				is_product_shelf: {
					required: true
				},
				warehouse_id: {
					required: true
				}
			},

			// Display error
			invalidHandler: function(event, validator) {
				KTUtil.scrollTop();

				swal.fire({
					"title": "",
					"text": "Υπήρξαν κάποια προβλήματα με την φόρμα σας. Παρακαλώ διορθώστε τα.",
					"type": "error",
					"confirmButtonClass": "btn btn-secondary"
				});
			},

			// Submit valid form
			submitHandler: function (form) {

			}
		});
	}

	var initSubmit = function() {
		var btn = formEl.find('[data-ktwizard-type="action-submit"]');

		$('body').on('click', '[data-ktwizard-type="action-submit"]', function(e) {
			e.preventDefault();
			let self = this;
			if (validator.form()) {
				// See: src\js\framework\base\app.js
				KTApp.progress(self);
				//KTApp.block(formEl);

				// See: http://malsup.com/jquery/form/#ajaxSubmit
				let method = $(formEl).attr('method');
				if(method != 'POST' && method != 'PUT') method = 'POST';
				const action = $(formEl).attr('action');
				var formData = serilizeFormData($(formEl));
				var redirect = BASE_URL + $(formEl).attr('data-redirect');

				let sendOffer = $(self).data('send-offset');
				if(sendOffer == '1') formData.append('sendOffer', true);

				if(method == 'PUT') {
					method = 'POST';
					formData.append('_method', 'PUT');
				}
				$.ajax({
					url: action,
					method,
					data:formData,
					contentType: false,
        			processData: false
				})
				.done(function(msg) {
					if(msg.redirect_to != undefined && msg.redirect_to != null) {
						redirect = msg.redirect_to;
					}

					if(msg.customer_id && $('#customer_id').length >= 1) {
                        $('#customer_id').val(msg.customer_id);
                    }

					KTApp.unprogress(self);
					swal.fire({
						"title": "",
						"text": "Τα δεδομένα αποθηκεύτηκαν με επιτυχία!",
						"type": "success",
						"confirmButtonClass": "btn btn-secondary",
						onClose: function() {
							window.location.href = redirect;
						}
					});
				})
				.fail(function(err) {
					KTApp.unprogress(self);
					console.log(err);
				})
			}
		});
	}

	return {
		// public functions
		init: function() {
			wizardEl = KTUtil.get('kt_wizard_v3');
			formEl = $('#kt_form');

			initWizard();
			initValidation();
			initSubmit();
		}
	};
}();

jQuery(document).ready(function() {
	KTWizard3.init();
});
function serilizeFormData(formInput)
{
	var form = formInput;
	var	formData = new FormData();
	$.each(form.find('input,select'), function(i, tag) {
		$(this).attr("readonly", false);
		$(this).prop("readonly", false);
		$(this).attr("disabled", false);
		$(this).prop("disabled", false);
	});
	var	formParams = formInput.serializeArray();
	$.each(form.find('input[type="file"]'), function(i, tag) {
		$.each($(tag)[0].files, function(i, file) {
		formData.append(tag.name, file);
		});
	});

	$.each(formParams, function(i, val) {
		formData.append(val.name, val.value);
	});

	return formData;
}
