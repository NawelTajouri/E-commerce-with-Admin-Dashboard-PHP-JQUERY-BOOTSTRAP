$(function () {
    
    'use strict';

    // Switch between Login And SIGNUP
    $('.login-page h1 span').click(function() {
        $(this).addClass('selected').siblings().removeClass('selected');
        $('.login-page form').hide();
        $('.' + $(this).data('class')).show();
      
        
    });


    //Trigger The SelectBoxIt
    $('select').selectBoxIt({
        autoWidth: false
    });

    //Hide Placeholder on Form Focus
    $('[placeholder]').focus(function() {
        $(this).attr('data-text', $(this).attr('placeholder')); // Get the content of placeholder attribute
        $(this).attr('placeholder', ''); //set placeholder attribute to null 
    }).blur(function() {
        $(this).attr('placeholder', $(this).attr('data-text')); // set (return) the content of placeholder attribute
    });

    //Add Asterisk On Required Fields (add *) 
    $('input').each(function() {
        if($(this).attr('required') === 'required') {
            $(this).after('<span class="asterisk">*</span>');
        }
    });

    //Confirmation Message On Button
    $('.confirm').click(function() {
        return confirm('Are You Sure!');
    });

  

    $('.live').keyup(function() {
       
        $($(this).data('class')).text($(this).val());
    });

});





