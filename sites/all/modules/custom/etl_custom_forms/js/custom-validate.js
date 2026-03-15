(function ($) {
  Drupal.behaviors.etlCustomValidate = {
    attach: function (context, settings) {

      var $form = $('#deferred-item-add-form', context); 
      if (!$form.length) {
        $form = $('form input[name="title"]').closest('form');
      }

      if (!$form.length) {
        console.warn('ETL form not found.');
        return;
      }

      $form.once('etl-validate', function () {

        // Add custom method: looseUrl – accepts domains without protocol
        $.validator.addMethod('looseUrl', function (value, element) {
          if (this.optional(element)) {
            return true;
          }

          value = $.trim(value);

          var pattern = /^(?:(?:https?:\/\/)?(?:[\w-]+\.)+[\w-]+(?:\/[\w-./?%&=]*)?)$/i;

          return pattern.test(value);
        }, settings.etl.validation.link_url_msg || 'Please enter a valid URL (e.g., google.com or https://example.com)');

        $form.validate({

          onkeyup: function (element, event) {
            if (event.which !== 16 && event.which !== 17 && event.which !== 18 && event.which !== 91 && event.which !== 93) {
              this.element(element);
            }
          },

          // Delay validation for date_popup fields so the calendar widget
          // has time to populate the value
          onfocusout: function (element) {
            var self = this;
            var name = $(element).attr('name') || '';
            if (name.indexOf('field_reminder_date') !== -1) {
              setTimeout(function () { self.element(element); }, 300);
              return;
            }
            this.element(element);
          },

          highlight: function (element) {
            $(element).closest('.form-item').addClass('error');
          },
          unhighlight: function (element) {
            $(element).closest('.form-item').removeClass('error');
          },

          errorElement: 'div',
          errorClass: 'form-item-description error',
          errorPlacement: function (error, element) {
            error.insertAfter(element);
          },

          rules: {
            title: {
              required: true,
              minlength: (settings.etl && settings.etl.validation && settings.etl.validation.title_minlength) || 3
            },

            field_reference_link: {
              looseUrl: true
            },

            'field_what_did_you_not_understan[und][0][value]': {
              required: true,
              minlength: 3
            },

            'field_reminder_date[und][0][date]': {
              required: true
            }
          },

          messages: {
            title: {
              required: settings.etl.validation.title_required_msg,
              minlength: settings.etl.validation.title_minlength_msg
            },
            field_reference_link: {
              looseUrl: settings.etl.validation.link_url_msg
            },
            'field_what_did_you_not_understan[und][0][value]': {
              required: settings.etl.validation.description_required_msg,
              minlength: settings.etl.validation.description_minlength_msg
            },
            'field_reminder_date[und][0][date]': {
              required: settings.etl.validation.date_future_msg
            }
          },

          submitHandler: function (form) {
            form.submit();
          }
        });

      });
    }
  };
})(jQuery);