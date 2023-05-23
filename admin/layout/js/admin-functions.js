$(function () {
    
    'use strict';


    // Dashboard
    $('.toggle-info').click(function() {
        $(this).toggleClass('selected').parent().next('.panel-body').fadeToggle(200);
        if($(this).hasClass('selected')) {
            $(this).html('<i class="fa fa-sort-asc fa-lg"></i>')
        } else {
            $(this).html('<i class="fa fa-sort-desc fa-lg"></i>')

        }
    
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

    //Convert Password Field to Text Field On Hover
    var passField = $('.password');
    $('.show-pass').hover(function() {
        passField.attr('type', 'text');
    }, function() {
        passField.attr('type', 'password');

    });

    //Confirmation Message On Button
    $('.confirm').click(function() {
        return confirm('Are You Sure!');
    });


    // Category View Options
    $('.cat h3').click(function(){
        $(this).next('.full-view').fadeToggle(200);
    });

    $('.ordering span').click(function(){
        $(this).addClass('active').siblings('span').removeClass('active');
        if($(this).data('view') === 'full') {
            $('.cat .full-view').fadeIn(200);
        } else {
            $('.cat .full-view').fadeOut();
        }
    });

    // Show Delete Button On child Cats
    $('.child-link').hover(function() {
        $(this).find('.show-delete').fadeIn();
    }, function() {
        $(this).find('.show-delete').fadeOut();
    });
});


