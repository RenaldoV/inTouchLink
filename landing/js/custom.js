/*
 TEMPLATE NAME: Sinewave - One Page Hosting Landing Page HTML Template
 TEMPLATE URI: - http://froid.works/themeforest/sinewave/resource/
 DESCRIPTION: Hosting HTML Template
 VERSION: 1.0
 AUTHOR: Ajay Kumar Choudhary
 AUTHOR URL: http://codecanyon.net/user/ajay138/
 DESIGN BY: Rakesh Kumar

 ##TABLE OF CONTENT##

 1. LOGIN, REGISTER & FORGOT PASSWORD FORM
 2. PAGE SCROLL ANIMATION
 3. COLLAPSE NAVBAR ON SCROLL
 4. GOOGLE MAPS
 5. CONTACT FORM
 6. SUBSCRIBE FORM
 7. WOW JS
 8. MOBILE MENU
 9. INPUT FIELD EFFECT

 */
(function () {
  'use strict';

  $(function () {
    /*(1) LOGIN, REGISTER & FORGOT PASSWORD FORM
     ========================================================================== */
    var loginDiv = $('#login');
    var forgetDiv = $('#forgetPassword');
    var signUpDiv = $('#signUp');
    var loginModal = $('#loginModal');

    var registerModel = function () {
         loginDiv.add(forgetDiv).hide();
         signUpDiv.fadeIn(700);
    }
   
    var LoginModel = function () {
        signUpDiv.add(forgetDiv).hide();
        loginDiv.fadeIn(700);
    }

    $('#forgotFormBtn').on("click", function () {
        signUpDiv.add(loginDiv).hide();
        forgetDiv.fadeIn(700).css({"margin-top": "30px"});
        return false;
    });

    $('.login-btn').on("click", function () {
      loginModal.modal('show');
      LoginModel();
      return false;
    });
    $('#modal-login-btn').on("click", function () {
      LoginModel();
      return false;
    });

    $('#signUpModal').on("click", function () {
      registerModel();
      return false
    });

    $('.sign-up-btn').on("click", function () {
      loginModal.modal('show');
      registerModel();
      return false
    });
    
    $('#formloginModal').on("click", function () {
      LoginModel();
      return false
    });

    /* PAGE SCROLL ANIMATION
     ========================================================================== */
    $(document).on('click', 'a.page-scroll', function (event) {
      var $anchor = $(this);
      $('html,body').stop().animate({
        scrollTop: $($anchor.attr('href')).offset().top
      }, 1500, 'easeInOutExpo');
      event.preventDefault();
    });

  });


  /*(2) COLLAPSE NAVBAR ON SCROLL
   ========================================================================== */
  $(window).on('scroll', function () {
    var navBarFixTop = $('.navbar-fixed-top');
    if ($('.navbar').offset().top > 50) {
      navBarFixTop.addClass('top-nav-collapse').css('background', '#fff');
    } else {
      navBarFixTop.removeClass('top-nav-collapse').css('background', '#fff');
    }
  });





  /*(5) CONTACT FORM
   ========================================================================== */
  $('#contact-submit').on("click", function () {
    var un = $('#fullName').val();
    var em = $('#email').val();
    var msg = $('#message').val();
    var business = $('#businessName').val();
    var support = "";
    var existing = "";
    
    
    if ($('#support').is(":checked")) {
    
    support = "on";
    
    } else {
    
    support = "off";
    
    }

		if ($('#existingCustomer').is(":checked")) {
    
    existing = "on";
    
    } else {
    
    existing = "off";
    
    }
    
    $.ajax({
      type: "POST",
      url: "landing/ajaxmail.php",
      data: {
        'username': un,
        'email': em,
        'msg': msg,
        'business': business,
        'support': support,
        'existing': existing,
      },
      success: function (message) {
        var response = JSON.parse(message);
        if (response.status === 'success') {
          $('.contact-form')[0].reset();
        }
        $('#error_contact').html(response.message);
      }
    });
  });

  /*(6) SUBSCRIBE FORM
   ========================================================================== */
  $('#subscribe-submit').on("click", function () {
    var subscribeMail = $('#subscribe-email');
    var em = subscribeMail.val();
    $.ajax({
      type: "POST",
      url: "landing/subscribemail.php",
      data: {
        'email': em,
      },
      success: function (message) {
        subscribeMail.removeClass('subscriber-success');
        subscribeMail.removeClass('subscriber-error');
        var response = JSON.parse(message);
        if (response.status === 'success') {
          subscribeMail.addClass('subscriber-success');

        } else {
          subscribeMail.addClass('subscriber-error');
        }
        subscribeMail.attr("placeholder", response.message);
        subscribeMail.val("");
      }
    });
  });


  /*(7) WOW JS
   ========================================================================== */

  var wow = new WOW(
    {
      boxClass: 'wow',      // animated element css class (default is wow)
      animateClass: 'animated', // animation css class (default is animated)
      offset: 10,          // distance to the element when triggering the animation (default is 0)
      mobile: true,       // trigger animations on mobile devices (default is true)
      live: true,
      // act on asynchronously loaded content (default is true)
      callback: function (box) {
        // the callback is fired every time an animation is started
        // the argument that is passed in is the DOM node being animated
      },
      scrollContainer: null // optional scroll container selector, otherwise use window
    }
  );
  wow.init();


  /*(8) MOBILE MENU
   ========================================================================== */

  if ('ontouchstart' in window) {
    var click = 'touchstart';
  }
  else {
    var click = 'click';
  }

  var burgerDiv = 'div.burger';
  var xDiv = 'div.x';
  var yDiv = 'div.y';
  var zDiv = 'div.z';
  var menuBg = 'div.menu-bg';
  var menuLi = '.menu li';
  var screen = '.screen';

  $(burgerDiv).on(click, function () {
    if (!$(this).hasClass('open')) {
      openMenu();
    }
    else {
      closeMenu();
    }
  });


  $('div.menu ul li a').on(click, function () {
    closeMenu();
  });

  function openMenu() {
    $(`${menuBg},${menuLi}`).addClass('animate');
    $(burgerDiv).addClass('open');
    $(`${xDiv}, ${zDiv}`).addClass('collapse');
    $(screen).show();

    setTimeout(function () {
      $(yDiv).hide();
      $(xDiv).addClass('rotate30');
      $(zDiv).addClass('rotate150');
    }, 70);
    setTimeout(function () {
      $(xDiv).addClass('rotate45');
      $(zDiv).addClass('rotate135');
    }, 120);

  }

  function closeMenu() {
    $(menuLi).removeClass('animate');
    setTimeout(function () {
      $(burgerDiv).removeClass('open');
      $(xDiv).removeClass('rotate45').addClass('rotate30');
      $(zDiv).removeClass('rotate135').addClass('rotate150');
      $(menuBg).removeClass('animate');
      $(screen).hide();

      setTimeout(function () {
        $(xDiv).removeClass('rotate30');
        $(zDiv).removeClass('rotate150');
      }, 50);
      setTimeout(function () {
        $(yDiv).show();
        $(`${xDiv},${zDiv}`).removeClass('collapse');
      }, 70);
    }, 100);

  }

  /*(9) INPUT FIELD EFFECT
   ========================================================================== */
  if (!String.prototype.trim) {
    (function () {
      // Make sure we trim BOM and NBSP
      var rtrim = /^[\s\uFEFF\xA0]+|[\s\uFEFF\xA0]+$/g;
      String.prototype.trim = function () {
        return this.replace(rtrim, '');
      };
    })();
  }
  var inputFilled = 'input--filled';

  [].slice.call(document.querySelectorAll('input.input__field')).forEach(function (inputEl) {
    // in case the input is already filled..
    if (inputEl.value.trim() !== '') {
      classie.add(inputEl.parentNode, inputFilled);
    }

    // events:
    inputEl.addEventListener('focus', onInputFocus);
    inputEl.addEventListener('blur', onInputBlur);
  });

  [].slice.call(document.querySelectorAll('textarea.input__field')).forEach(function (inputEl) {
    // in case the input is already filled..
    if (inputEl.value.trim() !== '') {
      classie.add(inputEl.parentNode, inputFilled);
    }

    // events:
    inputEl.addEventListener('focus', onInputFocus);
    inputEl.addEventListener('blur', onInputBlur);
  });


  function onInputFocus(ev) {
    classie.add(ev.target.parentNode, inputFilled);
  }

  function onInputBlur(ev) {
    if (ev.target.value.trim() === '') {
      classie.remove(ev.target.parentNode, inputFilled);
    }
  }


})();