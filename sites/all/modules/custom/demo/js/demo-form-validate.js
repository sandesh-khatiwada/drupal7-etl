(function ($) {

  Drupal.behaviors.demoFormValidate = {
    attach: function (context, settings) {

      let $form = $('#demo-item-form', context);
      if (!$form.length) {
        console.warn('[Demo Validate] #demo-item-form not found – trying fallback');
        $form = $('form input[name="title"]').closest('form');
      }

      if (!$form.length) {
        console.error('[Demo Validate] No form found. Validation skipped.');
        return;
      }

      console.log('[Demo Validate] Form detected → ID:', $form.attr('id'));

      $form.once('demo-validate', function () {

        console.log('[Demo Validate] Starting $.validate() initialization');

        $form.validate({

       onkeyup: function (element, event) {
        // Validate on every keyup except pure modifiers (shift, ctrl, alt)
        if (event.which !== 16 && event.which !== 17 && event.which !== 18 && event.which !== 91 && event.which !== 93) {
             this.element(element);
            }
        },

          onfocusout: function (element) {
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
              minlength: (settings.demo && settings.demo.validation && settings.demo.validation.title_minlength) || 3
            },
            weight: {
              required: true,
              number: true
            }
          },

          messages: {
            title: {
              required: (settings.demo && settings.demo.validation && settings.demo.validation.title_required_msg) || 'Title is required.',
              minlength: (settings.demo && settings.demo.validation && settings.demo.validation.title_minlength_msg) || 'Title must be at least 3 characters long.'
            },
            weight: {
              required: (settings.demo && settings.demo.validation && settings.demo.validation.weight_required_msg) || 'Weight is required.',
              number: (settings.demo && settings.demo.validation && settings.demo.validation.weight_number_msg) || 'Weight must be a valid number.'
            }
          },

          submitHandler: function (form) {
            console.log('[Demo Validate] Valid → submitting');
            form.submit();
          }

        });

        console.log('[Demo Validate] Validation plugin initialized');

      });
    }
  };

})(jQuery);