(function ($) {
  $(document).ready(function() {
    //Trigger a click on stripe checkout automatically
    var done = false; //Prevent double submit (for some reason)
    if(!done) {
      $("button.stripe-button-el").trigger("click");
      done = true;
    }

    $('.mepr-signup-form, #mepr-stripe-payment-form').each(function () {
      new MeprStripeForm(this);
    });
  });

  /**
   * The MemberPress Stripe form class
   *
   * @constructor
   * @param {HTMLFormElement} form
   */
  function MeprStripeForm (form) {
    this.form = form;
    this.$form = $(form);
    this.isSpc = this.$form.hasClass('mepr-signup-form');
    this.paymentMethods = [];
    this.selectedPaymentMethod = null;
    this.submitting = false;

    this.initPaymentMethods();
    this.$form.on('submit', $.proxy(this.handleSubmit, this));
  }

  /**
   * Initialize Stripe elements
   */
  MeprStripeForm.prototype.initPaymentMethods = function () {
    var self = this;

    self.$form.find('.mepr-stripe-card-element').each(function () {
      var $cardElement = $(this),
        $cardErrors = $cardElement.closest('.mp-form-row').find('.mepr-stripe-card-errors'),
        stripe = Stripe($cardElement.data('stripe-public-key')),
        elements = stripe.elements(),
        card = elements.create('card', { style: MeprStripeGateway.style }),
        paymentMethodId = $cardElement.data('payment-method-id');

      card.mount($cardElement[0]);

      card.addEventListener('change', function (event) {
        $cardErrors.text(event.error ? event.error.message : '');
      });

      self.paymentMethods.push({
        id: paymentMethodId,
        stripe: stripe,
        card: card,
        $cardErrors: $cardErrors
      });
    });
  };

  /**
   * Handle the payment form submission
   *
   * @param {jQuery.Event} e
   */
  MeprStripeForm.prototype.handleSubmit = function (e) {
    var self = this;

    e.preventDefault();

    if (self.submitting) {
      return;
    }

    self.submitting = true;

    if (self.$form.find('.mepr-payment-methods-wrapper').is(':hidden')) {
      self.form.submit();
      return;
    }

    self.$form.find('.mepr-submit').prop('disabled', true);
    self.$form.find('.mepr-loading-gif').show();

    self.selectedPaymentMethod = self.getSelectedPaymentMethod();

    if (self.selectedPaymentMethod) {
      var cardData = {
        billing_details: self.getBillingDetails()
      };

      self.selectedPaymentMethod.stripe.createPaymentMethod('card', self.selectedPaymentMethod.card, cardData).then(function (result) {
        if (result.error) {
          self.handlePaymentError(result.error.message);
        } else {
          self.confirmPayment({
            payment_method_id: result.paymentMethod.id
          });
        }
      });
    } else {
      self.form.submit();
    }
  };

  /**
   * Get the currently selected payment method data
   *
   * @return {object|null}
   */
  MeprStripeForm.prototype.getSelectedPaymentMethod = function () {
    if (this.isSpc) {
      var paymentMethodId = this.$form.find('input[name="mepr_payment_method"]:checked').val();

      for (var i = 0; i < this.paymentMethods.length; i++) {
        if (this.paymentMethods[i].id === paymentMethodId) {
          return this.paymentMethods[i];
        }
      }

      return null;
    } else {
      return this.paymentMethods.length ? this.paymentMethods[0] : null;
    }
  };

  /**
   * Returns the form fields in a pretty key/value hash
   *
   * @return {object}
   */
  MeprStripeForm.prototype.getFormData = function () {
    return this.$form.serializeArray().reduce(function(obj, item) {
      obj[item.name] = item.value;
      return obj;
    }, {});
  };

  /**
   * Get the billing details object to pass to Stripe
   *
   * @return {object}
   */
  MeprStripeForm.prototype.getBillingDetails = function () {
    var formData = this.getFormData(),
      keys = {
        line1: this.isSpc ? 'mepr-address-one' : 'card-address-1',
        line2: this.isSpc ? 'mepr-address-two' : 'card-address-2',
        city: this.isSpc ? 'mepr-address-city' : 'card-city',
        country: this.isSpc ? 'mepr-address-country' : 'card-country',
        state: this.isSpc ? 'mepr-address-state' : 'card-state',
        postal_code: this.isSpc ? 'mepr-address-zip' : 'card-zip'
      },
      details = {
        address: {}
      };

    if (typeof formData['card-name'] == 'string' && formData['card-name'].length) {
      details.name = formData['card-name'];
    }

    for (var key in keys) {
      if (keys.hasOwnProperty(key)) {
        if (typeof formData[keys[key]] == 'string' && formData[keys[key]].length) {
          details.address[key] = formData[keys[key]];
        }
      }
    }

    return details;
  };

  /**
   * Allow the form to be submitted again
   */
  MeprStripeForm.prototype.allowResubmission = function () {
    this.submitting = false;
    this.$form.find('.mepr-submit').prop('disabled', false);
    this.$form.find('.mepr-loading-gif').hide();
    this.$form.find('.mepr-form-has-errors').show();
    this.$form.find('.mepr-validation-error, .mepr-top-error').remove();
  };

  /**
   * Handle form validation errors
   *
   * @param {array} errors The validation errors array
   */
  MeprStripeForm.prototype.handleValidationErrors = function (errors) {
    // Allow the form to be submitted again
    this.allowResubmission();

    var topErrors = [];

    for (var key in errors) {
      if (errors.hasOwnProperty(key)) {
        var $field = this.$form.find('[name="' + key + '"]').first(),
          $label = $field.closest('.mp-form-row').find('.mp-form-label');

        if ($.isNumeric(key) || !$label.length) {
          topErrors.push(errors[key]);
        } else {
          $label.append($('<span class="mepr-validation-error">').html(errors[key]));
        }

        console.log(errors[key]);
      }
    }

    if (topErrors.length) {
      var $list = $('<ul>'),
        $wrap = $('<div class="mepr-top-error mepr_error">');

      for (var i = 0; i < topErrors.length; i++) {
        $list.append($('<li>').html(MeprStripeGateway.top_error.replace('%s', topErrors[i])));
      }

      $wrap.append($list).prependTo(this.$form);
    }
  };

  /**
   * Handle an error with the payment
   *
   * @param {string} error The error message to display
   */
  MeprStripeForm.prototype.handlePaymentError = function (error) {
    // Allow the form to be submitted again
    this.allowResubmission();

    // Inform the user if there was an error
    this.selectedPaymentMethod.$cardErrors.text(error);
    console.log(error);
  };

  /**
   * Handle the response from our Ajax endpoint
   *
   * @param {object} response
   */
  MeprStripeForm.prototype.handleServerResponse = function (response) {
    if (response === null || typeof response != 'object') {
      this.handlePaymentError(MeprStripeGateway.invalid_response_error)
    } else {
      if (response.transaction_id) {
        this.$form.find('input[name="mepr_transaction_id"]').val(response.transaction_id);
      }

      if (response.errors) {
        this.handleValidationErrors(response.errors);
      } else if (response.error) {
        this.handlePaymentError(response.error);
      } else if (response.requires_action) {
        this.handleAction(response);
      } else if (!this.$form.hasClass('mepr-payment-submitted')) {
        this.$form.addClass('mepr-payment-submitted');
        this.form.submit();
      }
    }
  };

  /**
   * Displays the card action dialog to the user, and confirms the payment if successful
   *
   * @param {object} response
   */
  MeprStripeForm.prototype.handleAction = function (response) {
    var self = this,
      stripe = this.selectedPaymentMethod.stripe,
      card = this.selectedPaymentMethod.card,
      cardData;

    if (response.action === 'handleCardSetup') {
      cardData = {
        payment_method_data: {
          billing_details: self.getBillingDetails()
        }
      };

      stripe.handleCardSetup(response.client_secret, card, cardData).then(function (result) {
        if (result.error) {
          self.handlePaymentError(result.error.message);
        } else {
          self.confirmPayment({
            setup_intent_id: result.setupIntent.id
          });
        }
      });
    } else if (response.action === 'handleCardPayment') {
      cardData = {
        payment_method_data: {
          billing_details: self.getBillingDetails()
        }
      };

      stripe.handleCardPayment(response.client_secret, card, cardData).then(function (result) {
        if (result.error) {
          self.handlePaymentError(result.error.message);
        } else {
          self.confirmPayment({
            payment_intent_id: result.paymentIntent.id
          });
        }
      });
    } else {
      stripe.handleCardAction(response.client_secret).then(function (result) {
        if (result.error) {
          self.handlePaymentError(result.error.message);
        } else {
          self.confirmPayment({
            payment_intent_id: result.paymentIntent.id
          });
        }
      });
    }
  };

  /**
   * Confirm the payment with our Ajax endpoint
   *
   * @param {object} extraData Additional data to send with the request
   */
  MeprStripeForm.prototype.confirmPayment = function (extraData) {
    var self = this,
      data = self.getFormData();

    $.extend(data, extraData || {}, {
      action: 'mepr_stripe_confirm_payment',
      mepr_current_url: document.location.href
    });

    // We don't want to hit our routes for processing the signup or payment forms
    delete data.mepr_process_signup_form;
    delete data.mepr_process_payment_form;

    $.ajax({
      type: 'POST',
      url: MeprStripeGateway.ajax_url,
      dataType: 'json',
      data: data
    })
    .done($.proxy(self.handleServerResponse, self))
    .fail(function () {
      self.handlePaymentError(MeprStripeGateway.ajax_error);
    });
  }
})(jQuery);
