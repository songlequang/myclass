jQuery(document).ready(function($){
    $(".live-edit").liveEdit({
        afterSaveAll: function(params) {
          return false;
        }
    });

    $('#course-category-select').change(function(event){

    	if($(this).val() === 'new'){
    		$('#new_course_category').addClass('animate cssanim fadeIn load');
    	}else{
            $('#new_course_category').removeClass('animate cssanim fadeIn load');
        }

        if($.isNumeric($(this).val())){
            $('.course-category-language-css').show();
        }
    });

    $('#course-linkage-select').change(function(event){

      if($(this).val() === 'add_new'){
        $('#new_course_linkage').addClass('animate cssanim fadeIn load');
      }else{
            $('#new_course_linkage').removeClass('animate cssanim fadeIn load');
            $('#save_course_action').addClass('reload_page');
        }
    });

$('body').delegate('*[data-help-tag]','click',function(event){

     var n=parseInt($(this).attr('data-help-tag'));
     n--;
    $('.course-create-help li').removeClass('active');
    $('.course-create-help li:eq('+n+')').addClass('active');
});


$('#course_creation_tabs > ul > li').click(function(event){

    if($(this).parent().hasClass('islive')){
        var n = $(this).index();
        $('.islive li.active').removeClass('active');
        $(this).addClass('active');
        $('.create_course_content > div').removeClass('active');
        $('.course-create-help > ul').removeClass('active');
        switch(n){
            case 0:
                $('#create_course').addClass('active');
                $('#create_course_help').addClass('active');
                $('#create_course_help li:first-child').addClass('active');
                break;
            case 1: $('#goal-course').addClass('active');
                $('#goal_course_help').addClass('active');
                /* $('#course_settings_help').addClass('active');*/
                /*$('#course_settings_help li:first-child').addClass('active');*/
                break;
            case 2: $('#course_settings').addClass('active');
                $('#course_settings_help').addClass('active');
                $('#course_settings_help li:first-child').addClass('active');
                break;
            case 3: $('#course_curriculum').addClass('active');
                $('#course_curriculum_help').addClass('active');
                $('#course_curriculum_help li:first-child').addClass('active');
                break;
            case 4: $('#course_pricing').addClass('active');
                $('#course_pricing_help').addClass('active');
                $('#course_pricing_help li:first-child').addClass('active');
                break;
            case 5: $('#course_live').addClass('active');
                $('#course_live_help').addClass('active');
                $('#course_live_help li:first-child').addClass('active');
                break;
        }
    }
});

// Uploading files
var media_uploader;
jQuery('.upload_image_button').on('click', function( event ){

    var button = jQuery( this );
    if ( media_uploader ) {
      media_uploader.open();
      return;
    }
    // Create the media uploader.
    media_uploader = wp.media.frames.media_uploader = wp.media({
        title: button.data( 'uploader-title' ),
        // Tell the modal to show only images.
        library: {
            type: 'image',
            query: false
        },
        button: {
            text: button.data( 'uploader-button-text' ),
        },
        multiple: button.data( 'uploader-allow-multiple' )
    });

    // Create a callback when the uploader is called
    media_uploader.on( 'select', function() {
        var selection = media_uploader.state().get('selection'),
            input_name = button.data( 'input-name' );
            selection.map( function( attachment ) {
            attachment = attachment.toJSON();
            console.log(attachment);
            button.html('<img src="'+attachment.sizes.thumbnail.url+'" width="'+attachment.sizes.thumbnail.width+'" height="'+attachment.sizes.thumbnail.height+'" class="submission_thumb thumbnail" /><input id="'+input_name+'" name="'+input_name+'" type="hidden" value="'+attachment.id+'" />');
         });

    });
    // Open the uploader
    media_uploader.open();
  });

var media_uploader1;
jQuery('.upload_badge_button').on('click', function( event ){
    var button = jQuery( this );
    if ( media_uploader1 ) {
      media_uploader1.open();
      return;
    }
    // Create the media uploader.
    media_uploader1 = wp.media.frames.media_uploader = wp.media({
        title: button.data( 'uploader-title' ),
        // Tell the modal to show only images.
        library: {
            type: 'image',
            query: false
        },
        button: {
            text: button.data( 'uploader-button-text' ),
        },
        multiple: button.data( 'uploader-allow-multiple' )
    });

    // Create a callback when the uploader is called
    media_uploader1.on( 'select', function() {
        var selection = media_uploader1.state().get('selection'),
            input_name = button.data( 'input-name' );
            selection.map( function( attachment ) {
            attachment = attachment.toJSON();
            button.html('<img src="'+attachment.sizes.full.url+'" width="'+attachment.sizes.full.width+'" height="'+attachment.sizes.full.height+'" class="submission_thumb thumbnail" /><input id="'+input_name+'" name="'+input_name+'" type="hidden" value="'+attachment.id+'" />');
         });

    });
    // Open the uploader
    media_uploader1.open();
  });
  $('#course-title').click(function(){
    var defaulttext = $(this).attr('data-default');
    var $cthis = $(this);
    if($cthis.html() == defaulttext){
        $cthis.html('');
         $('html').one('click',function() {
            if($cthis.html().length < 1){
                $cthis.html(defaulttext);
            }
          });
          event.stopPropagation();
    }
  });

    var count_linkage = 0;
  $('#create_course_action').click(function(event){
      if($('h1#course-title').text()=="Nhập vào tiêu đề khóa học")
      {
          alert("Hãy nhập vào tiêu đề khóa học");
          return;
      }

      var linkVideo = $('.video_trailer').text();
      var checkLinkFaceBook = linkVideo.indexOf('www.facebook.com');
      var checkLinkYoutube = linkVideo.indexOf('www.youtube.com');
      var video = '';
      if(checkLinkFaceBook <=0){

      }else{
          video = catlinkvideofacebook(linkVideo);
      }

      if(checkLinkYoutube <=0){

      }else{
          video = catlinkvideoyoutube(linkVideo);
      }

        var coursetitle=$('#course-title').text();
        var coursecat=$('#course-category-select').val();
        var newcoursecat=$('#new_course_category').text();
        var courselanguage=$('#course-category-select-language').val();
        var featuredimage=$('#course-image').val();
        var course_desc= '[iframevideo]'+video+'[/iframevideo]' + $('#course_short_description').text();
        var course_desc_ex = $('#course_short_description').text();
        var courselinkage = '';
        var newcourselinkage = '';

          if(!$.isNumeric(coursecat)){
              alert("Hãy chọn loại khóa học");
              return;
          }
          if(!$.isNumeric(courselanguage)){
              alert("Hãy chọn ngôn ngữ khóa học");
              return;
          }

        if(coursetitle == '' || /ENTER A COURSE TITLE/i.test(coursetitle)){
            alert(wplms_front_end_messages.course_title);
            return;
        }
        if($('#course-linkage-select').length){
          //courselinkage = $('#course-linkage-select').val();
            courselinkage = coursetitle + count_linkage; // phát sinh linkage tự động
        }

        if($('#new_course_linkage').length){
          //newcourselinkage = $('#new_course_linkage').text();
            newcourselinkage = coursetitle + count_linkage; // phát sinh linkage tự động
        }

        var $this = $(this);
        var defaulttxt = $this.html();
        $this.prepend('<i class="icon-sun-stroke animated spin"></i>');
        $this.addClass('disabled');
        $.confirm({
          text: wplms_front_end_messages.create_course_confirm,
          confirm: function() {
             $.ajax({
                    type: "POST",
                    url: ajaxurl,
                    data: { action: 'taokhoahoc',
                            security: $('#security').val(),
                            title: coursetitle,
                            category: coursecat,
                            newcategory: newcoursecat,
                            thumbnail: featuredimage,
                            description : course_desc,
                            descriptionex : course_desc_ex,
                            courselinkage:courselinkage,
                            newcourselinkage:newcourselinkage,
                            category_language: courselanguage
                          },
                    cache: false,
                    success: function (html) {
                        window.location.replace("?action=" + html + "&check_create=true"); // Tự động load lại trang khi thêm thành công
                        $this.find('i').remove();
                        $this.removeClass('disabled');
                        if($.isNumeric(html)){
                            var active=$('#course_creation_tabs li.active');
                            active.removeClass('active');
                            $('#create_course').removeClass('active');
                            //$('#create_course_help').removeClass('active');
                            //active.addClass('done');
                            //$('#course_creation_tabs li.done').next().addClass('active');
                            //$('#course_settings').addClass('active');
                            //$('#course_settings_help').addClass('active');
                            //$('#security').after('<input type="hidden" id="course_id" value="'+html+'" />');
                            //$('#course_creation_tabs ul').addClass('islive');
                        }else{
                            $this.html(html);
                            setTimeout(function(){$this.html(defaulttxt);}, 2000);
                        }
                    }
            });
          },
          cancel: function() {
              $this.find('i').remove();
              $this.removeClass('disabled');
          },
          confirmButton: wplms_front_end_messages.create_course_confirm_button,
          cancelButton: vibe_course_module_strings.cancel
      });
  });

    $('#save_course_action').click(function(event){
        if($('h1#course-title').text()=="Nhập vào tiêu đề khóa học")
        {
            alert("Hãy nhập vào tiêu đề khóa học");
            return;
        }

        var linkVideo = $('.video_trailer').text();
        var checkLinkFaceBook = linkVideo.indexOf('www.facebook.com');
        var checkLinkYoutube = linkVideo.indexOf('www.youtube.com');
        var video = '';
        if(checkLinkFaceBook <=0){

        }else{
            video = catlinkvideofacebook(linkVideo);
        }

        if(checkLinkYoutube <=0){

        }else{
            video = catlinkvideoyoutube(linkVideo);
        }

        var ID=$('#course_id').val();
        var coursetitle=$('#course-title').text();
        var coursecat=$('#course-category-select').val();
        var newcoursecat=$('#new_course_category').text();
        var featuredimage=$('#course-image').val();
        var course_desc= '[iframevideo]'+video+'[/iframevideo]' +$('#course_short_description').text();
        var status=$('#vibe_course_status:checked').val();
        var courselinkage = '';
        var newcourselinkage = '';
        var course_desc_ex = $('#course_short_description').text();
        if($('#course-linkage-select').length){
            courselinkage = $('#course-linkage-select').val();
        }
        if($('#new_course_linkage').length){
            newcourselinkage = $('#new_course_linkage').text();
        }
        var $this = $(this);
        var defaulttxt = $this.html();
        $this.prepend('<i class="icon-sun-stroke animated spin"></i>');
        $this.addClass('disabled');
        $.confirm({
            text: wplms_front_end_messages.save_course_confrim,
            confirm: function() {
                $.ajax({
                    type: "POST",
                    url: ajaxurl,
                    data: { action: 'chinhsuakhoahoc',
                        security: $('#security').val(),
                        ID: ID,
                        status:status,
                        title: coursetitle,
                        category: coursecat,
                        newcategory: newcoursecat,
                        thumbnail: featuredimage,
                        description : course_desc,
                        courselinkage:courselinkage,
                        newcourselinkage:newcourselinkage,
                        descriptionex : course_desc_ex,
                    },
                    cache: false,
                    success: function (html) {
                        $this.find('i').remove();
                        $this.removeClass('disabled');
                        if($.isNumeric(html)){
                            if($this.hasClass('reload_page')){
                                location.reload();
                            }else{
                                var active=$('#course_creation_tabs li.active');
                                active.removeClass('active');
                                $('#create_course').removeClass('active');
                                $('#create_course_help').removeClass('active');
                                active.addClass('done');
                                $('#course_creation_tabs li.done').next().addClass('active');
                                $('#goal-course').addClass('active');
                                $('#goal_course_help').addClass('active');
                            }
                        }else{
                            $this.html(html);
                            setTimeout(function(){$this.html(defaulttxt);}, 5000);
                        }
                    }
                });
            },
            cancel: function() {
                $this.find('i').remove();
            },
            confirmButton: wplms_front_end_messages.save_course_confrim_button,
            cancelButton: vibe_course_module_strings.cancel
        });
    });


    jQuery('body').delegate('.switch-label','click',function(event){

        var parent=$(this).parent();
        var hidden=$(this).parent().parent().parent().next();
        var checkvalue=parent.find('.switch-input:checked').val();

        if(checkvalue == 'H'){ // jQuery records the previous known value
            hidden.fadeIn(200);
        }else{
            hidden.fadeOut(200);
        }
    });
    jQuery('body').delegate('.switch-subscription','click',function(event){

        var parent=$(this).parent();
        var hidden=$('.product_duration');
        var checkvalue=parent.find('.switch-input:checked').val();
        if(checkvalue == 'S'){ // jQuery records the previous known value
            hidden.fadeIn(200);
        }else{
            hidden.fadeOut(200);
        }
    });

  $('body').delegate('#save_course_settings','click',function(event){


    var course_id=$('#course_id').val();
    //var vibe_course_auto_eval=$('.vibe_course_auto_eval:checked').val();
    var vibe_course_auto_eval = "S";
    var vibe_pre_course = $('#vibe_pre_course').val();
    var vibe_course_drip = $('.vibe_course_drip:checked').val();
    var vibe_course_drip_duration=$('#vibe_course_drip_duration').val();
    var vibe_certificate = $('.vibe_course_certificate:checked').val();
    var vibe_course_passing_percentage = $('#vibe_course_passing_percentage').val();
    var vibe_certificate_template = $('#vibe_certificate_template').val();
    var vibe_course_badge_percentage = $('#vibe_course_badge_percentage').val();
    var vibe_badge = $('.vibe_badge:checked').val(); // Checks if bade is active or not
    var vibe_course_badge = $('#vibe_course_badge').val();
    var vibe_course_badge_title = $('#vibe_course_badge_title').val();
    var vibe_max_students = $('#vibe_max_students').val();
    var vibe_start_date = $('#vibe_start_date').val();
    var vibe_group = $('#vibe_group').val();
    var vibe_forum = $('#vibe_forum').val();
    //var vibe_duration=$('#vibe_duration').val();
    var vibe_duration = 9999;
    var vibe_course_instructions = $('#vibe_course_instructions').html();
    var vibe_course_message = $('#vibe_course_message').html();
    var level = 0;
    if($('#course-level-select').length){
        level = $('#course-level-select').val();
    }
    var $this = $(this);
    var defaulttxt = $this.html();

    $this.prepend('<i class="icon-sun-stroke animated spin"></i>');
    $this.addClass('disabled');
    $.confirm({
      text: wplms_front_end_messages.save_course_confirm,
      confirm: function() {
         $.ajax({
                type: "POST",
                url: ajaxurl,
                data: { action: 'save_course_settings',
                        security: $('#security').val(),
                        course_id: course_id,
                        vibe_course_auto_eval: vibe_course_auto_eval,
                        vibe_pre_course: vibe_pre_course,
                        vibe_course_drip : vibe_course_drip,
                        vibe_course_drip_duration : vibe_course_drip_duration,
                        vibe_duration:vibe_duration,
                        vibe_certificate : vibe_certificate,
                        vibe_course_passing_percentage : vibe_course_passing_percentage,
                        vibe_certificate_template : vibe_certificate_template,
                        vibe_badge : vibe_badge,
                        vibe_course_badge_title:vibe_course_badge_title,
                        vibe_course_badge_percentage : vibe_course_badge_percentage,
                        vibe_course_badge : vibe_course_badge,
                        vibe_max_students:vibe_max_students,
                        vibe_start_date:vibe_start_date,
                        vibe_group : vibe_group,
                        vibe_forum : vibe_forum,
                        vibe_course_instructions : vibe_course_instructions,
                        vibe_course_message:vibe_course_message,
                        level:level
                      },
                cache: false,
                success: function (html) {
                    $this.find('i').remove();
                    $this.removeClass('disabled');
                    if($.isNumeric(html)){
                        var active=$('#course_creation_tabs li.active');
                        active.removeClass('active');
                        $('#course_settings').removeClass('active');
                        $('#course_settings_help').removeClass('active');
                        active.addClass('done');
                        $('#course_creation_tabs li.done').next().addClass('active');
                        $('#course_curriculum').addClass('active');
                        $('#course_curriculum_help').addClass('active');
                    }else{
                        console.log(html);
                        $this.html(html);
                        setTimeout(function(){$this.html(defaulttxt);}, 2000);
                    }
                }
        });
      },
      cancel: function() {
          $this.find('i').remove();
      },
      confirmButton: wplms_front_end_messages.save_course_confirm_button,
      cancelButton: vibe_course_module_strings.cancel
      });
    });

    $('ul.curriculum').sortable({
          revert: true,
          cursor: 'move',
          refreshPositions: true,
          opacity: 0.6,
          scroll:true,
          containment: 'parent',
          placeholder: 'placeholder',
          tolerance: 'pointer',
    });//.disableSelection();

    $('body').delegate('#add_course_section','click',function(event){
        var clone = $('#hidden_base .new_section').clone();
        $('ul.curriculum').append(clone);
        $('ul.curriculum').sortable({
          revert: true,
          cursor: 'move',
          refreshPositions: true,
          opacity: 0.6,
          scroll:true,
          containment: 'parent',
          placeholder: 'placeholder',
          tolerance: 'pointer',
        });//.disableSelection();
        autoset_section();
    });
    $('body').delegate('#add_course_unit','click',function(event){
        hidden_unit_quiz_button(); // -->
        var clone = $('#hidden_base .new_unit').clone();
        clone.find('select').chosen();
        $('ul.curriculum').append(clone);
        $('#save_course_curriculum').addClass('disabled');
        $('ul.curriculum').sortable({
          revert: true,
          cursor: 'move',
          refreshPositions: true,
          opacity: 0.6,
          scroll:true,
          containment: 'parent',
          placeholder: 'placeholder',
          tolerance: 'pointer',
        });//.disableSelection();
        return false;
    });
    $('body').delegate('#add_course_quiz','click',function(event){
        hidden_unit_quiz_button();// -->
        var clone = $('#hidden_base .new_quiz').clone();
        clone.find('select').chosen();
        $('ul.curriculum').append(clone);
        $('#save_course_curriculum').addClass('disabled');
        $('ul.curriculum').sortable({
          revert: true,
          cursor: 'move',
          refreshPositions: true,
          opacity: 0.6,
          scroll:true,
          containment: 'parent',
          placeholder: 'placeholder',
          tolerance: 'pointer',
        });//.disableSelection();
        return false;
    });
    $('body').delegate('.curriculum select ','change',function(event){
        var href;
        if($(this).val() == 'add_new'){
            $(this).parent().find('.new_unit_actions').fadeIn(200);
            $(this).parent().find('.new_quiz_actions').fadeIn(200);
            $(this).parent().find('.unit_actions').fadeOut(200);
            $(this).parent().find('.quiz_actions').fadeOut(200);

            $('#save_course_curriculum').addClass('disabled');
            $('.new_unit_title,.new_quiz_title').focus();
        }else{
            $(this).parent().find('.new_unit_actions').fadeOut(200);
            $(this).parent().find('.new_quiz_actions').fadeOut(200);
            $(this).parent().find('.unit_actions').fadeIn(200);
            $(this).parent().find('.quiz_actions').fadeIn(200);

            href= $(this).find('option:selected').attr('data-link')+'edit';
            if($(this).parent().hasClass('new_unit')){
                $(this).parent().find('.edit_unit').attr('href',href);
            }else{
                $(this).parent().find('.edit_quiz').attr('href',href);
            }

            $('#save_course_curriculum').removeClass('disabled');
        }
    });
    $('body').delegate('.new_unit_actions .publish','click',function(event){

        var buttonPublish = $(this);
        var course_id=$('#course_id').val();
        var $this = $(this);
        var title = $this.closest('.new_unit_actions').find('.new_unit_title').val();

        $this.prepend('<i class="icon-sun-stroke animated spin"></i>');

        $.confirm({
          text: wplms_front_end_messages.create_unit_confirm,
          confirm: function() {
             $.ajax({
                    type: "POST",
                    url: ajaxurl,
                    data: { action: 'child_create_unit',
                            security: $('#security').val(),
                            course_id: course_id,
                            unit_title: title
                          },
                    cache: false,
                    success: function (html) {
                        $this.closest('.new_unit').html(html);
                        $('#save_course_curriculum').removeClass('disabled');
                        show_unit_quiz_button();
                        luukhoahoc(buttonPublish);
                    }
            });
          },
          cancel: function() {
              $this.find('i').remove();
              $('#save_course_curriculum').removeClass('disabled');
          },
          confirmButton: wplms_front_end_messages.create_unit_confirm_button,
          cancelButton: vibe_course_module_strings.cancel
          });
    });

    $('.date_box').datepicker({
      dateFormat: 'yy-mm-dd'
    });

    $('body').delegate('.new_quiz_actions .publish','click',function(event){
        var course_id=$('#course_id').val();
        var $this = $(this);
        var title = $this.closest('.new_quiz_actions').find('.new_quiz_title').val();

        $this.prepend('<i class="icon-sun-stroke animated spin"></i>');

        $.confirm({
          text: wplms_front_end_messages.create_quiz_confirm,
          confirm: function() {
             $.ajax({
                    type: "POST",
                    url: ajaxurl,
                    data: { action: 'child_create_quiz',
                            security: $('#security').val(),
                            course_id: course_id,
                            quiz_title: title
                          },
                    cache: false,
                    success: function (html) {

                        $this.closest('.new_quiz').html(html);
                        $('#save_course_curriculum').removeClass('disabled');
                        show_unit_quiz_button();
                        luukhoahoc($this);
                    }
            });
          },
          cancel: function() {
              $this.find('i').remove();
              $('#save_course_curriculum').removeClass('disabled');
          },
          confirmButton: wplms_front_end_messages.create_quiz_confirm_button,
          cancelButton: vibe_course_module_strings.cancel
          });
    });
    $('body').delegate('.new_q .publish','click',function(event){

        var $this = $(this);
        var title = $this.closest('.new_q').find('.question_title').val();
        var quiz_id = $('.save_quiz_settings').attr('data-quiz');
        $this.prepend('<i class="icon-sun-stroke animated spin"></i>');

        $.confirm({
          text: wplms_front_end_messages.create_question_confirm,
          confirm: function() {
             $.ajax({
                    type: "POST",
                    url: ajaxurl,
                    data: { action: 'create_question',
                            security: $('#security').val(),
                            title: title,
                            quiz_id:quiz_id
                          },
                    cache: false,
                    success: function (html) {
                        $this.closest('.new_question').html(html);
                        $this.closest('.new_question').removeClass('new_question');
                        $('.save_quiz_settings').removeClass('disabled');
                    }
            });
          },
          cancel: function() {
              $this.find('i').remove();
          },
          confirmButton: wplms_front_end_messages.create_question_confirm_button,
          cancelButton: vibe_course_module_strings.cancel
          });
    });

    $('body').delegate('.curriculum .dropdown-menu .delete','click',function(event){
        event.preventDefault();
        var $this = $(this);
        var course_id=$('#course_id').val();
        var li = $(this).parent().parent().parent().parent();
        var id = li.find('h3.title').attr('data-id');
        console.log(id);
        $.confirm({
          text: wplms_front_end_messages.delete_confirm,
          confirm: function() {
             $.ajax({
                    type: "POST",
                    url: ajaxurl,
                    data: { action: 'delete_curriculum',
                            security: $('#security').val(),
                            course_id: course_id,
                            id: id
                          },
                    cache: false,
                    success: function (html) {
                        $this.find('i').remove();
                        if($.isNumeric(html)){
                            li.remove();
                        }else{
                            alert(html);
                        }
                    }
            });
          },
          cancel: function() {
              $this.find('i').remove();
          },
          confirmButton: wplms_front_end_messages.delete_confirm_button,
          cancelButton: vibe_course_module_strings.cancel
          });
    });
    $('body').delegate('.dropdown-menu .remove','click',function(event){
        var li = $(this).parent().parent().parent().parent();
        li.remove();
        $('#save_course_curriculum').removeClass('disabled');
    });
    $('body').delegate('.dropdown-menu .remove_new','click',function(event){
        var li = $(this).parent().parent().parent().parent().parent().parent();
        li.remove();
        $('#save_course_curriculum').removeClass('disabled');
    });

    $('body').delegate('#save_course_curriculum','click',function(event){

        var course_id=$('#course_id').val();
        var $this = $(this);
        var defaulttxt = $this.html();
        var curriculum = [];

        $('ul.curriculum li').each(function() {
            $(this).find('h3').each(function(){
                if($(this).hasClass('title')){
                    var data = {
                                   id: $(this).attr('data-id')
                               };
                }else{
                    var data = {
                                   id: $(this).text()
                               };
                }
                curriculum.push(data);
            });
            //$(this).find('select').each(function(){
            //    var data = {
            //                   id: $(this).val()
            //               };
            //    curriculum.push(data);
            //});
            $(this).find('input.section').each(function(){
                var data = {
                               id: $(this).val()
                           };
                curriculum.push(data);
            });
        });

        $this.prepend('<i class="icon-sun-stroke animated spin"></i>');

        $.confirm({
          text: wplms_front_end_messages.save_course_confirm,
          confirm: function() {
             $.ajax({
                    type: "POST",
                    url: ajaxurl,
                    data: { action: 'luuthutubaipost',
                            security: $('#security').val(),
                            course_id: course_id,
                            curriculum: JSON.stringify(curriculum)
                          },
                    cache: false,
                    success: function (html) {
                        $this.find('i').remove();
                        if($.isNumeric(html)){
                            var active=$('#course_creation_tabs li.active');
                            active.removeClass('active');
                            $('#course_curriculum').removeClass('active');
                            $('#course_curriculum_help').removeClass('active');
                            active.addClass('done');
                            $('#course_creation_tabs li.done').next().addClass('active');
                            $('#course_pricing').addClass('active');
                            $('#course_pricing_help').addClass('active');
                        }else{
                            $this.html(html);
                            setTimeout(function(){$this.html(defaulttxt);}, 2000);
                        }
                    }
            });
          },
          cancel: function() {
              $this.find('i').remove();
          },
          confirmButton: wplms_front_end_messages.save_course_confirm_button,
          cancelButton: vibe_course_module_strings.cancel
          });

    });

    $('body').delegate('#vibe_product ','change',function(event){
        if($(this).val() == 'add_new'){
            $('.new_product').fadeIn(200);
        }else{
            $('.new_product').fadeOut(200);
        }
    });
    $('body').delegate('.vibe_course_free ','click',function(event){
        var val =$('.vibe_course_free:checked').val();
        if(val == 'S'){
            $('#course_pricing > ul > li.course_product').fadeOut(200);
            $('#course_pricing > ul > li.course_membership').fadeOut(200);
            $('#course_pricing > ul > li.new_product').fadeOut(200);
        }else{
            $('#course_pricing > ul > li.course_product').fadeIn(200);
            $('#course_pricing > ul > li.course_membership').fadeIn(200);
        }
    });
    $('body').delegate('#save_pricing ','click',function(event){

        var course_id=$('#course_id').val();

        var course_pricing={};

        course_pricing['vibe_course_free'] = $('.vibe_course_free:checked').val();
        if($('#vibe_product').length){
            course_pricing['vibe_product']=$('#vibe_product').val();
            course_pricing['vibe_subscription']=$('.vibe_subscription:checked').val();
            course_pricing['vibe_product_price']=$('#product_price').val();
            course_pricing['vibe_duration']=$('#product_duration').val();
        }
        if($('#vibe_pmpro_membership').length)
            course_pricing['vibe_pmpro_membership']=$('#vibe_pmpro_membership').val();

        if($('#vibe_mycred_points').length){
            course_pricing['vibe_mycred_points']=$('#vibe_mycred_points').val();
            course_pricing['vibe_mycred_subscription']=$('.vibe_mycred_subscription:checked').val();
            course_pricing['vibe_mycred_duration']=$('#vibe_mycred_duration').val();
        }

        var $this = $(this);
        var defaulttxt = $this.html();
        $.confirm({
          text: wplms_front_end_messages.save_course_confirm,
          confirm: function() {
             $.ajax({
                    type: "POST",
                    url: ajaxurl,
                    data: { action: 'save_pricing',
                            security: $('#security').val(),
                            course_id: course_id,
                            pricing:JSON.stringify(course_pricing)
                          },
                    cache: false,
                    success: function (html) {
                        $this.find('i').remove();
                        console.log(html);
                        if($.isNumeric(html)){
                            var active=$('#course_creation_tabs li.active');
                            active.removeClass('active');
                            $('#course_pricing').removeClass('active');
                            $('#course_pricing_help').removeClass('active');
                            active.addClass('done');
                            $('#course_creation_tabs li.done').next().addClass('active');
                            $('#course_live').addClass('active');
                            $('#course_live_help').addClass('active');
                        }else{
                            $this.html(html);
                            setTimeout(function(){$this.html(defaulttxt);}, 2000);
                        }
                    }
            });
          },
          cancel: function() {
              $this.find('i').remove();
          },
          confirmButton: wplms_front_end_messages.save_course_confirm_button,
          cancelButton: vibe_course_module_strings.cancel
          });
    });
  /*
    $('body').delegate('#save_membership','click',function(event){

        var course_id=$('#course_id').val();
        var vibe_course_free=$('.vibe_course_free:checked').val();


        var $this = $(this);
        var defaulttxt = $this.html();
        $.confirm({
          text: wplms_front_end_messages.save_course_confirm,
          confirm: function() {
             $.ajax({
                    type: "POST",
                    url: ajaxurl,
                    data: { action: 'save_membership',
                            security: $('#security').val(),
                            course_id: course_id,
                            vibe_course_free: vibe_course_free,
                            vibe_pmpro_membership:vibe_pmpro_membership,
                          },
                    cache: false,
                    success: function (html) {
                        $this.find('i').remove();
                        if($.isNumeric(html)){
                            var active=$('#course_creation_tabs li.active');
                            active.removeClass('active');
                            $('#course_pricing').removeClass('active');
                            $('#course_pricing_help').removeClass('active');
                            active.addClass('done');
                            $('#course_creation_tabs li.done').next().addClass('active');
                            $('#course_live').addClass('active');
                            $('#course_live_help').addClass('active');
                        }else{
                            $this.html(html);
                            setTimeout(function(){$this.html(defaulttxt);}, 2000);
                        }
                    }
            });
          },
          cancel: function() {
              $this.find('i').remove();
          },
          confirmButton: wplms_front_end_messages.save_course_confirm_button,
          cancelButton: vibe_course_module_strings.cancel
          });
    });
*/
    $('body').delegate('#publish_course','click',function(event){


        var course_id=$('#course_id').val();
        var $this = $(this);
        var defaulttxt = $this.html();
        $this.addClass('disable');
        $.confirm({
          text: wplms_front_end_messages.save_course_confirm,
          confirm: function() {
             $.ajax({
                    type: "POST",
                    url: ajaxurl,
                    data: { action: 'publish_course',
                            security: $('#security').val(),
                            course_id: course_id
                          },
                    cache: false,
                    success: function (html) {
                        $this.find('i').remove();
                        $this.after(html);
                        $this.fadeOut(200);
                    }
            });
          },
          cancel: function() {
              $this.find('i').remove();
          },
          confirmButton: wplms_front_end_messages.save_course_confirm_button,
          cancelButton: vibe_course_module_strings.cancel
          });
    });

  // Lưu cấu hình khóa học
  $('body').delegate('#save_unit_settings','click',function(event){

        var $this = $(this);
        var unit_id=$this.attr('data-id');
        var course_id=$this.attr('data-course');
        var defaulttxt = $this.html();
        var vibe_type = $this.parent().parent().find('#vibe_type').val();
        var vibe_free = $this.parent().parent().find('.onoffswitch-checkbox:checked').val();
        if(vibe_free == "H"){
            vibe_free = "H";
        }else{
            vibe_free = "S";
        }
        var vibe_duration = $this.parent().parent().find('#vibe_duration').val();

        var vibe_assignment = '';
        if($('#vibe_assignment').length)
         vibe_assignment = $('#vibe_assignment').val();

       var vibe_forum = '';
       if($('#vibe_forum').length)
        vibe_forum= $('#vibe_forum').val();

        $this.addClass('disabled');
        $.confirm({
          text: wplms_front_end_messages.save_unit_confirm,
          confirm: function() {
             $.ajax({
                    type: "POST",
                    url: ajaxurl,
                    data: { action: 'child_save_unit_settings',
                            security: $('#security').val(),
                            course_id: course_id,
                            unit_id: unit_id,
                            vibe_type:vibe_type,
                            vibe_free:vibe_free,
                            vibe_duration:vibe_duration,
                            vibe_assignment:vibe_assignment,
                            vibe_forum:vibe_forum
                          },
                    cache: false,
                    success: function (html) {
                        $this.find('i').remove();
                        $this.removeClass('disabled');
                        $this.html(html);
                        setTimeout(function(){$this.html(defaulttxt);}, 2000);
                    }
            });
          },
          cancel: function() {
              $this.find('i').remove();
          },
          confirmButton: wplms_front_end_messages.save_unit_confirm_button,
          cancelButton: vibe_course_module_strings.cancel
          });
  });

  $('#questions').sortable({
          revert: true,
          cursor: 'move',
          refreshPositions: true,
          opacity: 0.6,
          scroll:true,
          containment: 'parent',
          placeholder: 'placeholder',
          tolerance: 'pointer',
        });//.disableSelection();

  $('body').delegate('#add_question','click',function(event){

      var clone=$('#hidden > li').clone();
      $('#questions').append(clone);
        $('#questions').sortable({
          revert: true,
          cursor: 'move',
          refreshPositions: true,
          opacity: 0.6,
          scroll:true,
          containment: 'parent',
          placeholder: 'placeholder',
          tolerance: 'pointer',
        });//.disableSelection();
       clone.find('select').chosen();
       $('.save_quiz_settings').addClass('disabled');
  });

  $('body').delegate('.question','change',function(){
      var value = $(this).val();
      if(value === 'add_new'){
        $(this).parent().find('.new_q').fadeIn(300);
      }else{
        $('.save_quiz_settings').removeClass('disabled');
      }

  });
  $('body').delegate('.save_quiz_settings','click',function(event){

        var $this = $(this);
        $this.addClass('disabled');
        var quiz_id=$this.attr('data-quiz');
        var defaulttxt = $this.html();
        var vibe_subtitle = $('#vibe_subtitle').html();
        var vibe_quiz_course = $('#vibe_quiz_course').val();
        var vibe_duration = $('#vibe_duration').val();
        var vibe_quiz_auto_evaluate = $('.vibe_quiz_auto_evaluate:checked').val();
        var vibe_quiz_dynamic = $('.vibe_quiz_dynamic:checked').val();
        var vibe_quiz_tags = $('#vibe_quiz_tags').val();
        var vibe_quiz_number_questions = $('#vibe_quiz_number_questions').val();
        var vibe_quiz_marks_per_question = $('#vibe_quiz_marks_per_question').val();
        var vibe_quiz_retakes=$('#vibe_quiz_retakes').val();
        var vibe_quiz_random = $('.vibe_quiz_randome:checked').val();
        var vibe_quiz_message = $('#vibe_quiz_message').html();

        var questions = [];
        var qid,qmarks;
        $('#questions > li').each(function() {
              qid=$(this).find('.question').val();
              qmarks=$(this).find('.question_marks').val();
              var data = {
                           ques: qid,
                           marks: qmarks
                       };
          questions.push(data);
        });

        $this.prepend('<i class="icon-sun-stroke animated spin"></i>');

        $.confirm({
          text: wplms_front_end_messages.save_quiz_confirm,
          confirm: function() {
             $.ajax({
                    type: "POST",
                    url: ajaxurl,
                    data: { action: 'save_quiz_settings',
                            security: $('#security').val(),
                            quiz_id: quiz_id,
                            vibe_subtitle:vibe_subtitle,
                            vibe_quiz_course:vibe_quiz_course,
                            vibe_duration:vibe_duration,
                            vibe_quiz_auto_evaluate:vibe_quiz_auto_evaluate,
                            vibe_quiz_dynamic:vibe_quiz_dynamic,
                            vibe_quiz_tags:vibe_quiz_tags,
                            vibe_quiz_number_questions:vibe_quiz_number_questions,
                            vibe_quiz_marks_per_question:vibe_quiz_marks_per_question,
                            vibe_quiz_retakes:vibe_quiz_retakes,
                            vibe_quiz_random:vibe_quiz_random,
                            vibe_quiz_message:vibe_quiz_message,
                            questions: JSON.stringify(questions)
                          },
                    cache: false,
                    success: function (html) {
                        $this.find('i').remove();
                        $this.html(html);
                        setTimeout(function(){$this.html(defaulttxt);$this.removeClass('disabled');location.reload();}, 2000);
                    }
            });
          },
          cancel: function() {
              $this.find('i').remove();
              $this.removeClass('disabled');
          },
          confirmButton: wplms_front_end_messages.save_quiz_confirm_button,
          cancelButton: vibe_course_module_strings.cancel
          });

    });

    $('body').delegate('#questions .dropdown-menu .delete','click',function(event){

        var $this = $(this);
        var id = $(this).parent().parent().parent().parent().find('.question').val();
        var li = $(this).parent().parent().parent().parent();
        $.confirm({
          text: wplms_front_end_messages.delete_confirm,
          confirm: function() {
             $.ajax({
                    type: "POST",
                    url: ajaxurl,
                    data: { action: 'delete_question',
                            security: $('#security').val(),
                            id: id
                          },
                    cache: false,
                    success: function (html) {
                        $this.find('i').remove();
                        if($.isNumeric(html)){
                            li.remove();
                        }else{
                            alert(html);
                        }
                    }
            });
          },
          cancel: function() {
              $this.find('i').remove();
          },
          confirmButton: wplms_front_end_messages.delete_confirm_button,
          cancelButton: vibe_course_module_strings.cancel
          });
    });

  $('#vibe_question_type').change(function(event){

      var value = $(this).val();
      if(value === 'smalltext' || value === 'largetext'){
        $('li.optionli').fadeOut(200);
      }else{
        $('li.optionli').fadeIn(200);
        var $this=$('.vibe_question_options');
        $this.removeClass();
        $this.addClass('vibe_question_options');
        $this.addClass(value);
      }
  });

    $('.vibe_question_options').sortable({
      revert: true,
      cursor: 'move',
      refreshPositions: true,
      opacity: 0.6,
      scroll:true,
      containment: 'parent',
      placeholder: 'placeholder',
      tolerance: 'pointer',
      update: function(event, ui) {
        $('.vibe_question_options').trigger('update');
      }
    });//.disableSelection();

    $('#add_option').click(function(event){

        var clone = $('.hidden > li').clone();
        console.log(clone);
        $('.vibe_question_options').append(clone);
        $('.vibe_question_options').trigger('update');

    });

    $('.vibe_question_options').on('update',function(){
      var index=0;
        $(this).find('li').each(function(){
            index= $(this).index();
            $(this).find('span').text((index+1));
        });
    });
    $('body').delegate('.vibe_question_options li > span','click',function(event){
        var parent = $(this).parent();
        var index = parent.index();

        if($('.vibe_question_options').hasClass('single')){
          $('.vibe_question_options li').removeClass('selected');
          parent.addClass('selected');
          $('#vibe_question_answer').trigger('update');
        }
        if($('.vibe_question_options').hasClass('multiple')){
            if(parent.hasClass('selected')){
              parent.removeClass('selected');
            }else{
              parent.addClass('selected');
              $('#vibe_question_answer').trigger('update');
            }
        }
        if($('.vibe_question_options').hasClass('sort')){

        }


    });
    $('#vibe_question_answer').on('update',function(){
        var value='';
        value = $('.vibe_question_options > li.selected').map(function() {
              return ($(this).index()+1);
          }).get().join(',');
        $('#vibe_question_answer').attr('value',value);
    });
    $('body').delegate('.vibe_quiz_dynamic','click',function(){
        var value = $('.vibe_quiz_dynamic:checked').val();
          if(value === 'S'){
            $('.dynamic').fadeIn(200);
            $('#quiz_question_controls').fadeOut(200);
          }else{
            $('.dynamic').fadeOut(200);
            $('#quiz_question_controls').fadeIn(200);
          }
    });
    $('#save_question_settings').click(function(event){

        var $this = $(this);
        var defaulttxt = $this.html();
        var id = $('#question_id').val();
        var vibe_question_type = $('#vibe_question_type').val();
        var vibe_question_answer = $('#vibe_question_answer').val();
        var vibe_question_hint = $('#vibe_question_hint').val();
        var vibe_question_explaination = $('#vibe_question_explaination').html();

         var vibe_question_options = [];

         $this.addClass('disabled');
        $('.vibe_question_options > li').each(function() {
              var option = $(this).find('.option').val();
              var data = {
                           option: option
                       };
          vibe_question_options.push(data);
        });
        $.confirm({
          text: wplms_front_end_messages.save_settings,
          confirm: function() {
             $.ajax({
                    type: "POST",
                    url: ajaxurl,
                    data: { action: 'save_question',
                            security: $('#security').val(),
                            id: id,
                            vibe_question_type:vibe_question_type,
                            vibe_question_options:JSON.stringify(vibe_question_options),
                            vibe_question_answer:vibe_question_answer,
                            vibe_question_hint:vibe_question_hint,
                            vibe_question_explaination:vibe_question_explaination
                          },
                    cache: false,
                    success: function (html) {
                        $this.find('i').remove();
                        $this.removeClass('disabled');
                        $this.html(html);
                        setTimeout(function(){$this.html(defaulttxt);}, 2000);
                    }
            });
          },
          cancel: function() {
              $this.find('i').remove();
              $this.removeClass('disabled');
          },
          confirmButton: wplms_front_end_messages.save_confirm_button,
          cancelButton: vibe_course_module_strings.cancel
          });
    });

    $('body').delegate('.rem','click',function(event){

        $(this).parent().remove();
        $('#save_course_curriculum').removeClass('disabled');
        $('.save_quiz_settings').removeClass('disabled');
        $('#save_unit_settings').removeClass('disabled');
    });

    $('body').delegate('#vibe_assignment','change',function(){
        var value = $(this).val();
        var href;
        if(value === 'add_new'){
          $('#save_unit_settings').addClass('disabled');
          $('#assignment_link').addClass('hide');
        }else{
          href= $('#vibe_assignment > option:selected').attr('data-link')+'?edit';
          $('#assignment_link').attr('href',href);
          $('#assignment_link').removeClass('hide');
        }

  });

    $('.add_new_assignment').click(function(event){
        $(this).parent().next().show(200);
    });

   $('body').delegate('.dropdown-menu .new_remove','click',function(event){

      var li = $(this).parent().parent().parent().parent();
      li.fadeOut(200);
      $('#save_unit_settings').removeClass('disabled');
  });
  $('body').delegate('.new_assignment_actions .publish','click',function(event){

        var unit_id=$('#save_unit_settings').attr('data-id');
        var $this = $(this);
        var title = $this.closest('.new_assignment_actions').find('.new_assignment_title').val();

        $this.prepend('<i class="icon-sun-stroke animated spin"></i>');

        $.confirm({
          text: wplms_front_end_messages.create_assignment_confirm,
          confirm: function() {
             $.ajax({
                    type: "POST",
                    url: ajaxurl,
                    data: { action: 'create_assignment',
                            security: $('#security').val(),
                            unit_id: unit_id,
                            title: title
                          },
                    cache: false,
                    success: function (html) {
                        $('#vibe_assignment').append(html);
                        $('#vibe_assignment').trigger('change');
                        $("#vibe_assignment").trigger("chosen:updated");
                        $('#save_unit_settings').removeClass('disabled');
                        $('#assignment_link').removeClass('hide');
                        $('.new_assignment_actions > li:last-child').fadeOut(200);
                    }
            });
          },
          cancel: function() {
              $this.find('i').remove();
              $('#save_course_curriculum').removeClass('disabled');
          },
          confirmButton: wplms_front_end_messages.create_assignment_confirm_button,
          cancelButton: vibe_course_module_strings.cancel
          });
    });
  $('.vibe_assignment_evaluation').click(function(){
      var value = $('.vibe_assignment_evaluation:checked').val();
      $('#assignment_course').removeClass('hide');
      if(value === 'S'){
        $('#assignment_course').fadeIn(200);
      }else{
        $('#assignment_course').fadeOut(200);
      }

  });

  $('#vibe_assignment_submission_type').change(function(){
      var value = $(this).val();
      if(value === 'textarea'){
        $('#attachment_type').fadeOut(200);
      }else{
        $('#attachment_type').fadeIn(200);
      }
  });

  $('#save_assignment_settings').click(function(event){

        var $this = $(this);
        var defaulttxt = $this.html();
        var assignment_id = $('#assignment_id').val();
        var vibe_subtitle = $('#vibe_subtitle').text();
        var vibe_assignment_marks = $('#vibe_assignment_marks').val();
        var vibe_assignment_duration = $('#vibe_assignment_duration').val();
        var vibe_assignment_evaluation= $('.vibe_assignment_evaluation:checked').val();
        var vibe_assignment_course= $('#vibe_assignment_course').val();
        var vibe_assignment_submission_type= $('#vibe_assignment_submission_type').val();
        var vibe_attachment_size = $('#vibe_attachment_size').val();
        var vibe_attachment_type= [];
        $('#vibe_attachment_type option:selected').each(function(i,selected){
            vibe_attachment_type[i] = $(selected).val();
        });

        $this.addClass('disabled');
        $.confirm({
          text: wplms_front_end_messages.save_settings,
          confirm: function() {
             $.ajax({
                    type: "POST",
                    url: ajaxurl,
                    data: { action: 'save_assignment_settings',
                            security: $('#assignment_security').val(),
                            assignment_id: assignment_id,
                            vibe_subtitle:vibe_subtitle,
                            vibe_assignment_marks:vibe_assignment_marks,
                            vibe_assignment_duration:vibe_assignment_duration,
                            vibe_assignment_evaluation:vibe_assignment_evaluation,
                            vibe_assignment_course:vibe_assignment_course,
                            vibe_assignment_submission_type:vibe_assignment_submission_type,
                            vibe_attachment_type: JSON.stringify(vibe_attachment_type),
                            vibe_attachment_size:vibe_attachment_size
                          },
                    cache: false,
                    success: function (html) {
                        $this.find('i').remove();
                        $this.html(html);
                        setTimeout(function(){$this.html(defaulttxt);}, 2000);
                        $this.removeClass('disabled');
                    }
            });
          },
          cancel: function() {
              $this.removeClass('disabled');
          },
          confirmButton: wplms_front_end_messages.save_confirm_button,
          cancelButton: vibe_course_module_strings.cancel
          });
    });
    //Hiển thị mặc định section mặc định khi vừa load trang

    var check_course_id = $('#check_create_course').val();
    if($('.curriculum li').length == 0 ){
        if(check_course_id != null){
            var clone = $('#hidden_base .new_section').clone();
            $('ul.curriculum').append(clone);
            $('ul.curriculum').sortable({
                revert: true,
                cursor: 'move',
                refreshPositions: true,
                opacity: 0.6,
                scroll:true,
                containment: 'parent',
                placeholder: 'placeholder',
                tolerance: 'pointer'
            });//.disableSelection();
        }
    }



    function autoset_section(){
        var count = 0;

        $('ul.curriculum').find("li").each(function(){
            if($(this).hasClass('new_section')){
                count++;
                var strHtml = "<span class='auto_section'> Chương : " + count + "</span>";
                if($(this).find('span').hasClass('auto_section')){
                    var test =  $(this).find('span');

                    test.text("");
                    test.append(strHtml);
                    test.removeClass('auto_section');
                }else{
                    $(this).find('span.show_section').append(strHtml);
                }

            }
        });
    }

    // Danh dau section tu dong
    $("ul.curriculum").click(function(event){
        autoset_section();
    });

    autoset_section();

    // Hiển thị unit mặc định khi vừa load trang
    var check_course_id = $('#check_create_course').val();
    if($('.curriculum li').length == 1 || $('.curriculum li').length == 0 ){
        if(check_course_id != null){
            var clone = $('#hidden_base .new_unit').clone();
            clone.find('select').chosen();
            $('ul.curriculum').append(clone);
            $('#save_course_curriculum').addClass('disabled');
            $('ul.curriculum').sortable({
                revert: true,
                cursor: 'move',
                refreshPositions: true,
                opacity: 0.6,
                scroll:true,
                containment: 'parent',
                placeholder: 'placeholder',
                tolerance: 'pointer',
            });//.disableSelection();
        }
    }





    // ẩn hiện button
    function hidden_unit_quiz_button(){
        $('#add_course_unit').css('display','none');
        $('#add_course_quiz').css('display','none');
        //$('.hidden_button_description').css('display','none');
    }

    function show_unit_quiz_button(){
        $('#add_course_unit').css('display','block');
        $('#add_course_quiz').css('display','block');
        //$('.hidden_button_description').css('display','block');
    }

    //
    $('body').delegate('.btn-group .remove','click',function(event){
        var li = $(this).parent().parent().parent().parent();
        li.remove();
        $('#save_course_curriculum').removeClass('disabled');
    });
    $('body').delegate('.btn-group .remove_new','click',function(event){
        var li = $(this).parent().parent().parent().parent().parent();
        li.remove();
        show_unit_quiz_button();
        $('#save_course_curriculum').removeClass('disabled');

    });

    //function tự động save đề cương khi click vào nút xóa, save nội dung
    function luukhoahoc(button_class){

        var course_id=$('#course_id').val();
        var $this = button_class;
        var defaulttxt = $this.html();
        var curriculum = [];

        $('ul.curriculum li').each(function() {
            $(this).find('h3').each(function(){
                if($(this).hasClass('title')){
                    var data = {
                        id: $(this).attr('data-id')
                    };
                }else{
                    var data = {
                        id: $(this).text()
                    };
                }
                curriculum.push(data);
            });
            //$(this).find('select').each(function(){
            //    var data = {
            //        id: $(this).val()
            //    };
            //    curriculum.push(data);
            //});
            $(this).find('input.section').each(function(){
                var data = {
                    id: $(this).val()
                };
                curriculum.push(data);
            });
        });

        $this.prepend('<i class="icon-sun-stroke animated spin"></i>');

        $.confirm({
            text: wplms_front_end_messages.save_course_confrim,
            confirm: function() {
                $.ajax({
                    type: "POST",
                    url: ajaxurl,
                    data: { action: 'luuthutubaipost',
                        security: $('#security').val(),
                        course_id: course_id,
                        curriculum: JSON.stringify(curriculum)
                    },
                    cache: false,
                    success: function (html) {
                        $this.find('i').remove();
                        if($.isNumeric(html)){
                            //var active=$('#course_creation_tabs li.active');
                            //active.removeClass('active');
                            //$('#course_curriculum').removeClass('active');
                            //$('#course_curriculum_help').removeClass('active');
                            //active.addClass('done');
                            //$('#course_creation_tabs li.done').next().addClass('active');
                            //$('#course_pricing').addClass('active');
                            //$('#course_pricing_help').addClass('active');
                        }else{
                            $this.html(html);
                            setTimeout(function(){$this.html(defaulttxt);}, 2000);
                        }
                    }
                });
            },
            cancel: function() {
                $this.find('i').remove();
            },
            confirmButton: wplms_front_end_messages.save_course_confrim_button,
            cancelButton: vibe_course_module_strings.cancel
        });
    }
    //
    //Hiển thị publish và remove trước edit và remove
    $('.new_unit_title').fadeIn(100);

    //Xử lý nút save nội dung Unit
    $('body').delegate('.save_unit_post','click',function(){

        var button_save = $(this);

        var unit_id = $(this).parent().parent().parent().parent().parent().children('.btn-group').children('.edit_content ').attr('data-id');
        tinyMCE.remove();
        tao_editor("wisSW_Editor" + unit_id);
        //var unit_content = $(this).parent().find('iframe').contents().find('.mce-content-body ').html();
        var unit_title = $(this).parent().parent().parent().parent().parent().children('.set_backgroud_unit').find('h3').text();
        var unit_content = get_tinymce_content("wisSW_Editor" + unit_id);
        $.ajax({
            type: "POST",
            url: ajaxurl,
            data: {
                action: "update_unit_content",
                unit_id: unit_id,
                unit_title: unit_title,
                unit_content: unit_content
            },
            cache: false,
            success: function(result){
                button_save.parent().parent().parent().fadeOut(); // ẩn khung nội dung khóa học
                button_save.parent().parent().parent().parent().parent().find('.btn-group').find('.header_content_unit').fadeOut();
                luukhoahoc(button_save); // lưu khóa học

            }
        })
    } );



    // Xử lý sự kiện ẩn hiện khung nội dung unit khi
    $('body').delegate('.close-btn','click',function(event){
        $(this).parent().parent().parent().find('.hidden_button_description').find('.box_shadow_content_unit').fadeOut();
        $(this).parent().fadeOut();
        $(this).parent().parent().parent().find('.hidden_button_description').find('.box_shadow_unit').fadeIn();
        $(this).parent().parent().find('.hidden_button_description').find('.box_shadow_content_unit').find('.upload_video').fadeOut();
    });


    //Xử lý nút xóa hình chữ x
    $('body').delegate('.curriculum  .menu_delete','click',function(event){
        event.preventDefault();
        var menubutton = $(this);
        var $this = $(this);
        var course_id=$('#course_id').val();
        var li = $(this).parent().parent();
        var id = li.find('h3.title').attr('data-id');
        console.log(id);
        $.confirm({
            text: wplms_front_end_messages.delete_confrim,
            confirm: function() {
                $.ajax({
                    type: "POST",
                    url: ajaxurl,
                    data: { action: 'child_delete_curriculum',
                        security: $('#security').val(),
                        course_id: course_id,
                        id: id
                    },
                    cache: false,
                    success: function (html) {
                        $this.find('i').remove();
                        if($.isNumeric(html)){
                            li.remove();
                            luukhoahoc(menubutton);
                        }else{
                            //alert(html);
                        }
                    }
                });
            },
            cancel: function() {
                $this.find('i').remove();
            },
            confirmButton: wplms_front_end_messages.delete_confrim_button,
            cancelButton: vibe_course_module_strings.cancel
        });


    });


    //Tạo khung media để upload ảnh
    $('body').delegate('.insert-my-media','click',function(){
        var myButton = $(this);
        var buttonEdit = $(myButton).parent().find('iframe').contents().find('.mce-content-body');
        if (this.window === undefined) {
            this.window = wp.media({
                title: 'Thêm tập tin',
                //library: {type: 'image'},
                displaySettings: true,
                multiple: false,
                button: {text: 'Thêm'},
		type : 'image'
            })

            var self = this; // Needed to retrieve our variable in the anonymous function below
            this.window.on('select', function() {
                var first = self.window.state().get('selection').first().toJSON();

                if(first.type != "image"){

                    var id_post = myButton.attr('data-id');
                    var id_attachment = first.id;
                    alert(id_post + " - " + id_attachment);
                    $.ajax({
                        type: "POST",
                        url: ajaxurl,
                        data:{
                            action: 'UpdateAttachmentPost',
                            id_post: id_post,
                            id_attachment: id_attachment
                        },
                        cache: false,
                        success: function(result){
                            alert('Đính kèm file thành công !');
                        }

                    });
                }else{

                    var contentImage = "<img width='300px' height='300px' src='" + first.url + "'/>";
                    buttonEdit.append(contentImage);
                }
                //for (attr in first)
                //    alert(attr);



            });
        }

        this.window.open();
        return false;
    });

    //function CatLink(link){
    //    var vitridauthea = link.indexOf('href=')+6;
    //    var vitricuoithea = link.indexOf('title')-2;
    //    var vitrithea = link.substring(vitridauthea,vitricuoithea);
    //
    //    var vitridauvideo = link.indexOf('video src=') + 11;
    //    var vitricuoivideo = link.indexOf('.mp4') + 4;
    //    var vitrithevideo = link.substring(vitridauvideo,vitricuoivideo);
    //}

    //Cắt link facebook
    function CatLinkVideoFaceBook(linkFacebook){
        //var vitridau = linkFacebook.indexOf('v=');
        //var vitricuoi = linkFacebook.indexOf('&');
        //var checkLinkFaceBook = linkFacebook.indexOf('www.facebook.com');
        //var idFacebook = linkFacebook.substring(vitridau+2,vitricuoi);
        //if(checkLinkFaceBook <=0){
        //    alert('Bạn vui lòng chèn đúng video facebook !   ')
        //}else{
        //    var chuoi = '<iframe src="http://www.facebook.com/video/embed?video_id='+idFacebook+'" width="538px" height="385px" frameborder="0"></iframe>';
        //    return chuoi;
        //}

        var vitridau = linkFacebook.indexOf('v=');
        var vitricuoi = linkFacebook.indexOf('&');
        var idFacebook ='';
        var checkLinkFaceBook = linkFacebook.indexOf('www.facebook.com');
        var vitriembed = linkFacebook.indexOf('video_id=');
        if(vitriembed <=0){
            if(vitridau > 0){
                if(vitricuoi >0 ){
                    idFacebook = linkFacebook.substring(vitridau+2,vitricuoi);
                }else{
                    idFacebook = linkFacebook.substring(vitridau+2);
                }
            }else{
                var vitridauvb = linkFacebook.indexOf('vb.');
                var catlan1 = linkFacebook.substring(vitridauvb+3);
                var vitriid = catlan1.indexOf('/');
                var catlan2 = catlan1.substr(vitriid+1);
                var vitriidfacebook = catlan2.indexOf('/');
                idFacebook = catlan2.substr(0,vitriidfacebook);

            }

        }else{
            idFacebook = linkFacebook.substring(vitriembed+9);
        }

        var chuoi = '';
        if(checkLinkFaceBook <=0){
            alert('Bạn vui lòng chèn đúng video facebook !');
        }else{
            chuoi = '<iframe src="http://www.facebook.com/video/embed?video_id='+idFacebook+'" width="538px" height="385px" frameborder="0"></iframe>';
        }
        return chuoi;

    }

    //Tạo Editor tự động
    function AddNewEditor(content_editor,id_editor){
        //Tạo button show popup chèn video facebook
        tinymce.PluginManager.add('facebook', function(editor, url) {
            // Add a button that opens a window
            editor.addButton('facebook', {
                text: 'Video FaceBook',
                icon: " icon-facebook",
                tooltip: "Thêm video facebook",
                onclick: function() {
                    // Open window
                    editor.windowManager.open({
                        title: 'Chèn video facebook',
                        body: [
                            {type: 'textbox', name: 'txt_facebook', label: 'Đường dẫn facebook : '}
                        ],
                        onsubmit: function(e) {
                            editor.insertContent(CatLinkVideoFaceBook(e.data.txt_facebook));
                        }
                    });
                }
            });

        });

        htmlSource = '<textarea class="wp-editor-area" id="wisSW_Editor' + id_editor + '"></textarea>';
        content_editor.append(htmlSource);
        tinymce.init({
            selector: "#wisSW_Editor" + id_editor,
            extended_valid_elements : "iframe[src|frameborder|style|scrolling|class|width|height|name|align]",
            plugins: [
                "media","facebook"
            ],
            menubar : false,
            toolbar1: "insertfile undo redo | styleselect | bold italic | alignleft aligncenter alignright alignjustify | bullist numlist outdent indent | link image ",
            toolbar2: "media facebook ",

        });

    }

    //Tạo editor
    function tao_editor(id_editor){
        //new nicEditor({buttonList : ['bold','italic','underline','left','center','right','justify','ol','ul','link','image','upload','youTube']}).panelInstance(id_editor);
        //$('.nicEdit-main').parent().css({"width" :"100%","height" : "100%"});
        tinymce.init({
            selector: "textarea#"+id_editor,
            extended_valid_elements : "iframe[src|frameborder|style|scrolling|class|width|height|name|align]",
            plugins: [
                "media","facebook"
            ],
            menubar : false,
            toolbar1: "insertfile undo redo | styleselect | bold italic | alignleft aligncenter alignright alignjustify | bullist numlist outdent indent | link image ",
            toolbar2: "media facebook ",
        });

    }

    $('body').delegate('.setting_content','click',function(){
        $(this).parent().parent().find('.hidden_button_description').find('.box_shadow_content_unit').fadeIn();
        $(this).parent().find('.header_content_unit').fadeIn();
        $('.content_settings').fadeIn();
        $('.content_editor').fadeOut();

    });

    function get_tinymce_content(idEditor){

        //change to name of editor set in wp_editor()
        var editorID =  idEditor;
        if (jQuery('#wp-'+editorID+'-wrap').hasClass("tmce-active"))
            var content = tinyMCE.get(editorID).getContent({format : 'html'});
        else
            var content = jQuery('#'+editorID).val();

        return content;
    }

    //bắt sự kiện khi click vào nút edit_content sẽ tạo ra editor
    $('body').delegate('.edit_content','click',function(){
        $(this).parent().parent().find('.hidden_button_description').find('.box_shadow_content_unit').fadeIn();
        $(this).parent().find('.header_content_unit').fadeIn();
        $('.content_editor').fadeIn();
        $('.content_settings').fadeOut();
        var id_editor = $(this).parent().parent().find('.hidden_button_description').find('.box_shadow_content_unit').find('.add_content_unit ').find('.content_editor').find('.text');
        var content_editor = $(this).parent().parent().find('.hidden_button_description').find('.box_shadow_content_unit').find('.add_content_unit ').find('.content_editor').find('div.text');

        if(!$(this).hasClass('click')){
            $(this).addClass('click');
            tao_editor(id_editor.attr("id"));
            AddNewEditor(content_editor,id_editor.attr("id"));

        }else{
            tinyMCE.remove();
            tao_editor("wisSW_Editor" + id_editor.attr("id"));
            tao_editor(id_editor.attr("id"));
        }


    });

    var dem = 0;
    //Xử lý nút online offline chuyển màu đỏ
    $('body').delegate('.switch','click',function(){
        dem++;
        var This = $(this);
        if(dem%2==0){
            var status=$(this).find('.vibe_course_status:checked').val();
            var statusCer = $(this).find('.vibe_course_certificate:checked').val();
            var statusBag = $(this).find('.vibe_badge:checked').val();
            var statusFree = $(this).find('.vibe_course_free:checked').val();
            if(status == 'publish' || statusCer == "S" || statusBag == 'S' || statusFree == 'S'){
               This.find('.switch-selection').css('background-color','#70c989');
            }else{
                This.find('.switch-selection').css('background-color','#dd4b39');
            }
        }
    });

    function catlinkvideoyoutube(linkYouTube){
        var vitridau = linkYouTube.indexOf('v=');
        var checkLinkYoutube = linkYouTube.indexOf('www.youtube.com');
        var vitriembed = linkYouTube.indexOf('embed');
        if(vitriembed <=0){
            var idFacebook = linkYouTube.substring(vitridau+2);
        }else{
            var idFacebook = linkYouTube.substring(vitriembed+6);
        }
        var chuoi = '';
        if(checkLinkYoutube <=0){
            chuoi = 'Bạn vui lòng chèn đúng video youtube !';
        }else{
            chuoi = '<iframe src="https://www.youtube.com/embed/'+idFacebook+'" width="538px" height="385px" frameborder="0"></iframe>';
        }
        return chuoi;
    }

    function catlinkvideofacebook(linkFacebook){
        var vitridau = linkFacebook.indexOf('v=');
        var vitricuoi = linkFacebook.indexOf('&');
        var idFacebook ='';
        var checkLinkFaceBook = linkFacebook.indexOf('www.facebook.com');
        var vitriembed = linkFacebook.indexOf('video_id=');
        if(vitriembed <=0){
            if(vitridau > 0){
                if(vitricuoi >0 ){
                    idFacebook = linkFacebook.substring(vitridau+2,vitricuoi);
                }else{
                    idFacebook = linkFacebook.substring(vitridau+2);
                }
            }else{
                var vitridauvb = linkFacebook.indexOf('vb.');
                var catlan1 = linkFacebook.substring(vitridauvb+3);
                var vitriid = catlan1.indexOf('/');
                var catlan2 = catlan1.substr(vitriid+1);
                var vitriidfacebook = catlan2.indexOf('/');
                idFacebook = catlan2.substr(0,vitriidfacebook);

            }

        }else{
             idFacebook = linkFacebook.substring(vitriembed+9);
        }

        var chuoi = '';
        if(checkLinkFaceBook <=0){
            chuoi = 'Bạn vui lòng chèn đúng video facebook !';
        }else{
            chuoi = '<iframe src="http://www.facebook.com/video/embed?video_id='+idFacebook+'" width="100%" height="385px" frameborder="0"></iframe>';
        }
        return chuoi;
    }

    function hienthivideotrailer(linkframe){
        $('.xemtrailer').text('');
        $('.xemtrailer').append(linkframe);
    }

    $('.video_trailer').blur(function(){
        var linkVideo = $('.video_trailer').text();
        var video = '';
        var checkLinkFaceBook = linkVideo.indexOf('www.facebook.com');


        if(checkLinkFaceBook <=0){
            var checkLinkYoutube = linkVideo.indexOf('www.youtube.com');
            if(checkLinkYoutube <=0){
                video = 'Vui lòng nhập đúng link facebook hoặc youtube !';
            }else{
                video = catlinkvideoyoutube(linkVideo);
            }
        }else{
            video = catlinkvideofacebook(linkVideo);
        }

        hienthivideotrailer(video);
    });

    //xử lý sự kiện khi click vào khung chèn video trailer khóa học thì mất chữ mặc định
    $('.video_trailer').focusin(function(){
        if(!$(this).hasClass('hasVideo')){
            $(this).addClass('hasVideo');
            $(this).empty();

        }
    });

});
