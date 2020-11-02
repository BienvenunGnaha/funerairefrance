$(function(){

    var i = 0;

    var sub = [];

    $('.menu-link').mouseover(function () {

        var height = $(this).outerHeight();

        var top = $(this).offset().top;

        var selector = $(this).attr('data-target');

        $(selector).offset({left: 0, top: 0});

        console.log(selector);

        if(i === 0){

            console.log($(selector).offset());

            $(selector).offset({left: $(selector).offset().left, top: height+top});

            

            $(selector).show();

            sub.push(selector);

            i++;

        }else{

            if ($.inArray(selector, sub) === -1){



                $(selector).offset({left: $(selector).offset().left, top: height+top});

                console.log($(selector).offset());

                $(selector).show();

                sub.push(selector);

            }else{

                $(selector).offset({left: 0, top: 0});

                console.log($(selector).offset());

                $(selector).show();

            }



        }



    });



    $('.frfu-submenu').mouseenter(function () {

        $(this).show();

    })



    $('.frfu-submenu').mouseover(function () {

        $(this).show();

    })

    $('.frfu-submenu').mouseleave(function () {

        $(this).hide();

    })



    $('.menu-link').mouseleave(function () {

        var selector = $(this).attr('data-target');

        $(selector).hide();

        $(selector).offset({left: 0, top: 0});

    });

    /*$(window).scroll(function(){
        if($('.frfu-menu-top').offset().top === 0){
            console.log($('.frfu-menu-top'));
            $('.frfu-menu-top').css({'position' : 'fixed'});
        }
        
    });*/

});