/*
    A simple jQuery modal (http://github.com/kylefox/jquery-modal)
    Version 0.8.0
*/
(function (factory) {
// console.log('modal definido')
  // Making your jQuery plugin work better with npm tools
  // http://blog.npmjs.org/post/112712169830/making-your-jquery-plugin-work-better-with-npm
  if(typeof module === "object" && typeof module.exports === "object") {
    factory(require("jquery"), window, document);
  }
  else {
    factory(jQuery, window, document);
  }
}(function($, window, document, undefined) {

  var acmodals = [],
      getCurrent = function() {
        return acmodals.length ? acmodals[acmodals.length - 1] : null;
      },
      selectCurrent = function() {
        var i,
            selected = false;
        for (i=acmodals.length-1; i>=0; i--) {
          if (acmodals[i].$blocker) {
            acmodals[i].$blocker.toggleClass('current',!selected).toggleClass('behind',selected);
            selected = true;
          }
        }
      };

  $.acmodal = function(el, options) {
    var remove, target;
    this.$body = $('body');
    this.options = $.extend({}, $.acmodal.defaults, options);
    this.options.doFade = !isNaN(parseInt(this.options.fadeDuration, 10));
    this.$blocker = null;
    if (this.options.closeExisting)
      while ($.acmodal.isActive())
        $.acmodal.close(); // Close any open acmodals.
    acmodals.push(this);
    if (el.is('a')) {
      target = el.attr('href');
      //Select element by id from href
      if (/^#/.test(target)) {
        this.$elm = $(target);
        if (this.$elm.length !== 1) return null;
        this.$body.append(this.$elm);
        this.open();
      //AJAX
      } else {
        this.$elm = $('<div>');
        this.$body.append(this.$elm);
        remove = function(event, modal) { modal.elm.remove(); };
        this.showSpinner();
        el.trigger($.acmodal.AJAX_SEND);
        $.get(target).done(function(html) {
          if (!$.acmodal.isActive()) return;
          el.trigger($.acmodal.AJAX_SUCCESS);
          var current = getCurrent();
          current.$elm.empty().append(html).on($.acmodal.CLOSE, remove);
          current.hideSpinner();
          current.open();
          el.trigger($.acmodal.AJAX_COMPLETE);
        }).fail(function() {
          el.trigger($.acmodal.AJAX_FAIL);
          var current = getCurrent();
          current.hideSpinner();
          acmodals.pop(); // remove expected modal from the list
          el.trigger($.acmodal.AJAX_COMPLETE);
        });
      }
    } else {
      this.$elm = el;
      this.$body.append(this.$elm);
      this.open();
    }
  };

  $.acmodal.prototype = {
    constructor: $.acmodal,

    open: function() {
      var m = this;
      this.block();
      if(this.options.doFade) {
        setTimeout(function() {
          m.show();
        }, this.options.fadeDuration * this.options.fadeDelay);
      } else {
        this.show();
      }
      $(document).off('keydown.acmodal').on('keydown.acmodal', function(event) {
        var current = getCurrent();
        if (event.which == 27 && current.options.escapeClose) current.close();
      });
      if (this.options.clickClose)
        this.$blocker.click(function(e) {
          if (e.target==this)
            $.acmodal.close();
        });
    },

    close: function() {
      acmodals.pop();
      this.unblock();
      this.hide();
      if (!$.acmodal.isActive())
        $(document).off('keydown.acmodal');
    },

    block: function() {
      this.$elm.trigger($.acmodal.BEFORE_BLOCK, [this._ctx()]);
      this.$body.css('overflow','hidden');
      this.$blocker = $('<div class="jquery-acmodal ac-blocker current"></div>').appendTo(this.$body);
      selectCurrent();
      if(this.options.doFade) {
        this.$blocker.css('opacity',0).animate({opacity: 1}, this.options.fadeDuration);
      }
      this.$elm.trigger($.acmodal.BLOCK, [this._ctx()]);
    },

    unblock: function(now) {
      if (!now && this.options.doFade)
        this.$blocker.fadeOut(this.options.fadeDuration, this.unblock.bind(this,true));
      else {
        this.$blocker.children().appendTo(this.$body);
        this.$blocker.remove();
        this.$blocker = null;
        selectCurrent();
        if (!$.acmodal.isActive())
          this.$body.css('overflow','');
      }
    },

    show: function() {
      this.$elm.trigger($.acmodal.BEFORE_OPEN, [this._ctx()]);
      if (this.options.showClose) {
        this.closeButton = $('<a href="#ac-close-modal" rel="acmodal:close" class="ac-close-modal ' + this.options.closeClass + '">' + this.options.closeText + '</a>');
        this.$elm.append(this.closeButton);
      }
      this.$elm.addClass(this.options.modalClass).appendTo(this.$blocker);
      if(this.options.doFade) {
        this.$elm.css('opacity',0).show().animate({opacity: 1}, this.options.fadeDuration);
      } else {
        this.$elm.show();
      }
      this.$elm.trigger($.acmodal.OPEN, [this._ctx()]);
    },

    hide: function() {
      this.$elm.trigger($.acmodal.BEFORE_CLOSE, [this._ctx()]);
      if (this.closeButton) this.closeButton.remove();
      var _this = this;
      if(this.options.doFade) {
        this.$elm.fadeOut(this.options.fadeDuration, function () {
          _this.$elm.trigger($.acmodal.AFTER_CLOSE, [_this._ctx()]);
        });
      } else {
        this.$elm.hide(0, function () {
          _this.$elm.trigger($.acmodal.AFTER_CLOSE, [_this._ctx()]);
        });
      }
      this.$elm.trigger($.acmodal.CLOSE, [this._ctx()]);
    },

    showSpinner: function() {
      if (!this.options.showSpinner) return;
      this.spinner = this.spinner || $('<div class="' + this.options.modalClass + '-spinner"></div>')
        .append(this.options.spinnerHtml);
      this.$body.append(this.spinner);
      this.spinner.show();
    },

    hideSpinner: function() {
      if (this.spinner) this.spinner.remove();
    },

    //Return context for custom events
    _ctx: function() {
      return { elm: this.$elm, $blocker: this.$blocker, options: this.options };
    }
  };

  $.acmodal.close = function(event) {
    if (!$.acmodal.isActive()) return;
    if (event) event.preventDefault();
    var current = getCurrent();
    current.close();
    return current.$elm;
  };

  // Returns if there currently is an active modal
  $.acmodal.isActive = function () {
    return acmodals.length > 0;
  }

  $.acmodal.getCurrent = getCurrent;

  $.acmodal.defaults = {
    closeExisting: true,
    escapeClose: true,
    clickClose: true,
    closeText: 'Close',
    closeClass: '',
    modalClass: "ac-modal",
    spinnerHtml: null,
    showSpinner: true,
    showClose: true,
    fadeDuration: null,   // Number of milliseconds the fade animation takes.
    fadeDelay: 1.0        // Point during the overlay's fade-in that the modal begins to fade in (.5 = 50%, 1.5 = 150%, etc.)
  };

  // Event constants
  $.acmodal.BEFORE_BLOCK = 'acmodal:before-block';
  $.acmodal.BLOCK = 'acmodal:block';
  $.acmodal.BEFORE_OPEN = 'acmodal:before-open';
  $.acmodal.OPEN = 'acmodal:open';
  $.acmodal.BEFORE_CLOSE = 'acmodal:before-close';
  $.acmodal.CLOSE = 'acmodal:close';
  $.acmodal.AFTER_CLOSE = 'acmodal:after-close';
  $.acmodal.AJAX_SEND = 'acmodal:ajax:send';
  $.acmodal.AJAX_SUCCESS = 'acmodal:ajax:success';
  $.acmodal.AJAX_FAIL = 'acmodal:ajax:fail';
  $.acmodal.AJAX_COMPLETE = 'acmodal:ajax:complete';

  $.fn.acmodal = function(options){
    if (this.length === 1) {
      new $.acmodal(this, options);
    }
    return this;
  };

  // Automatically bind links with rel="acmodal:close" to, well, close the modal.
  $(document).on('click.acmodal', 'a[rel="acmodal:close"]', $.acmodal.close);
  $(document).on('click.acmodal', 'a[rel="acmodal:open"]', function(event) {
    event.preventDefault();
    $(this).acmodal();
  });
}));
