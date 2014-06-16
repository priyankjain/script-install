/*
 * Dracon CAPTCHA 2.1 Effects
 * http://www.dracon.biz/
 *
 * Copyright (c) 2009 dracon.biz
 * Licensed under the Dracon license.
 * http://www.dracon.biz/license.php
 */
 /*
$(window).bind('load', function () {
  
  // onload fadein
  $('#dracon_captcha_test').fadeIn(1000);
  
  // image overfx
  $('div.fade').hover(function() {
    $(this).css('cursor', 'pointer');
    if ($(this).children('img').not(':animated')) { $(this).children('img').fadeIn(200) }
    $(this).children('img').animate({ opacity:1 }, 200)
  }, function() {
    $(this).children('img').stop().animate({ opacity:0 }, 400);
  })
  
  // define links
  $('#title').click(function(){ window.open('http://www.dracon.biz/captcha.php') });
  $('#submit').click(function(){ form_submit() });
  $('#reset').click(function(){ form_reset() });
  
  // delay plugin
  $.fn.delay = function(time, callback){
    var $time = this;
    setTimeout(function(){ callback.call($time) }, time);
    return $time;
  }
  
});
*/
// reset form 
function form_reset() {
  $('form')[0].reset(); 
}

// submit form
function form_submit() {

  // check inputs
  var speed = 0; 
  var goNext = true;
  $('#main_form :input').each(function() {
    speed += 50;
    if (this.value) $('#check_'+this.id).html('<img src="/templates/default/images/ok_icon.png" width="28" style="display:none">')
    else {
      $('#check_'+this.id).html('<img src="/templates/default/images/error_icon.png" width="28" style="display:none">')
      if (goNext) { goNext = false; this.focus(); }
    }
    $('#check_'+this.id).children('img').delay(speed, function(){
      this.fadeIn(500);
    });
  });
  
  // show captcha
  if (goNext) {
    $('#main_form').delay(500, function(){
      $(this).fadeOut(1000, function() {
        $('#loader').fadeIn(500, function() {
          $('#loader').fadeOut(500, function() {
            // reset submit buttons
            $('#submit').unbind("click").click(function(){ captcha_submit() });
            $('#reset').unbind("click").click(function(){ $('#code').attr('value', '').focus() });
            // attach captcha input & nfo
            $('#captcha_sub').clone().appendTo('#captcha');
            $('#captcha').fadeIn(500, function() {
              $('#captcha_sub').fadeIn(500, function() {
                $('#code').focus();
              });
            });
          });
        });
      });
    });
  }
  
}

// submit captcha
function captcha_submit() {
  
  // check input
  if (!$('#code').attr('value')) {
    $('#code').attr('value', '???')
    $('#code').select();
    $('#code').focus();
    $('#captcha_nfo1').fadeIn(100, function() {
      $('#captcha_nfo2').fadeIn(100);
    })
    return;
  }
  
  // submit captcha
  $('#captcha').fadeOut(500, function() {
    $('#loader').fadeIn(500, function() {
      $('#captcha').load('/templates/default/CodeGen.php?ajax', { secCode:$('#code').attr('value') }, function(jsCheck){
        $('#loader').fadeOut(500, function() {
          if (jsCheck == 'ok') {
            // everything ok, submit the form
            final_submit();
          }
          else if (jsCheck.indexOf('timer:') != -1) {
            // hide submit buttons
            $('#submit').fadeOut(300, function() {
              $('#reset').fadeOut(300);
            });
            // display countdown
            var jsCounter = jsCheck.substring(6);
            $('#counter :span').html(jsCounter);
            $('#counter').fadeIn(300);
            // timer start
            var jsInterval = setInterval( function() {
              jsCounter--;
              if (jsCounter == 0) {
                clearInterval(jsInterval);
                // show captcha
                $('#counter').fadeOut(300);
                $('#loader').fadeIn(500, function() {
                  $('#captcha').load('/templates/default/CodeGen.php?ajax', { secCode:'reload' }, function(jsCheck){
                    $('#loader').fadeOut(500, function() {
                      // attach captcha input & nfo
                      $('#captcha_sub').clone().appendTo('#captcha');
                      $('#captcha').fadeIn(500, function() {
                        $('#captcha_sub').fadeIn(500, function() {
                          $('#code').focus();
                          $('#submit').fadeIn(300);
                          $('#reset').fadeIn(300);
                        });
                      });                      
                    });
                  });
                });
              }
              $('#counter :span').html(jsCounter);
            },1000); 
          }
          else if (jsCheck == 'hack') {
            // hacking attempt
            $('#captcha').html('<span class="sec_code">Error, hacking attempt detected!</span>');
            $('#captcha').fadeIn(500);            
            // hide submit buttons
            $('#submit').fadeOut(300, function() {
              $('#reset').fadeOut(300);
            });            
          }
          else {
            // reattach captcha input & nfo
            $('#captcha_sub').clone().appendTo('#captcha');
            $('#captcha').fadeIn(500, function() {
              $('#captcha_sub').fadeIn(500, function() {
                $('#code').focus();
              });
            });
          }
        });
      });
    });
  });

}

// final submit
function final_submit() {

  $('#captcha').load('?final', { name:$('#name').attr('value'), email:$('#email').attr('value'), message:$('#message').attr('value') }, function(){
    $('#captcha').fadeIn(500, function() {
      $('#submit').fadeOut(300, function() {
        $('#reset').unbind("click").click(function(){ 
          form_reset(); 
          $('#code').attr('value', '');
          document.location = '/';
        });
      });
    });
  });
  
} 
