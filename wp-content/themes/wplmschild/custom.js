/*
 * Enter your Custom Javascript Functions here
 */

jQuery(document).ready(function($){

    // $(window).scroll(function(event){
    //  var st = $(this).scrollTop();
    //  if($('header').hasClass('fix')){
    //    var headerheight=$('header').height();
    //    if(st > headerheight){
    //      $('header').addClass('fixed');
    //    }else{
    //      $('header').removeClass('fixed');
    //    }
    //  }
    //});

//Start:khải
// Kiểm tra độ dài ký tự
//Thêm placeholder


    if($("h1#course-title").text()==""||$("h1#course-title").text()==" ")
    {
        $("h1#course-title").text("Nhập vào tiêu đề khóa học");
        $("h1#course-title").css({
            "font-family":"Arial",
            "color":"#999999"
        });
    }


    $("h1#course-title").click(function(){
        if($(this).text()=="Nhập vào tiêu đề khóa học")
        {
            $(this).text("");
        }
        $("h1#course-title").css({
            "font-family":"Arial",
            "color":"black"
        });
    });
    $("h1#course-title").blur(function(){
        if($(this).text()=="")
        {
            $(this).text("Nhập vào tiêu đề khóa học");
            $(this).css({
                "font-family":"Arial",
                "color":"#999999"

            });
        }
    });

    $('h1#course-title').keydown(DemDoDaiKyTu);
    $('h1#course-title').keyup(DemDoDaiKyTu);
    $('h1#course-title').click(function(){
        if($(this).text()!="Nhập vào tiêu đề khóa học")
        {
            DemDoDaiKyTu;
        }
        if($("h1#course-title").text()=="")
        {
            $('#lengthchar').text("60");


        }
    });
    $('h1#course-title').blur(function(){
        if($(this).text()!="Nhập vào tiêu đề khóa học")
        {
            DemDoDaiKyTu;

        }

    });
    DemDoDaiKyTu();
    function DemDoDaiKyTu(){
        $('#lengthchar').text(60-$('h1#course-title').text().length);
        if($('#lengthchar').text()=="0")
        {
            $('#lengthchar').css({"background-color":"red"})

        }
        else
        {
            $('#lengthchar').css({"background-color":"#999999"})

        }
    };
    if($("h1#course-title").text()=="Nhập vào tiêu đề khóa học")
    {
        $('#lengthchar').text("60");
    }

    /*sự kiện thêm mục tiêu*/
    $('#muctieu1').keypress(function(event){

        var keycode = (event.keyCode ? event.keyCode : event.which);
        if(keycode == '13'){
            $( "#btnMucTieu1" ).trigger( "click" );
        }
        event.stopPropagation();
    });

    $('#muctieu2').keypress(function(event){

        var keycode = (event.keyCode ? event.keyCode : event.which);
        if(keycode == '13'){
            $( "#btnMucTieu2" ).trigger( "click" );
        }
        event.stopPropagation();
    });

    $('#muctieu3').keypress(function(event){

        var keycode = (event.keyCode ? event.keyCode : event.which);
        if(keycode == '13'){
            $( "#btnMucTieu3" ).trigger( "click" );
        }
        event.stopPropagation();
    });

    $('#btnMucTieu1').click(function (){
        if($(".mt1").val()!="")
        {
            noidung=$(".mt1").val();
            $(".muctieukhoahoc1").append("<div class='nodeparent'><span class='form-control node1'>"+noidung+"</span><br><div class='delete' style='cursor: pointer'>Xóa</div> </div>");
            $(".mt1").val("");
        }

    });
    $('#btnMucTieu2').click(function (){
        if($(".mt2").val()!="")
        {
            noidung=$(".mt2").val();

            $(".muctieukhoahoc2").append("<div class='nodeparent'><span class='form-control node2'>"+noidung+"</span><br><div class='delete' style='cursor: pointer'>Xóa</div> </div>");
            $(".mt2").val("");
        }

    })
    $('#btnMucTieu3').click(function (){
        if($(".mt3").val()!="")
        {
            noidung=$(".mt3").val();
            $(".muctieukhoahoc3").append("<div class='nodeparent'><span class='form-control node3'>"+noidung+"</span><br><div class='delete' style='cursor: pointer'>Xóa</div></div> ");
            $(".mt3").val("");
        }

    })

    //chức năng nút lưu mục tiêu
    $('#save_goal_course').click(function(event){
        noidungmuctieu1="";
        noidungmuctieu2="";
        noidungmuctieu3="";
        $('.muctieukhoahoc1').find('span.node1').each(function(){
            noidungmuctieu1+=$(this).text()+"[)";
        });
        $('.muctieukhoahoc2').find('span.node2').each(function(){
            noidungmuctieu2+=$(this).text()+"[)";
        });
        $('.muctieukhoahoc3').find('span.node3').each(function(){
            noidungmuctieu3+=$(this).text()+"[)";
        });

        var idkh=$('#course_id').val();
        var muctieu1=noidungmuctieu1;
        var muctieu2=noidungmuctieu2;
        var muctieu3=noidungmuctieu3;
        var ajaxfunctions=$("#linkajaxfunctions").val();
        $.ajax({
            type : "POST", // chọn phương thức gửi là get
            url : ajaxurl, // gửi ajax đến file result.php
            data : { // Danh sách các thuộc tính sẽ gửi đi
                action: 'themmuctieu',
                security: $('#security').val(),
                idcourse: idkh,
                goal1: muctieu1,
                goal2: muctieu2,
                goal3: muctieu3
            },
            success : function (result){
                var active=$('#course_creation_tabs li.active');
                active.removeClass('active');
                $('#goal-course').removeClass('active');
                active.addClass('done');
                $('#course_creation_tabs li.done').next().addClass('active');
                $('#course_settings').addClass('active');
                $('#course_settings_help').addClass('active');
                $('#save_goal_course').addClass('disappearbtn');
                $('#save_goal_editcourse').removeClass('disappearbtn');
            }
        });
    });


    $('#save_goal_editcourse').click(function(event){
        noidungmuctieu1="";
        noidungmuctieu2="";
        noidungmuctieu3="";
        $('.muctieukhoahoc1').find('span.node1').each(function(){
            noidungmuctieu1+=$(this).text()+"[)";
        });
        $('.muctieukhoahoc2').find('span.node2').each(function(){
            noidungmuctieu2+=$(this).text()+"[)";
        });
        $('.muctieukhoahoc3').find('span.node3').each(function(){
            noidungmuctieu3+=$(this).text()+"[)";
        });

        var idkh=$('#course_id').val();
        var muctieu1=noidungmuctieu1;
        var muctieu2=noidungmuctieu2;
        var muctieu3=noidungmuctieu3;
        var ajaxfunctions=$("#linkajaxfunctions").val();
        $.ajax({
            type : "POST", // chọn phương thức gửi là get
            url : ajaxurl, // gửi ajax đến file result.php
            data : { // Danh sách các thuộc tính sẽ gửi đi
                action: 'capnhatmuctieu',
                security: $('#security').val(),
                idcourse: idkh,
                goal1: muctieu1,
                goal2: muctieu2,
                goal3: muctieu3
            },
            success : function (result){
                var active=$('#course_creation_tabs li.active');
                active.removeClass('active');
                $('#goal-course').removeClass('active');
                $('#goal-course-help').removeClass('active');
                active.addClass('done');
                $('#course_creation_tabs li.done').next().addClass('active');
                $('#course_settings').addClass('active');
                $('#course_settings_help').addClass('active');
                $('#save_goal_course').addClass('disappearbtn');
                $('#save_goal_editcourse').removeClass('disappearbtn');
            }
        });
    });
//Xóa mục tiêu
    $('body').delegate('.delete','click',function(event){
        $(this).parents('.nodeparent').remove();
    });
    /*End khải*/
    $('#muctieu1').focus(function(){
        $("#li1").addClass("active");
        $("#li2").removeClass("active");
        $("#li3").removeClass("active");

    });
    $('#muctieu2').focus(function(){
        $("#li1").removeClass("active");
        $("#li2").addClass("active");
        $("#li3").removeClass("active");
    });
    $('#muctieu3').focus(function(){
        $("#li1").removeClass("active");
        $("#li2").removeClass("active");
        $("#li3").addClass("active");
    });


    //Xử lý sự kiện khi click vào nút edit unit thì sẽ hiện ra phần chọn nội dung unit
    $('body').delegate('.edit_content','click',function(event){
        $(this).parent().parent().find('.hidden_button_description').find('.box_shadow_unit').fadeOut();
        $(this).parent().parent().find('.hidden_button_description').find('.box_shadow_content_unit').fadeIn();
        $(this).parent().find('.header_content_unit').fadeIn();

    });


    $('body').delegate('.close-btn','click',function(event){
        $(this).parent().parent().parent().find('.hidden_button_description').find('.box_shadow_content_unit').fadeOut();
        $(this).parent().fadeOut();
        $(this).parent().parent().parent().find('.hidden_button_description').find('.box_shadow_unit').fadeIn();

    });



    //
    $('body').delegate('.curriculum  .menu_delete','click',function(event){
        event.preventDefault();
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
            confirmButton: wplms_front_end_messages.delete_confrim_button,
            cancelButton: vibe_course_module_strings.cancel
        });
    });
    /*Thêm editor */
    $('body').delegate('.multiple-choice','click',function(event){
        var This = $(this);

        $.ajax({
            type : "POST", // chọn phương thức gửi là get
            url : ajaxurl, // gửi ajax đến file result.php
            data : { // Danh sách các thuộc tính sẽ gửi đi
                action: 'create_multiple_choice',
                security: $('#security').val()
            },
            success : function (result){
                This.parent().parent().find('.NoiDung').append(result);
                This.parent().parent().find('.add_content_quiz').css("display","none");
                This.parent().parent().find('.titleQuestions').css("display","none");
            }
        });
    });
    $('body').delegate('.true-false','click',function(event){
        var This = $(this);

        $.ajax({
            type : "POST", // chọn phương thức gửi là get
            url : ajaxurl, // gửi ajax đến file result.php
            data : { // Danh sách các thuộc tính sẽ gửi đi
                action: 'create_true_false',
                security: $('#security').val()
            },
            success : function (result){
                This.parent().parent().find('.NoiDung').append(result);
                This.parent().parent().find('.add_content_quiz').css("display","none");
                This.parent().parent().find('.titleQuestions').css("display","none");
            }
        });
    });
    $('body').delegate('.fill-in-the-blanks','click',function(event){
        var This = $(this);

        $.ajax({
            type : "POST", // chọn phương thức gửi là get
            url : ajaxurl, // gửi ajax đến file result.php
            data : { // Danh sách các thuộc tính sẽ gửi đi
                action: 'create_fill_in_the_blanks',
                security: $('#security').val()
            },
            success : function (result){
                This.parent().parent().find('.NoiDung').append(result);
                This.parent().parent().find('.add_content_quiz').fadeOut(500);

            }
        });
    });

    //Xóa câu hỏi
    $('body').delegate('.traloi','click',function(event){
        var lilast = $(this).parent().parent().index();
        var licount=$(this).parent().parent().parent().find('li').length-1;
        if(lilast==licount)
        {
            var div= '<li class="NoiDungDapAn"> <div><input class="rdb" type="radio" name="rdb"><input type=\'text\' class="form-control traloi txttl" placeholder="Nhập vào câu trả lời"></div><div ><input type=\'text\' class="form-control giaithich txt" placeholder="Giải thích câu trả lời"> </div>  <div class="XoaTraLoi">Xóa</div></li>';
            $(this).parent().parent().parent().append(div);
        }
    });
    $('body').delegate('.traloi','focus',function(event){
        var lilast = $(this).parent().parent().index();
        var licount=$(this).parent().parent().parent().find('li').length-1;
        if(lilast==licount)
        {
            var div= '<li class="NoiDungDapAn"> <div><input class="rdb" type="radio" name="rdb"><input type=\'text\' class="form-control traloi txttl" placeholder="Nhập vào câu trả lời"></div><div ><input type=\'text\' class="form-control giaithich txt" placeholder="Giải thích câu trả lời"> </div>  <div class="XoaTraLoi">Xóa</div></li>';
            $(this).parent().parent().parent().append(div);
        }
    });
    $('body').delegate('.XoaTraLoi,.ckbXoaTraLoi','click',function(event){
        if($(this).parent().index()==0)
        {
            return;
        };
        $(this).parent('.NoiDungDapAn').remove();
    });
    //Xử lý sự kiện khi click vào nút edit unit thì sẽ hiện ra phần chọn nội dung adquestion
    $('body').delegate('.add-question','click',function(event){
        $(this).parent().parent().find('.hidden_button_description').find('.box_shadow_content_quiz').fadeIn();
        $(this).parent().find('.header_content_unit').fadeIn();
        $(this).parent().parent().find('.hidden_button_description').find('.NoiDung').find('.li-content').fadeIn(100);

    });

    $('body').delegate('.lecture_icon_quiz','click',function(event){
        $(this).parent().parent().parent().find('.TNMultiple').parent().fadeOut();
        $(this).parent().parent().parent().find('.TNTrueFalse').parent().fadeOut();
        $(this).parent().parent().parent().find('.TNFillInBlank').parent().fadeOut();
        $(this).parent().parent().parent().find('.hidden_button_description').find('.box_shadow_content_unit').fadeOut();
        $(this).parent().fadeOut();
        $(this).parent().parent().parent().find('.hidden_button_description').find('.box_shadow_unit').fadeIn();
    });


    //Chọn câu hỏi
    $('body').delegate('.LuuCauHoi','click',function(event){
        //id câu hỏi
        var This = $(this);
        var quiz_id=$(this).closest('.new_quiz').find('.add-question').attr('data-id');
        var questiontitle = $(this).parent().parent().parent().parent().find('.titleTN').val();
        var questioncontent=$(this).parent().parent().parent().parent().find('.editor').val();
        //loại câu hỏi
        var vibe_question_type="single";
        //Các đáp án
        var vibe_question_options = [];
        var vibe_question_answer="1";
        var vibe_question_hint="";


        var vibe_question_explaination="";
        $(this).parent().parent().find('.DapAn').find('li').each(function(){
            if($(this).index()==$(this).parent().find('li').length-1)
            {
                return false;
            }
            if($(this).find('[name="rdb"]:checked').length!=0)
            {
                //Vị trí đáp án đúng
                vibe_question_answer = $(this).index()+1;
                //Gợi ý
                vibe_question_hint ="";
                vibe_question_explaination=$(this).find('.giaithich').val();
            }
            var option=$(this).find('.txttl').val();
            var data = {
                option: option
            };
            vibe_question_options.push(data);
        });
        if(vibe_question_explaination=='')
        {
            alert('Bạn chưa chọn đáp án đúng hoặc chưa nhập lời giải thích');
            return;
        }
        $.ajax({
            type: "POST",
            url: ajaxurl,
            data: { action: 'create_questions',
                security: $('#security').val(),
                qtitle: questiontitle,
                qcontent: questioncontent,
                vibe_question_type:vibe_question_type,
                vibe_question_options:JSON.stringify(vibe_question_options),
                vibe_question_answer:vibe_question_answer,
                vibe_question_hint:vibe_question_hint,
                vibe_question_explaination:vibe_question_explaination
            },
            cache: false,
            success: function (result) {
                var questionid = $(result).filter('.questionid').val();

                //Ẩn câu hỏi vừa thêm
                This.parent().parent().parent().parent().parent().find('.TNMultiple').fadeOut(100);
                //Add tiêu đề câu hỏi vào div subtitleTN
                var stt = This.parent().parent().parent().parent().parent().index()+1;
                var divTitle = '<div class="question-title"><div class="nametitle" style="float:left"><b class="stt">'+stt+'.</b><span class="name_title"> '+questiontitle+'</span></div>' + '<div class="question-type"> Multiple-choice </div><div class="question-edit" ><span class="Sua">Sửa </span><span class="Xoa">Xóa</span></div></div><div style="clear:both"></div>';
                This.parent().parent().parent().parent().parent().find('.subtitleTN').append(divTitle);
                This.parent().parent().parent().parent().parent().find('.subtitleTN').removeClass('disappearbtn');
                This.parent().parent().parent().parent().parent().parent().parent().find('.add_content_quiz').css("display","block");
                This.addClass('disappearbtn');
                This.parent().find('.CapNhapCauHoi').removeClass('disappearbtn');
                This.parent().find('.CapNhapCauHoi').attr('data-id',questionid);
                This.parent().parent().parent().parent().parent().parent().parent().find('.titleQuestions').css('display','block');
                //Lưu tất cả các câu hỏi vào quiz
                var questions = [];
                This.closest('.NoiDung').find('.li-content').each(function() {
                    var qid=$(this).find('.id-question').attr('data-id');
                    /*qmarks=$(this).find('.question_marks').val();*/
                    var data = {
                        ques: qid
                        /*  marks: qmarks*/

                    };
                    questions.push(data);
                });
                $.ajax({
                    type: "POST",
                    url: ajaxurl,
                    data: { action: 'saves_quiz_settings',
                        quiz_id: quiz_id,
                        questions: JSON.stringify(questions)
                    },
                    cache: false,
                    success: function (result) {
                    }
                });
            }
        });
    });

    //Edit câu trắc nghiệm
    $('body').delegate('.Sua','click',function(event){
        $(this).parent().parent().parent().parent().find('.TNMultiple').fadeIn(100);
        $(this).parent().parent().parent().parent().find('.TNTrueFalse').fadeIn(100);
        $(this).parent().parent().parent().parent().find('.TNMultiplecorrect').fadeIn(100);
        $(this).parent().parent().parent().parent().parent().parent().parent().find('.add_content_quiz').css("display","none");
        $(this).parent().parent().parent().parent().parent().find(".li-content").find('.subtitleTN').css('display','none');
        $(this).parent().parent().parent().parent().parent().parent().find(".titleQuestions").css('display','none');

    });
    //Cập nhật câu trắc nghiệm
    $('body').delegate('.CapNhapCauHoi','click',function(event){
        //id câu hỏi
        var This = $(this);
        var id=$(this).attr('data-id');
        var questiontitle = $(this).parent().parent().parent().parent().find('.titleTN').val();
        var questioncontent=$(this).parent().parent().parent().parent().find('.editor').val();

        //loại câu hỏi
        var vibe_question_type="multiple";
        //Các đáp án
        var vibe_question_options = [];
        var vibe_question_answer;
        var vibe_question_hint;
        var vibe_question_explaination;
        $(this).closest('.li-content').find('.DapAn').find('li').each(function(){
            if($(this).find('[name="rdb"]:checked').length!=0)
            {
                //Vị trí đáp án đúng
                vibe_question_answer = $(this).index()+1;
                //Gợi ý
                vibe_question_hint ="";
                vibe_question_explaination=$(this).find('.giaithich').val();
            }
            var option=$(this).find('.txttl').val();
            var data;
            if(option!="")
            {
                data = {
                    option: option
                };

            }
            vibe_question_options.push(data);

        });
        if(vibe_question_answer=='')
        {
            alert('Hãy chọn đáp án đúng');
            return;
        }
        if(vibe_question_explaination=='')
        {
            alert('Hãy nhập vào lời giải thích');
            return;
        }
        $.ajax({
            type: "POST",
            url: ajaxurl,
            data: { action: 'update_questions',
                security: $('#security').val(),
                id: id,
                qtitle: questiontitle,
                qcontent: questioncontent,
                vibe_question_type:vibe_question_type,
                vibe_question_options:JSON.stringify(vibe_question_options),
                vibe_question_answer:vibe_question_answer,
                vibe_question_hint:vibe_question_hint,
                vibe_question_explaination:vibe_question_explaination
            },
            cache: false,
            success: function (html) {
                //Ẩn câu hỏi vừa thêm
                This.parent().parent().parent().parent().parent().find('.TNMultiple').fadeOut(100);
                This.parent().parent().parent().parent().parent().parent().parent().find('.add_content_quiz').css("display","block");
                This.parent().parent().parent().parent().parent().parent().find('.li-content').find('.subtitleTN').css('display','block');
                This.parent().parent().parent().parent().parent().find('.subtitleTN').find('.question-title').find('.nametitle').find('.name_title').empty();
                This.parent().parent().parent().parent().parent().find('.subtitleTN').find('.question-title').find('.nametitle').find('.name_title').append(" "+questiontitle);
                This.parent().parent().parent().parent().parent().parent().parent().find('.titleQuestions').css('display','block');
            }
        });
    });
    $('body').delegate('.HuyCauHoi','click',function(event){
        var This = $(this);

        /*   This.parent().parent().parent().parent().parent().find('.TNTrueFalse').fadeOut(100);
         This.parent().parent().parent().parent().parent().find('.TNMultiplecorrect').fadeOut(100);*/
        This.parent().parent().parent().parent().parent().parent().parent().find('.add_content_quiz').css("display","block");
        This.parent().parent().parent().parent().parent().parent().find('.li-content').find('.subtitleTN').css('display','block');
        This.parent().parent().parent().parent().parent().parent().parent().find('.titleQuestions').css('display','block');
        if(This.parent().find('.id-question').attr('data-id')=="")
        {

            This.closest('.li-content').remove();
        }
        else
        {

            This.closest('.li-content').find('.questions').fadeOut(100);

        }

    });
    $('body').delegate('.Xoa','click',function(event){
        var id  = $(this).closest('.li-content').find('.id-question').attr('data-id');
        var This = $(this);
        var quiz_id=$(this).closest('.new_quiz').find('.title').attr('data-id');

        $.ajax({
            type: "POST",
            url: ajaxurl,
            data: { action: 'delete_questions',
                security: $('#security').val(),
                id: id

            },
            cache: false,
            success: function (html) {
                //cập nhật lại thứ tự
                var stt = This.closest('.li-content').find('.stt').text();

                var i=1;
                This.closest('.NoiDung').find('.li-content').each(function(){
                    if($(this).find('.stt').text()!=stt)
                    {
                        $(this).find('.stt').text(i+'.');
                        i++;
                    }
                });
                var idq = This.closest('.li-content').find('.id-question').attr("data-id");
                //list các li câu trắc nghiệm
                var listqs=This.closest('.NoiDung').find('.li-content');


                //Cập nhật lại question trong quiz
                var questions = [];
                listqs.each(function() {
                    if($(this).find('.id-question').attr("data-id")!=idq)
                    {
                        var qid=$(this).find('.id-question').attr('data-id');

                        /*qmarks=$(this).find('.question_marks').val();*/
                        var data = {
                            ques: qid
                            /*  marks: qmarks*/
                        };
                        questions.push(data);
                    }

                });

                $.ajax({
                    type: "POST",
                    url: ajaxurl,
                    data: { action: 'saves_quiz_settings',
                        quiz_id: quiz_id,
                        questions: JSON.stringify(questions)
                    },
                    cache: false,
                    success: function (result) {
                        This.parent().parent().parent().parent().remove();
                    }
                });
            }});
    });
    //Xử lý trắc nghiệm true false
    /*   $('body').delegate('.HuyTrueFalse','click',function(event){
     var This = $(this);
     This.parent().parent().parent().parent().parent().find('.TNTrueFalse').fadeOut(100);
     This.parent().parent().parent().parent().parent().parent().parent().find('.add_content_quiz').css("display","block");
     This.parent().parent().parent().parent().parent().parent().find('.li-content').find('.subtitleTN').css('display','block');
     This.parent().parent().parent().parent().parent().parent().parent().find('.titleQuestions').css('display','block');
     });*/
    $('body').delegate('.LuuTrueFalse','click',function(event){
        //id câu hỏi
        var This = $(this);
        var quiz_id=$(this).closest('.new_quiz').find('.add-question').attr('data-id');
        var questiontitle = $(this).parent().parent().parent().parent().find('.titleTN').val();
        var questioncontent=$(this).parent().parent().parent().parent().find('.editor').val();
        //loại câu hỏi
        var vibe_question_type="truefalse";
        //Các đáp án

        var vibe_question_answer="1";
        /* var vibe_question_hint="";*/
        $(this).parent().parent().find('.DapAn').find('li').each(function(){
            var option = $(this).index();
            if($(this).find('[name="rdbTrueFalse"]:checked').length!=0)
            {
                if(option==0)
                {
                    vibe_question_answer='1';
                }
                else
                {
                    vibe_question_answer='0';
                }
            }
        });
        $.ajax({
            type: "POST",
            url: ajaxurl,
            data: { action: 'create_questions_truefalse',
                security: $('#security').val(),
                qtitle: questiontitle,
                qcontent: questioncontent,
                vibe_question_type:vibe_question_type,
                vibe_question_answer:vibe_question_answer


            },
            cache: false,
            success: function (result) {
                var questionid = $(result).filter('.questionid').val();
                //Ẩn câu hỏi vừa thêm
                This.parent().parent().parent().parent().parent().find('.TNTrueFalse').fadeOut(100);
                //Add tiêu đề câu hỏi vào div subtitleTN
                var stt = This.parent().parent().parent().parent().parent().index()+1;
                var divTitle = '<div class="question-title"><div class="nametitle" style="float:left"><b class="stt">'+stt+'.</b><span class="name_title"> '+questiontitle+'</span></div>' + '<div class="question-type"> True-False</div><div class="question-edit" ><span class="Sua">Sửa </span><span class="Xoa">Xóa</span></div></div><div style="clear:both"></div>';
                This.parent().parent().parent().parent().parent().find('.subtitleTN').append(divTitle);
                This.parent().parent().parent().parent().parent().find('.subtitleTN').removeClass('disappearbtn');
                This.parent().parent().parent().parent().parent().parent().parent().find('.add_content_quiz').css("display","block");
                This.addClass('disappearbtn');
                This.parent().find('.CapNhatTrueFalse').removeClass('disappearbtn');
                This.parent().find('.CapNhatTrueFalse').attr('data-id',questionid);
                This.parent().parent().parent().parent().parent().parent().parent().find('.titleQuestions').css('display','block');
                //Lưu tất cả các câu hỏi vào quiz
                var questions = [];
                This.closest('.NoiDung').find('.li-content').each(function() {
                    var qid=$(this).find('.id-question').attr('data-id');
                    /*qmarks=$(this).find('.question_marks').val();*/
                    var data = {
                        ques: qid
                        /*  marks: qmarks*/
                    };
                    questions.push(data);

                });
                $.ajax({
                    type: "POST",
                    url: ajaxurl,
                    data: { action: 'saves_quiz_settings',
                        quiz_id: quiz_id,
                        questions: JSON.stringify(questions)
                    },
                    cache: false,
                    success: function (result) {
                    }
                });
            }
        });
    });
    //Cập nhật true false
    $('body').delegate('.CapNhatTrueFalse','click',function(event){
        //id câu hỏi
        var This = $(this);
        var id=$(this).attr('data-id');
        var questiontitle = $(this).parent().parent().parent().parent().find('.titleTN').val();
        var questioncontent=$(this).parent().parent().parent().parent().find('.editor').val();
        //loại câu hỏi
        var vibe_question_type="truefalse";
        //Các đáp án
        var vibe_question_answer="1";
        /* var vibe_question_hint="";*/
        $(this).parent().parent().find('.DapAn').find('li').each(function(){
            var option = $(this).index();
            if($(this).find('[name="rdbTrueFalse"]:checked').length!=0)
            {
                if(option==0)
                {
                    vibe_question_answer='1';
                }
                else
                {
                    vibe_question_answer='0';
                }
            }
        });
        $.ajax({
            type: "POST",
            url: ajaxurl,
            data: { action: 'update_questions',
                security: $('#security').val(),
                id: id,
                qtitle: questiontitle,
                qcontent: questioncontent,
                vibe_question_type:vibe_question_type,
                vibe_question_answer:vibe_question_answer

            },
            cache: false,
            success: function (html) {

                //Ẩn câu hỏi vừa thêm
                This.parent().parent().parent().parent().parent().find('.TNTrueFalse').fadeOut(100);
                This.parent().parent().parent().parent().parent().parent().parent().find('.add_content_quiz').css("display","block");
                This.parent().parent().parent().parent().parent().parent().find('.li-content').find('.subtitleTN').css('display','block');
                This.parent().parent().parent().parent().parent().find('.subtitleTN').find('.question-title').find('.nametitle').find('.name_title').empty();
                This.parent().parent().parent().parent().parent().find('.subtitleTN').find('.question-title').find('.nametitle').find('.name_title').append(" "+questiontitle);
                This.parent().parent().parent().parent().parent().parent().parent().find('.titleQuestions').css('display','block');
            }
        });
    });
    //Tạo câu hỏi multiple correct
    $('body').delegate('.multiple-correct','click',function(event){
        var This = $(this);
        $.ajax({
            type : "POST", // chọn phương thức gửi là get
            url : ajaxurl, // gửi ajax đến file result.php
            data : { // Danh sách các thuộc tính sẽ gửi đi
                action: 'create_multiple_correct',
                security: $('#security').val()
            },
            success : function (result){
                This.parent().parent().find('.NoiDung').append(result);
                This.parent().parent().find('.add_content_quiz').css("display","none");
                This.parent().parent().find('.titleQuestions').css("display","none");
            }
        });
    });
    $('body').delegate('.ckbtraloi','click',function(event){
        var lilast = $(this).parent().parent().index();
        var licount=$(this).parent().parent().parent().find('li').length-1;
        if(lilast==licount)
        {
            var div= '<li class="NoiDungDapAn"> <div><input type="checkbox" name="ckbmultilple" class="ckbmultilple"><input type=\'text\' class="form-control ckbtraloi txttl" placeholder="Nhập vào câu trả lời"></div><div ><input type=\'text\' class="form-control giaithich txt" placeholder="Giải thích câu trả lời"> </div>  <div class="XoaTraLoi">Xóa</div></li>';
            $(this).parent().parent().parent().append(div);
        }
    });
    $('body').delegate('.ckbtraloi','focus',function(event){
        var lilast = $(this).parent().parent().index();
        var licount=$(this).parent().parent().parent().find('li').length-1;
        if(lilast==licount)
        {
            var div= '<li class="NoiDungDapAn"> <div><input type="checkbox" name="ckbmultilple" class="ckbmultilple"><input type=\'text\' class="form-control ckbtraloi txttl textboxtl" placeholder="Nhập vào câu trả lời"></div>  <div class="ckbXoaTraLoi">Xóa</div></li>';
            $(this).parent().parent().parent().append(div);
        }
    });
    //Tạo câu hỏi lưu multiple correct
    $('body').delegate('.LuuMultipleCorrect','click',function(event){
        //id câu hỏi
        var This = $(this);
        var quiz_id=$(this).closest('.new_quiz').find('.add-question').attr('data-id');
        var questiontitle = $(this).parent().parent().parent().parent().find('.titleTN').val();
        var questioncontent=$(this).parent().parent().parent().parent().find('.editor').val();
        //loại câu hỏi
        var vibe_question_type="multiple";
        //Các đáp án
        var vibe_question_options = [];
        var vibe_question_answer="";
        var vibe_question_hint="";
        var vibe_question_explaination="";
        $(this).parent().parent().find('.DapAn').find('li').each(function(){
            if($(this).index()==$(this).parent().find('li').length-1)
            {
                return false;
            }
            if($(this).find('[name="ckbmultilple"]:checked').length!=0)
            {
                //Vị trí đáp án đúng
                vibe_question_answer += $(this).index()+1+",";
                //Gợi ý
                vibe_question_hint ="";
            }
            var option=$(this).find('.ckbtraloi').val();
            var data = {
                option: option
            };
            vibe_question_options.push(data);

        });

        vibe_question_explaination=This.parent().find('.txtGiaiThich').val();
        vibe_question_answer=vibe_question_answer.substr(0,vibe_question_answer.length-1);
        if(vibe_question_answer=="")
        {
            alert("Hãy chọn lựa chọn đúng");
            return;
        }
        if(vibe_question_explaination=="")
        {
            alert("Hãy nhập vào lời giải thích");
            return;
        }
        /*  alert("questiontitle: "+questiontitle);
         alert("questioncontent: "+questioncontent);
         alert("vibe_question_type: "+vibe_question_type);
         alert("vibe_question_options: "+vibe_question_options);
         alert("vibe_question_answer: "+vibe_question_answer);
         alert("vibe_question_hint: "+vibe_question_hint);
         alert("vibe_question_explaination: "+vibe_question_explaination);*/
        $.ajax({
            type: "POST",
            url: ajaxurl,
            data: { action: 'create_questions_multiplecorrect',
                security: $('#security').val(),
                qtitle: questiontitle,
                qcontent: questioncontent,
                vibe_question_type:vibe_question_type,
                vibe_question_options:JSON.stringify(vibe_question_options),
                vibe_question_answer:vibe_question_answer,
                vibe_question_hint:vibe_question_hint,
                vibe_question_explaination:vibe_question_explaination
            },
            cache: false,
            success: function (result) {
                var questionid = $(result).filter('.questionid').val();
                //Ẩn câu hỏi vừa thêm
                This.parent().parent().parent().parent().parent().find('.TNMultiplecorrect').fadeOut(100);
                //Add tiêu đề câu hỏi vào div subtitleTN
                var stt = This.closest('.li-content').index()+1;
                var divTitle = '<div class="question-title"><div class="nametitle" style="float:left"><b class="stt">'+stt+'.</b><span class="name_title"> '+questiontitle+'</span></div>' + '<div class="question-type"> Multiple-correct </div><div class="question-edit" ><span class="Sua">Sửa </span><span class="Xoa">Xóa</span></div></div><div style="clear:both"></div>';
                This.closest('.li-content').find('.subtitleTN').append(divTitle);
                This.closest('.li-content').find('.subtitleTN').removeClass('disappearbtn');
                This.parent().parent().parent().parent().parent().parent().parent().find('.add_content_quiz').css("display","block");
                This.addClass('disappearbtn');
                This.parent().find('.CapNhapMultipleCorrect').removeClass('disappearbtn');
                This.parent().find('.CapNhapMultipleCorrect').attr('data-id',questionid);
                This.parent().parent().parent().parent().parent().parent().parent().find('.titleQuestions').css('display','block');

                //Lưu tất cả câu hỏi vào quiz
                var questions = [];
                This.closest('.NoiDung').find('.li-content').each(function() {
                    var qid=$(this).find('.id-question').attr('data-id');
                    qmarks=$(this).find('.question_marks').val();
                    var data = {
                        ques: qid,
                        marks: qmarks

                    };
                    questions.push(data);
                });
                $.ajax({
                    type: "POST",
                    url: ajaxurl,
                    data: { action: 'saves_quiz_settings',
                        quiz_id: quiz_id,
                        questions: JSON.stringify(questions)
                    },
                    cache: false,
                    success: function (result) {
                    }
                });
            }
        });
    });
    //Cập nhật câu trắc nghiệm multiple corrects
    $('body').delegate('.CapNhapMultipleCorrect','click',function(event){
        //id câu hỏi
        var This = $(this);
        var id=$(this).attr('data-id');
        var questiontitle = $(this).closest('.li-content').find('.titleTN').val();
        var questioncontent=$(this).closest('.li-content').find('.editor').val();
        //loại câu hỏi
        var vibe_question_type="multiple";
        //Các đáp án
        var vibe_question_options = [];
        var vibe_question_answer="";
        var vibe_question_hint="";
        var vibe_question_explaination="";
        $(this).closest('.li-content').find('.DapAn').find('li').each(function(){
            if($(this).find('[name="ckbmultilple"]:checked').length!=0)
            {
                //Vị trí đáp án đúng
                vibe_question_answer += $(this).index()+1+",";
                //Gợi ý
                vibe_question_hint ="";
            }
            var option=$(this).find('.ckbtraloi').val();
            var data;
            if(option!="")
            {
                data = {
                    option: option
                };
            }
            vibe_question_options.push(data);
        });
        vibe_question_explaination=This.parent().find('.txtGiaiThich').val();
        vibe_question_answer=vibe_question_answer.substr(0,vibe_question_answer.length-1);

        if(vibe_question_answer=="")
        {
            alert("Hãy chọn lựa chọn đúng");
            return;
        }
        if(vibe_question_explaination=="")
        {
            alert("Hãy nhập vào lời giải thích");
            return;
        }
        $.ajax({
            type: "POST",
            url: ajaxurl,
            data: { action: 'update_questions',
                security: $('#security').val(),
                id: id,
                qtitle: questiontitle,
                qcontent: questioncontent,
                vibe_question_type:vibe_question_type,
                vibe_question_options:JSON.stringify(vibe_question_options),
                vibe_question_answer:vibe_question_answer,
                vibe_question_hint:vibe_question_hint,
                vibe_question_explaination:vibe_question_explaination
            },
            cache: false,
            success: function (html) {
                //Ẩn câu hỏi vừa thêm
                This.parent().parent().parent().parent().parent().find('.TNMultiplecorrect').fadeOut(100);
                This.parent().parent().parent().parent().parent().parent().parent().find('.add_content_quiz').css("display","block");
                This.parent().parent().parent().parent().parent().parent().find('.li-content').find('.subtitleTN').css('display','block');
                This.parent().parent().parent().parent().parent().find('.subtitleTN').find('.question-title').find('.nametitle').find('.name_title').empty();
                This.parent().parent().parent().parent().parent().find('.subtitleTN').find('.question-title').find('.nametitle').find('.name_title').append(" "+questiontitle);
                This.parent().parent().parent().parent().parent().parent().parent().find('.titleQuestions').css('display','block');
            }
        });
    });

    // Match anwser
    $('body').delegate('.match-answer','click',function(event){
        var This = $(this);
        $.ajax({
            type : "POST", // chọn phương thức gửi là get
            url : ajaxurl, // gửi ajax đến file result.php
            data : { // Danh sách các thuộc tính sẽ gửi đi
                action: 'create_question_match_answer',
                security: $('#security').val()
            },
            success : function (result){
                This.parent().parent().find('.NoiDung').append(result);
                AddNewEditor (This.closest('.box_shadow_content_quiz').find('.content_editor'),"abc");
                This.parent().parent().find('.add_content_quiz').css("display","none");
                This.parent().parent().find('.titleQuestions').css("display","none");
            }
        });
    });
    //Tạo Editor tự động
    function AddNewEditor(content_editor,id_editor){
        htmlSource = '<textarea class="wp-editor-area editor-content" ></textarea>';
        content_editor.append("<li>"+htmlSource+"</li>");
        tinymce.init({
            selector: ".editor-content",
            menubar : false,
            height:100,
            plugins: [
                "image",
            ],
            toolbar: "insertfile | bold italic | link image ",
        });

    }

    function TaoEditorBinhLuan(content_editor,id_editor){
        htmlSource = '<textarea class="wp-editor-area editor-content"  id="wisSW_Editor' + id_editor + '" ></textarea>';
        content_editor.append(htmlSource);
        tinymce.init({
            selector: ".editor-content",
            menubar : false,
            height:100,
            plugins: [
                "image",
            ],
            toolbar: "insertfile | bold italic | link image ",
        });

    }
    //End Khải

    //bắt sự kiện khi re chuột vào editor thì sẽ tắt drag and drop
    $('body').delegate('.close-btn','mouseleave',function(){
        $('ul.curriculum').sortable({
            revert: true,
            cursor: 'move',
            refreshPositions: true,
            opacity: 0.6,
            scroll:true,
            containment: 'parent',
            placeholder: 'placeholder',
            tolerance: 'pointer',
        });

        //

        /*End anh bình*/
    });



    //bắt sự kiện khi re chuột vào editor thì sẽ tắt drag and drop
    $('body').delegate('.box_shadow_content_unit','mouseenter',function(){
        $('ul.curriculum').sortable("destroy");


    });

    //xử lý sự kiện khi lick vào nút login sẽ kiểm tra xem là đã đăng nhập hay chưa nếu chưa thì chỉnh sửa css lại bằng cách add class
    $('.vbplogin').click(function(event) {
        event.preventDefault();
        var user_id = $('#check_user').val();
        if(user_id ==0){
            $('#vibe_bp_login').addClass('child_vibe_bp_login');
            $('.box-heading').addClass('child-box-heading');
            $('#vbp-login-form').addClass('child-vbp-login-form');
            $('.loginbox-v4').addClass('child-loginbox-v4');
            $('#vibe_bp_login:after').addClass('child_vibe_bp_login:after');
            //$('header').append("<div class='shadow-login'> </div>")
            $('.shadow-login').fadeIn(1);
            $('body').addClass('hidden-scrollbar');
        }else{
	    $('#vibe_bp_login').fadeIn(300);
            $('#vibe_bp_login').toggleClass('active');

	}
        event.stopPropagation();
    });

    $('.shadow-login').click(function(){
        annoidungdangnhap();
    });

    $('.btn-close').click(function(){
        annoidungdangnhap();
    });

    function annoidungdangnhap(){
        $('.shadow-login').fadeOut(1);
        $('#vibe_bp_login').removeClass("active");
        $('#vibe_bp_login').fadeOut();
        $('body').removeClass('hidden-scrollbar');
    }

    //Xử lý dropdow menu bên trái
    $('.nav_doc li a').parent().has('ul').mouseover(function(){
        tagOffset = $(this).offset();
        offsetLeft = tagOffset.left;
        offsetRight = tagOffset.right;
        offsetTop = tagOffset.top - 114;

        popupOffsetLeft = offsetLeft + 180;
        closeParent = $(this).closest("ul").attr("class");
        if(closeParent == 'nav_doc'){
            $(this).find('ul').first().css({'posotion':'absolute','visibility' : 'visible', 'left' : popupOffsetLeft + 'px', 'top' : offsetTop + 'px'});
        }else{
            secondOffset = $(this).find('ul').last().parent().offset();
            secondOffsetTop = secondOffset.top - offsetTop - 83;
            secondOffsetLeft = offsetLeft - 10 + 40;
            $(this).find('ul').last().css({'visibility' : 'visible', 'left' : secondOffsetLeft + 'px', 'top' : secondOffsetTop + 'px'});
        }

    });

    //xử lý dropdow menu bên trái
    $('.nav_doc li a').parent().has('ul').mouseout(function() {
        $(this).find('ul').css({'visibility' : 'hidden'});
    });

    //xử lý hiện menu bên trái khi rê chuột vào
    $('.danhsachmenu').click(function(){
        if($(this).hasClass('show')){
            $(this).removeClass('show');
            $('#noidung_menu').fadeOut();
        }else{
            $(this).addClass('show');
            $('#noidung_menu').fadeIn();
        }

    });

    //bắt sự kiện khi re chuột vào editor thì sẽ tắt drag and drop
    $('body').delegate('.box_shadow_content_unit','mouseenter',function(){
        $('ul.curriculum').sortable("destroy");


    });

    //xử lý sự kiện khi lick vào nút login sẽ kiểm tra xem là đã đăng nhập hay chưa nếu chưa thì chỉnh sửa css lại bằng cách add class
    $('.vbplogin').click(function(event) {
        event.preventDefault();
        var user_id = $('#check_user').val();
        if(user_id ==0){
            $('#vibe_bp_login').addClass('child_vibe_bp_login');
            $('.box-heading').addClass('child-box-heading');
            $('#vbp-login-form').addClass('child-vbp-login-form');
            $('.loginbox-v4').addClass('child-loginbox-v4');
            $('#vibe_bp_login:after').addClass('child_vibe_bp_login:after');
            //$('header').append("<div class='shadow-login'> </div>")
            $('.shadow-login').fadeIn(1);
            $('body').addClass('hidden-scrollbar');
        }
        event.stopPropagation();
    });

    //Xử lý ẩn nội dung đăng nhập khi click vào login
    $('.shadow-login').click(function(){
        annoidungdangnhap();
    });

    //Xử lý ẩn nội dung đăng nhập khi click vào button close
    $('.btn-close').click(function(){
        annoidungdangnhap();
    });

    function annoidungdangnhap(){
        $('.shadow-login').fadeOut(1);
        $('#vibe_bp_login').removeClass("active");
        $('#vibe_bp_login').fadeOut();
        $('body').removeClass('hidden-scrollbar');
    }

    //Xử lý sự kiện click vào danh mục load nội dung ajax ( chút sửa thành child sau )
    $('body').delegate('.danhmuckhoahoc','click',function(e,k){
        e.stopPropagation(); //dừng sự kiện của cha khi click vào con
        var This = $(this);
        $('.danhmuckhoahoc').removeClass('active_menu');
        if($('.tatcakhoahoc').hasClass('active_menu')){
            $('.tatcakhoahoc').removeClass('active_menu');
        }
        if(k!=1){
            $('.menu_level input[id=level]').prop("checked", true);
            $('.menu_ngonngu input[id=language]').prop("checked", true);
        }
        if(This.hasClass('cha')){
            var id = This.find('.danhmuccha').attr('data-id');
            var name_term = This.find('.danhmuccha').text();
        }else{
            var id = This.find('span').attr('data-id');
            var name_term = This.find('span').text();
        }

        var name_term_language =$('input[name=language]:checked').val();
        var name_term_level = $('input[name=level]:checked').val();

        var name_term_language_text =$('input[name=language]:checked').parent().find('label').text();
        var name_term_level_text = $('input[name=level]:checked').parent().find('label').text();

        var content = "Hiện tại chưa có khóa học";

        $('.anmenu').css('display','none');

        $('.content-course-cat').addClass('loading');
        $('.content_course').css('opacity',0.2);
        $('.chemanhinh').show();
        $('#ajaxloader').removeClass('disabled');
        $('.content_course').remove();
        //
        //an danh muc khi click vào danh mục tương ứng
        $.ajax({
            type: "POST",
            url: ajaxurl,
            data: {
                action: 'locdanhmuckhoahoc',
                term_id: id,
            },
            success: function(result){
                $(result).filter('li').each(function(){
                    var classMenu = '.'+$(this).html();
                    $(classMenu).css('display','block');

                });
            }
        });

        $.ajax({
            type : "POST",
            url : ajaxurl,
            data : {
                action: 'hienthikhoahoctrangchu',
                term_id: id,
                name_term: name_term,
                name_term_language: name_term_language,
                name_term_level: name_term_level,
                name_term_language_text: name_term_language_text,
                name_term_level_text: name_term_level_text
            },
            cache: false,
            success: function(result){
                $('#ajaxloader').addClass('disabled');
                $('.chemanhinh').hide();
                //$('.content-course-cat').append(result);
                $('.content-course-cat').append('<div class="content_course">'+result+'<div>');
                $('.content_course').css('opacity',1);
                $('.content-course-cat').removeClass('loading');
                This.addClass('active_menu');
            }

        })
    });
    /*Khải phần unit course*/
    /*  phần post thảo luận*/
    $('.btnAddDiscussion').click(function(){





        $('#wp-txtThaoLuan-editor-container').empty();
        htmlSource='<textarea id="txtThaoLuan" class="wp-editor-area editor-content" ></textarea>';

        $('#wp-txtThaoLuan-editor-container').prepend(htmlSource);
        tinymce.init({
            selector: ".editor-content",
            menubar : false,
            height:100,
            plugins: [
                "image",
            ],
            toolbar: "insertfile | bold italic | link image ",
        });


        $('.contentdcs').fadeIn(500);

    });
    $('body').delegate('.btnClose', 'click', function(){
            $('.contentdcs').fadeOut(500);
        }
    );


    /*Nút trở về*/
    //$('.backtocourse').click(function(){
    //    location.reload(true);
    //});
    $('body').delegate('.backtocourse','click',function(){
        $('.TV').css("display","none");
        $('.TV').removeClass('kiemtrascrollhockhoahoc');
        $('body').removeClass("hidden-scrollbar");
        $('.c').trigger('click');
        $('body').css('overflow-y', 'scroll');
        $('#unit_content').empty();
        $('.hide_comment').trigger('click');
        var id=$(this).attr('data-id');

        //code mới
        $.ajax({
            type: "POST",
            url: ajaxurl,
            data: { action: 'load_phia_tren_khoa_hoc',
                id: id
            },
            cache: false,
            success: function (result) {
                $('.iconmediaplayer').empty();
                $('.iconmediaplayer').append(result);
            }
        });

        //load lại danh sách bình luận
        var noidungtimkiem = $('.timkiembinhluan').val();
        var id_course=$(this).attr('data-id');
        $('.NoiDungThaoLuan').empty();
        $('.NoiDungThaoLuan').append('<i class="loadingreview icon-refresh glyphicon-refresh-animate"></i>Đang tải...');
        $.ajax({
            type: "POST",
            url: ajaxurl,
            data:{
                action: "timkiembinhluan",
                tieude: noidungtimkiem,
                id :id_course
            },
            success: function(result1){
                $('.NoiDungThaoLuan').empty();
                $('.NoiDungThaoLuan').append(result1);
            }
        });
    });

    //$('.unit_line a').click(function(){
    //    $('.TV').css("display","block");
    //    $('body').addClass("hidden-scrollbar");
    //});

    //Xử lý load ra tất cả unit khi mới vào học khóa hsọc
    $('.course_timeline ul li.section').addClass('show');
    $('.course_timeline ul li.section').nextAll().addClass('show');



    $('body').delegate('.unit_line a','click',function(){
        $('.TV').addClass('kiemtrascrollhockhoahoc');
        $('.unit_line').removeClass('active1');
        $id=$(this).parent().attr('id');
        $id='.'+$id;
        $($id).addClass('active1');
        $('.TV').css("display","block");
        $('.TV').find('#unit_content').css('overflow','auto');
        $('body').addClass("hidden-scrollbar");
        $('body').css('overflow', 'hidden');
        $('.hide_comment').trigger('click');
    });
    $('.iconmediaplayer').click(function(){
        $('.hide_comment').trigger('click');
        $('.TV').find('#unit_content').css('overflow','auto');
        $('.TV').addClass('kiemtrascrollhockhoahoc');
        if($(this).find('.unithientai').hasClass('kiemtra')){
            var id=$(this).find('.unithientai').attr('data-id');
            if(id){
                var id_unit='#unit'+id+' a';
                $(id_unit).trigger('click');
                //alert(id_unit);
            }else{
                $('.course_timeline ul li.unit_line:first a').trigger('click');
            }

        }else{
            $('.TV').css("display","block");
            $('body').addClass("hidden-scrollbar");
        }
    });
    /* post thảo luận course*/
    $('.btnThaoLuan').click(function(){
        var This = $(this);
        var course_id=$('.course_id').val();
        var title_discussion_course=$('.title-discussion').val();
        var content_discusstion_course=get_tinymce_content('txtThaoLuan');
        var user_id = $('.user_id').val();
        var warning='<i class="icon-sun-stroke animated spin"></i>';
        var parentcomment = 0;
        if(This.hasClass("warning"))
        {
            return;
        }
        This.prepend(warning);
        This.addClass("warning");
        var kt=0;
        if(title_discussion_course==""){
            alert("Bạn vui lòng nhập tiêu đề bình luận");
            This.find("i").remove();
            This.removeClass("warning");
        }else{
            if(content_discusstion_course.length<13 || content_discusstion_course.length>613){
                alert("Nội dung bình luận từ 6 đến 600 ký tự");
                This.find("i").remove();
                This.removeClass("warning");
            }else{
                $('.chemanhinh').show();
                $('#ajaxloader').show();
                $.ajax({
                    type: "POST",
                    url: ajaxurl,
                    data: { action: 'create_discussion',
                        security: $('#security').val(),
                        cdtitle: title_discussion_course,
                        cdcontent: content_discusstion_course,
                        cdcourseid:course_id,
                        cduserid:user_id,
                        parent_comment: parentcomment,
                        kt: kt
                    },
                    cache: false,
                    success: function (result) {
                        $('.chemanhinh').hide();
                        $('#ajaxloader').hide();
                        This.find("i").remove();
                        This.removeClass("warning");
                        $('.title-discussion').val("");
                        $("#txtThaoLuan").val("");
                        /*append thảo luận*/
                        var contentdc= $(result).filter('.result').html();
                        $(".append-content-discussion").prepend(contentdc);
                        $(".contentdcs").fadeOut(200);
                        tinyMCE.activeEditor.setContent('');
                    }
                });
            }
        }

    });

    //Thêm thảo luận con
    $('body').delegate('.btn_comment_child','click',function(){
        var This = $(this);
        var course_id=$(this).closest('.NoiDungCMTUser').attr('data-course-id');
        var course_id_khoahoc=$('.course_id').val();
        var title_discussion_course=$(this).closest('.content_child_comment').find('.title_child_comment:first').val();
        var content_discusstion_course = tinyMCE.get($(this).attr('data-id')).getContent();
        var user_id = $('.user_id').val();
        var warning='<i class="icon-sun-stroke animated spin"></i>';
        var parentcomment = $(this).parent().closest('.NoiDungCMTUser').attr('data-id');
        if(This.hasClass("warning"))
        {
            return;
        }
        This.prepend(warning);
        This.addClass("warning");
        var kt=1;
        if(title_discussion_course==""){
            alert("Bạn vui lòng nhập tiêu đề bình luận");
            This.find("i").remove();
            This.removeClass("warning");
        }else{
            if(content_discusstion_course.length<13 || content_discusstion_course.length>613){
                alert("Nội dung bình luận từ 6 đến 600 ký tự");
                This.find("i").remove();
                This.removeClass("warning");
            }else{
                $('.chemanhinh').show();
                $('#ajaxloader').show();
                $.ajax({
                    type: "POST",
                    url: ajaxurl,
                    data: { action: 'create_discussion_child',
                        security: $('#security').val(),
                        cdtitle: title_discussion_course,
                        cdcontent: content_discusstion_course,
                        cdcourseid:course_id,
                        cduserid:user_id,
                        parent_comment: parentcomment,
                        kt: kt
                    },
                    cache: false,
                    success: function (result) {
                        $('.chemanhinh').hide();
                        $('#ajaxloader').hide();
                        This.find("i").remove();
                        This.removeClass("warning");
                        $('.title_child_comment').val("");
                        $("#txtThaoLuan").val("");
                        /*append thảo luận*/
                        var contentdc= $(result).filter('.result').html();
                        This.closest('.child_comment').find('.content_child_comment_start').append(contentdc);
                        //This.closest('.child_comment').prepend(contentdc);
                        $(".contentdcs").fadeOut(200);
                        tinyMCE.activeEditor.setContent('');
                    }
                });
            }
        }


    });

    /* Lấy nội dung editor*/
    function get_tinymce_content(ideditor){

        //change to name of editor set in wp_editor()
        var editorID = ideditor;

        if (jQuery('#wp-'+editorID+'-wrap').hasClass("tmce-active"))
            var content = tinyMCE.get(editorID).getContent({format : 'html'});
        else
            var content = jQuery('#'+editorID).val();

        return content;
    }
    /*Hiệu chỉnh thảo luận (Xóa sửa)*/
    $('body').delegate('.item-discustion','hover',function(e){
        e.stopPropagation();
        $(this).children().find('.HieuChinh-ds').fadeIn(200);
    });
    $('body').delegate('.item-discustion','mouseleave',function(){
        $(this).find('.HieuChinh-ds').fadeOut(200);
    });
    /*Xóa sửa thảo luận*/
    $('body').delegate('.Xoads','click',function(){

        var r = confirm("Bạn có muốn xóa bài thảo luận này ?");
        if(r==false)
        {
            return;
        }
        var cmt_id = $(this).parent().find('.id-comment-ds').val();
        var This = $(this);
        $.ajax({
            type: "POST",
            url: ajaxurl,
            data: { action: 'delete_comment',
                //security: $('#security').val(),
                cmt_id: cmt_id
            },
            cache: false,
            success: function (result) {
                This.closest('.item-discustion').remove();

            }
        });
    });

    $('body').delegate('.Suads','click',function(){
        if($(this).hasClass('warning'))
        {
            return;
        }
        $(this).addClass('warning');
        $(this).parent().find('.id-comment-ds').val();
        $(this).closest(".item-discustion").find(".content_before").fadeOut(200);
        var title = $(this).closest(".item-discustion").find(".comment-title-user:first").text();
        var content = $(this).closest(".item-discustion").find(".comment-content-user").html();
        var id_cmt = $(this).closest(".item-discustion").find(".id-comment-ds").val();
        if($(this).closest(".item-discustion").hasClass("child")){
            var div_editor = $(this).closest(".item-discustion").find('.edit_content_editor_child');
        }else{
            var div_editor = $(this).closest(".item-discustion").find('.edit_content_editor');
        }
        $(this).css("display","none");
        $(this).parent().find(".Xoads").fadeOut();
        $(this).closest(".item-discustion").find(".edit_content_editor").css("margin-bottom"," 20%");
        $(this).closest(".item-discustion").find(".NoiDungCMTUser").fadeOut();
        tinymce.remove();
        AddNewEditor(div_editor,id_cmt,content,title);
        $(this).removeClass('warning');
    });

    /* $('.Xoads').click(function(){
     var r = confirm("Bạn có muốn xóa bài thảo luận này ?");
     if(r==false)
     {
     return;
     }
     var cmt_id = $(this).parent().find('.id-comment-ds').val();
     var This = $(this);
     $.ajax({
     type: "POST",
     url: ajaxurl,
     data: { action: 'delete_comment',
     security: $('#security').val(),
     cmt_id: cmt_id
     },
     cache: false,
     success: function (result) {
     This.closest('.item-discustion').remove();

     }
     });
     });*/

    /*  $('.Suads').click(function(){
     if($(this).hasClass('warning'))
     {
     return;
     }
     $(this).addClass('warning');
     $(this).parent().find('.id-comment-ds').val();
     $(this).closest(".item-discustion").find(".content_before").fadeOut(200);
     var title = $(this).closest(".item-discustion").find(".comment-title-user").text();
     var content = $(this).closest(".item-discustion").find(".comment-content-user").html();
     var id_cmt = $(this).closest(".item-discustion").find(".id-comment-ds").val();
     var div_editor = $(this).closest(".item-discustion").find('.edit_content_editor');
     $(this).css("display","none");
     $(this).parent().find(".Xoads").fadeOut();
     $(this).closest(".item-discustion").find(".edit_content_editor").css("margin-bottom"," 20%");
     $(this).closest(".item-discustion").find(".NoiDungCMTUser").fadeOut();
     AddNewEditor(div_editor,id_cmt,content,title);
     $(this).removeClass('warning');

     });*/

    $('body').delegate('.btn_edit','click',function(){
        var This = $(this);
        var title = This.closest(".item-discustion").find('.title').val();
        var cmt_id=$(this).closest(".item-discustion").find(".id-comment-ds").val();
        var div_editor = $(this).closest(".item-discustion").find('.edit_content_editor');
        var content_before =$(this).closest(".item-discustion").find(".comment-content-user").html();
        var id_course = $(this).closest('.discussion-content').find('.timkiembinhluan').attr('data-course-id');
        tinymce.remove();
        var kt=0;
        if($(this).closest('.item-discustion').hasClass("child")){
            kt = 1;
        }
        AddNewEditor_appendto(div_editor,cmt_id,content_before,title);
        var content = get_tinymce_content('editor-content'+cmt_id);
        var warning='<i class="icon-sun-stroke animated spin"></i>';

        if(This.hasClass("warning"))
        {
            return;
        }
        This.prepend(warning);
        This.addClass("warning");
        $('.chemanhinh').show();
        $('#ajaxloader').show();
        $.ajax({
            type: "POST",
            url: ajaxurl,
            data: { action: 'update_discussion',
                security: $('#security').val(),
                cmt_id: cmt_id,
                title:title,
                content:content,
                id_course: id_course,
                kt: kt
            },
            cache: false,
            success: function (result) {
                $('.chemanhinh').hide();
                $('#ajaxloader').hide();
                This.find("i").remove();
                This.removeClass("warning");
                This.closest(".item-discustion").find('.NoiDungCMTUser').empty();
                var contentdc= $(result).filter('.result').html();
                This.closest(".item-discustion").find('.NoiDungCMTUser').append(result);
                This.closest(".item-discustion").find('.close_edit_content').trigger('click');

            }
        });
    });

    $('body').delegate('.close_edit_content','click',function(){
        var This = $(this);
        This.closest(".item-discustion").find(".NoiDungCMTUser").fadeIn(200);
        This.closest(".item-discustion").find(".Xoads").fadeIn();
        This.closest(".item-discustion").find(".Suads").fadeIn();
        $(this).closest(".item-discustion").find(".edit_content_editor").css("margin","0");
        $(this).closest(".item-discustion").find(".edit_content_editor_child").css("margin","0");

        This.closest(".item-discustion-delegate").find(".NoiDungCMTUser").fadeIn(200);
        This.closest(".item-discustion-delegate").find(".Xoads-delegate").fadeIn();
        This.closest(".item-discustion-delegate").find(".Suads-delegate").fadeIn();
        $(this).closest(".item-discustion-delegate").find(".edit_content_editor").css("margin","0");
        This.closest('.edit_content_editor').children().remove();
        This.closest('.edit_content_editor_child').children().remove();
    });

    //Tạo Editor edit nội dung
    function AddNewEditorChild(vitri,id_editor,noidung,title,id){
        iconclose = "<div class='close_child_edit_content' style='text-align: right'><i class='icon-close-off-2' style='position: relative;margin: 0;cursor: pointer;color: #999999'></i> </div> ";
        title = iconclose+'<input type="text" class="title_child_comment form-control" value="'+title+'" placeholder="Nhập vào tiêu đề thảo luận" >';
        button = '<div data-course-id= "'+id+'"name="'+id_editor+'" data-id="'+id_editor+'" style="float: right;width: auto;" class="btn btn-success btn_comment_child" >Gửi</div>';
        buttonAddMedia = '<span class="insert-my-media btn btn-success" data-id="'+id+'">Thêm hình ảnh</span>';
        htmlSource = title+'<textarea id="'+id_editor+'" class="wp-editor-area editor-content" >'+noidung+'</textarea>'+button+buttonAddMedia;

        vitri.append(htmlSource);
        tinymce.init({
            selector: ".editor-content",
            menubar : false,
            height:100,
            plugins: [
                "image",
            ],
            toolbar: "insertfile | bold italic | link image ",
        });

    }

    //
    $('body').delegate('.insert-my-media-comment','click',function(){
        var myButton = $(this);
        var buttonEdit = $(myButton).parent().find('iframe').contents().find('.mce-content-body');
        if (this.window === undefined) {
            this.window = wp.media({
                title: 'Thêm tập tin',
                library: {type: 'image'},
                displaySettings: true,
		type: 'image',
                multiple: false,
                button: {text: 'Thêm'}
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

    //Tạo Editor edit nội dung
    function AddNewEditor(vitri,id_editor,noidung,title){
        iconclose = "<div class='close_edit_content' style='text-align: right'><i class='icon-close-off-2' style='position: relative;margin: 0;cursor: pointer;color: #999999'></i> </div> ";
        title = iconclose+'<input type="text" class="title form-control" value="'+title+'" placeholder="Nhập vào tiêu đề thảo luận" >';
        button = '<div style="float: right;width: auto;" class="btn btn-success btn_edit" >Lưu</div>';
        buttonAddMedia = '<span class="insert-my-media btn btn-success" data-id="'+id_editor+'">Thêm hình ảnh</span>';
        htmlSource = title+'<textarea id="editor-content'+id_editor+'" class="wp-editor-area editor-content" >'+noidung+'</textarea>'+button+buttonAddMedia;

        vitri.append(htmlSource);
        tinymce.init({
            selector: ".editor-content",
            menubar : false,
            height:100,
            plugins: [
                "image",
            ],
            toolbar: "insertfile | bold italic | link image ",
        });
    }
    function AddNewEditor_appendto(vitri,id_editor,noidung,title){
        iconclose = "<div style='display: none'> <div class='close_edit_content' style='text-align: right'><i class='icon-close-off-2' style='position: relative;margin: 0;cursor: pointer;color: #999999'></i> </div> ";
        title = iconclose+'<input type="text" class="title form-control" value="'+title+'" placeholder="Nhập vào tiêu đề thảo luận" >';
        button = '<div style="float: right;width: auto;" class="btn btn-success btn_edit" >Lưu</div>';
        htmlSource = title+'<textarea id="editor-content'+id_editor+'" class="wp-editor-area editor-content" >'+noidung+'</textarea>'+button+"</div>";

        vitri.append(htmlSource);
        tinymce.init({
            selector: ".editor-content",
            menubar : false,
            height:100,
            plugins: [
                "image",
            ],
            toolbar: "insertfile | bold italic | link image ",
        });

    }
    //End Khải

    var mcVM_options = {
        menuId: "menu-v",
        alignWithMainMenu: true
    };
    /* www.menucool.com/vertical/vertical-menu.*/
    init_v_menu(mcVM_options);

    function init_v_menu(a) {
        if (window.addEventListener) window.addEventListener("load", function() {
            start_v_menu(a)
        }, false);
        else window.attachEvent && window.attachEvent("onload", function() {
            start_v_menu(a)
        })
    }

    function start_v_menu(i) {
        var e = document.getElementById(i.menuId),
            j = e.offsetHeight,
            b = e.getElementsByTagName("ul"),
            g = /msie|MSIE 6/.test(navigator.userAgent);
        if (g)
            for (var h = e.getElementsByTagName("li"), a = 0, l = h.length; a < l; a++) {
                h[a].onmouseover = function() {
                    this.className = "onhover"
                };
                h[a].onmouseout = function() {
                    this.className = ""
                }
            }
        for (var k = function(a, b) {
            if (a.id == i.menuId) return b;
            else {
                b += a.offsetTop;
                return k(a.parentNode.parentNode, b)
            }
        }, a = 0; a < b.length; a++) {
            var c = b[a].parentNode;
            c.getElementsByTagName("a")[0].className += " arrow";
            b[a].style.left = c.offsetWidth + "px";
            b[a].style.top = c.offsetTop + "px";
            if (i.alignWithMainMenu) {
                var d = k(c.parentNode, 0);
                if (b[a].offsetTop + b[a].offsetHeight + d > j) {
                    var f;
                    if (b[a].offsetHeight > j) f = -d;
                    else f = j - b[a].offsetHeight - d;
                    b[a].style.top = f + "px"
                }
            }
            c.onmouseover = function() {
                if (g) this.className = "onhover";
                var a = this.getElementsByTagName("ul")[0];
                if (a) {
                    a.style.visibility = "visible";
                    a.style.display = "block"
                }
            };
            c.onmouseout = function() {
                if (g) this.className = "";
                this.getElementsByTagName("ul")[0].style.visibility = "hidden";
                this.getElementsByTagName("ul")[0].style.display = "none"
            }
        }
        for (var a = b.length - 1; a > -1; a--) b[a].style.display = "none"
    }

    $('body').delegate('.ul>li','click',function(){
        alert('ldskfj');
    });
    /* $('.title-discussion').blur(KiemTraPostThaoLuan);
     $('body').delegate('#wp-txtThaoLuan-wrap','blur',KiemTraPostThaoLuan);
     $('body').delegate('#wp-txtThaoLuan-wrap','click',function(){alert("321321")});
     $('body').delegate('#wp-txtThaoLuan-wrap','keyup',KiemTraPostThaoLuan);
     $('.title-discussion').keydown(KiemTraPostThaoLuan);
     $('.title-discussion').keyup(KiemTraPostThaoLuan);
     $('.title-discussion').click(KiemTraPostThaoLuan);
     function KiemTraPostThaoLuan()
     {
     if(get_tinymce_content().length<= 1||$('.title-discussion').val()=="")
     {
     $('.btnThaoLuan').attr("disabled","disabled");
     }
     else
     {
     $('.btnThaoLuan').removeAttr('disabled');
     }
     }
     function get_tinymce_content(){

     //change to name of editor set in wp_editor()
     var editorID = 'txtThaoLuan';

     if (jQuery('#wp-'+editorID+'-wrap').hasClass("tmce-active"))
     var content = tinyMCE.get(editorID).getContent({format : 'text'});
     else
     var content = jQuery('#'+editorID).val();

     return content;
     }
     KiemTraPostThaoLuan();*/

    //xử lý khi click nào nút trả lời thì sẽ add Tinymce vào textarea
    $('body').delegate('.rely_comment','click',function(){
        var idTextarea = $(this).attr('data-commnent-id');
        var positionAddEditor = $(this).closest('.list-comment').parent().find('.content_child_comment');
        var id = $(this).closest('.NoiDungCMTUser').attr('data-course-id');
        $(this).closest('.NoiDungCMTUser').find('.hide-list-comment').show();
        $(this).closest('.NoiDungCMTUser').find('.child_comment').fadeIn();
        $(this).closest('.NoiDungCMTUser').find('.content_child_comment').empty();
        AddNewEditorChild(positionAddEditor,idTextarea,"","",id);

        //if(!$(this).closest('.list-comment').hasClass('be-frist')){
        $(this).hide();
        //}
    });

    //xử lý khi click vào nút ẩn comment child
    $('body').delegate('.hide_comment','click',function(){
        $(this).closest('.NoiDungCMTUser').find('.close_child_edit_content').trigger('click');
    });

    //xử lý khi click vào nút đóng của bình luận con thì tắt nội dung bình luận
    $('body').delegate('.close_child_edit_content','click',function(){
        $(this).closest('.NoiDungCMTUser').find('.rely_comment').fadeIn();
        $(this).closest('.NoiDungCMTUser').find('.hide-list-comment').fadeOut();
        $(this).closest('.NoiDungCMTUser').find('.child_comment').fadeOut();

        $(this).parent().empty();
    });

    //xử lý khi click vào nút xem thêm ajax thì add thêm comment khóa học vào

    $('body').delegate('.xemthembinhluan','click',function(){
        var id = $(this).attr('data-course-id');
        var sotrangview = $(this).attr('data-page');
        var This = $(this);
        var timkiem = $('.timkiembinhluan').val();
        $('.noidungthongbaoloading').show();
        $.ajax({
            type: "POST",
            url: ajaxurl,
            data: {
                action: 'viewmorediscusstion',
                id: id,
                sotrangviewmore: sotrangview,
                timkiem : timkiem
            },
            cache: false,
            success: function (result) {
                This.hide();
                $('.append-content-discussion').append(result);
                $('.unit_loading').hide();
                $('.noidungthongbaoloading').hide();
            }
        });
    });

    //xử lý khi click nút next thì load lại tab đề cương bên phải
    $('body').delegate('#next_unit','click',function(){
        $('.c').trigger('click');
    });

    $('body').delegate('#prev_unit','click',function(){
        $('.c').trigger('click');
    });

    //xử lý scroll đề cương mặc định trong khóa học
    $('.course_timeline.accordion').find('.unit_line.active').prevAll('.section').trigger('click');

    var ajaxCourse = null;
    $('body').delegate('.c','click',function(){
        if(ajaxCourse != null){
            ajaxCourse.abort();
        }
        if($('.c').hasClass('hascontent')){

        }else{
            $(this).closest('.gray-nav').find('li').removeClass('hascontent');
            $(this).closest('.gray-nav').find('li').removeClass('chon');
            $(this).addClass('chon');
            $(this).addClass('hascontent');
            $(this).closest('.curriculum_content').find('.course_timeline').remove();
            var content_curriculum_right = $('.curriculum_content_right');
            content_curriculum_right.html('');
            var content_curriculum = $('.course_timeline').clone();
            content_curriculum_right.append(content_curriculum);
        }


    });

    $('body').delegate('.d','click',function(){
        if(ajaxCourse != null){
            ajaxCourse.abort();
        }
        if($('.d').hasClass('hascontent')){
            $(this).closest('.gray-nav').find('li').removeClass('hascontent');

        }else{

            $(this).closest('.gray-nav').find('li').removeClass('hascontent');
            $(this).closest('.gray-nav').find('li').removeClass('chon');
            $(this).addClass('chon');
            $(this).addClass('hascontent');
            var content_curriculum_right = $('.curriculum_content_right');
            content_curriculum_right.html('');
            var content_attach = $('.unitattachments').clone();
            content_curriculum_right.append(content_attach);

        }


    });

    $('body').delegate('.e','click',function(){
        if($('.e').hasClass('hascontent')){
            $(this).closest('.gray-nav').find('li').removeClass('hascontent');

        }else{
            $('.noidungthongbaoloading').show();
            $(this).closest('.gray-nav').find('li').removeClass('hascontent');
            $(this).closest('.gray-nav').find('li').removeClass('chon');
            $(this).addClass('chon');
            $(this).addClass('hascontent');
            var content_curriculum_right = $('.curriculum_content_right');
            content_curriculum_right.html('');
            var input_content = '<div class="thaoluanunit"><input id="thaoluanunit" type="text" class="form-control" placeholder="Tiêu đề thảo luận"/> </div>';
            var noidungloadding = '<div class="unit_loading"><i class="noidungkhoahocloading icon-refresh glyphicon-refresh-animate"></i><span>Loading</span> </div>';

            content_curriculum_right.append(noidungloadding + input_content);
            var unit_id = $('#unit').attr('data-unit');
            ajaxCourse = $.ajax({
                type: "POST",
                url: ajaxurl,
                data: {
                    action: 'getdiscusstionforunit',
                    post_id: unit_id
                },
                cache: false,
                success: function (result) {
                    content_curriculum_right.append(result);
                    $('.unit_loading').hide();
                }
            });
        }


    });

    $('body').delegate('#thaoluanunit','click',function(){
        var This = $(this).closest('.thaoluanunit');
        var unit_id = $('#unit').attr('data-unit');
        if(!$(this).hasClass('contenteditor')){
            tinymce.remove();
            TaoEditorBinhLuan(This,unit_id);
            This.append('<span class="thembinhtheounit btn btn-success" style="  top: 100px;float: right;position: relative;">Gửi</span><span class="insert-my-media btn btn-success" data-id="'+unit_id+'">Thêm hình ảnh</span><div class="append-content-discussion"></div>');
            $(this).addClass('contenteditor');
        }

    });

    $('body').delegate('.thembinhtheounit','click',function(){
        var This = $(this);
        var unit_id = $('#unit').attr('data-unit');
        var unit_content_discusstion = tinyMCE.get("wisSW_Editor"+unit_id).getContent();
        var title = $('#thaoluanunit').val();
        var warning='<i class="icon-sun-stroke animated spin"></i>';
        var user_receiver = $(this).closest().find().attr('data-id');
        var course_id=$('.course_id').val();

        if(This.hasClass("warning"))
        {
            return;
        }
        This.prepend(warning);
        This.addClass("warning");
        if(title==""){
            alert("Bạn vui lòng nhập tiêu đề bình luận");
            This.find("i").remove();
            This.removeClass("warning");
        }else{
            if(unit_content_discusstion.length<13 || unit_content_discusstion.length>613){
                alert("Nội dung bình luận từ 6 đến 600 ký tự");
                This.find("i").remove();
                This.removeClass("warning");
            }else{
                $('.chemanhinh').show();
                $('#ajaxloader').show();
                $.ajax({
                    type: "POST",
                    url: ajaxurl,
                    data: { action: 'create_unit_discussion',
                        security: $('#security').val(),
                        cdtitle: title,
                        cdcontent: unit_content_discusstion,
                        cdunitid: unit_id,
                        cdcourseid: course_id
                    },
                    cache: false,
                    success: function (result) {
                        $('.chemanhinh').hide();
                        $('#ajaxloader').hide();
                        $('#thaoluanunit').val("");
                        This.find("i").remove();
                        This.removeClass("warning");
                        $('.title-discussion').val("");
                        $("#txtThaoLuan").val("");
                        /*append thảo luận*/
                        var contentdc= $(result).filter('.result').html();
                        $(".append-content-discussion").prepend(contentdc);
                        $(".contentdcs").fadeOut(200);
                        tinyMCE.activeEditor.setContent('');
                    }
                });
            }
        }

    });

//    Click vào cái chuông thì ẩn hiện nội dung thông báo và cập nhật lại trạng thái của comment thành comment củ
    $('body').delegate('.thongbao','click',function(){
        $('.noidungthongbaoloading').show();
        var noidungthongbao = $(this).closest('.row').find('.noidungthongbao');
        $('.noidungthongbao').find('.mask').html('');
        if(noidungthongbao.hasClass('show')){
            noidungthongbao.removeClass('show');

        }else{
            noidungthongbao.addClass('show');
            $('.sothongbao').removeClass('hienthisothongbao');

            $.ajax({
                type: "POST",
                url: ajaxurl,
                data:{
                    action: "get_notification_for_user_use_ajax"
                },
                cache: false,
                success: function(result){
                    $('.noidungthongbaoloading').hide();
                    if($(result).find('li').length !=0){
                        $('.noidungthongbao').find('.mask').append(result);
                        $('.mask ul li').each(function(){
                            var notify_id = $(this).attr('data-id');
                            $.ajax({
                                type: "POST",
                                url: ajaxurl,
                                data:{
                                    action: "update_status_notification",
                                    notify_id: notify_id
                                },
                                cache: false,
                                success: function(result){

                                }
                            });

                        });
                    }

                }
            });


        }
    });

    //hiển thị số thông báo mới khi trang vừa load
    var sothongbao = $('.sothongbao').text();
    if(sothongbao != 0){
        $('.sothongbao').addClass('hienthisothongbao');
    }

    //hiển thị thông báo động giống facebook bằng cách set trong một khoảng thời gian nào đó nó sẽ thực hiện tại ajax
    setInterval(function(){
        $.ajax({
            type: "POST",
            url: ajaxurl,
            data: {
                action: 'get_new_ajax_count_notification_for_user'
            },
            cache: false,
            success:function(result){
                //var old_notify_id = $('.mask > ul li:first-child').attr('data-id');
                if(result !=0){
                    $('.sothongbao').addClass('hienthisothongbao');
                    $('.sothongbao').text(result-0);
                }
            }
        });
    },5000);

    //bắt sự kiện khi click vào kính lúp thì bắt đầu search
    $('#search .icon-search-2').click(function(){
        $('#searchform').submit();
    });

    // Khi click chuột ra ngoài màn hình thì nội dung thông báo sẽ ẩn đi
    $(document).mouseup(function (e) {
        var container = $(".noidungthongbao");

        if (!container.is(e.target) && container.has(e.target).length === 0) // ... nor a descendant of the container
        {
            container.removeClass('show');
        }

        var container = $("#vibe_bp_login");
        var container1 = $("#thongtinkhoahoc");
        var container2 = $('#ratingreviewkhoahoc');
        var container3 = $('.xacnhandangkykhoahoc');
	var container5 = $('.khoahoccomingsoon');
        var container4 = $('.TV');

        if(container4.hasClass("kiemtrascrollhockhoahoc")){
            return;
        }

        if (!container.is(e.target) && container.has(e.target).length === 0 && !container1.is(e.target) && container1.has(e.target).length === 0 && !container2.is(e.target) && container2.has(e.target).length === 0 && !container3.is(e.target) && container3.has(e.target).length === 0 && !container5.is(e.target) && container5.has(e.target).length === 0) // ... nor a descendant of the container
        {
            container.removeClass('active');
            container1.removeClass('hienpopupthongtinkhoahoc');
            container2.removeClass('hienpopupthongtinkhoahoc');
            container3.removeClass('hienpopupxacnhandangkykhoahoc');
	    container5.removeClass('hienpopupxacnhandangkykhoahoc');
            $('.shadow-login').hide();
            $('body').css('overflow-y', 'scroll');
        }
    });


    //khi re chuột vào khóa học thì sẽ hiện hình giảng viên hiện button cho khóa học
   $('body').delegate('.courseitem','mouseenter',function(){
        $(this).find('.avatar-list').fadeIn(10);
        var button_course = $(this).find('.course_button');


        if($(this).find('#vaotranghockhoahoc').length !=0){
            //var button_course_1 = $(this).find('.hienxacnhandangkykhoahoc');
            //button_course_1.removeClass('hienxacnhandangkykhoahoc');
            //$(this).find('.avatar-list').append(button_course_1);
            //button_course_1.show();
            //button_course_1.addClass('showbuttontrangchu');

            if($(this).find('.xemngaykhoahoc').length==0) {
                var duongdan = $(this).find('.block_media a').attr('href');
                var button_xem_ngay = '<a href="' + duongdan + '" class="full button xemngaykhoahoc showbuttontrangchu">XEM NGAY</span></a>';
                $(this).find('.avatar-list').append(button_xem_ngay);
            }
        }
        else{
            if($(this).find('form').length !=0) {
                $(this).find('.avatar-list').append(button_course.parent());
            }else{
                if($(this).find('.unlogin').length==0){
                    $(this).find('.avatar-list').append(button_course);
                }else {
                    $(this).find('.unlogin').css('display','none');
                    if($(this).find('.xemngaykhoahoc').length==0) {
                        var duongdan = $(this).find('.block_media a').attr('href');
                        var button_xem_ngay = '<a href="' + duongdan + '" class="full button xemngaykhoahoc showbuttontrangchu">XEM NGAY</span></a>';
                        $(this).find('.avatar-list').append(button_xem_ngay);
                    }
                }
            }
        }

        button_course.removeClass('course_button');
        button_course.addClass('showbuttontrangchu');
        $(this).addClass('shadow_item_khoahoc');

    });

    //xử lý sự kiện khi click vào khóa học thì nhảy vào khóa học đó
    $('body').delegate('.courseitem','click',function(){

        var linka = $(this).find('.showbuttontrangchu');

        if(linka.val()){
            if(linka.val().length > 0){
                linka.parent('form').submit();
            }else{
                window.location.href=linka.closest('.block_media').find('a').attr('href');
            }
        }else{
            window.location.href=$(this).find('.block_media').find('a:first').attr('href');
        }

    });

    $('body').delegate('.courseitem','mouseleave',function(){
        $(this).find('.avatar-list').fadeOut(10);
        $(this).removeClass('shadow_item_khoahoc');
    });

    $('body').delegate('.sotrang','click',function(){
        var page_ajax = $(this).attr('data-id');
        $('.content-course-cat').addClass('loading');
        $('.content_course').css('opacity',0.2);
        $('.chemanhinh').show();
        $('#ajaxloader').removeClass('disabled');
        $('.content_course').remove();

        var danhmuc =0;
        var danhmuc_text="";
        if($('.tatcakhoahoc').hasClass('active_menu')){
            danhmuc=0;
            danhmuc_text="Tất cả";
        }else{
            danhmuc = $('.danhmuckhoahoc.cha.active_menu').find('.danhmuccha').attr('data-id');
            danhmuc_text = $('.danhmuckhoahoc.cha.active_menu').find('.danhmuccha').text();
        }
        var level_id = $('input[name=level]:checked').val();
        var language_id = $('input[name=language]:checked').val();
        var level_id_text = $('input[name=level]:checked').parent().find('label').text();
        var language_id_text = $('input[name=language]:checked').parent().find('label').text();

        //alert(danhmuc+"---"+danhmuc_text+"--"+level_id+"---"+language_id+"--"+level_id_text+"---"+language_id_text);

        var THIS = $(this);
        $.ajax({
            type: "POST",
            url: ajaxurl,
            data: {
                //action: 'loadajaxphantrang',
                action: 'loadajaxphantrang_lan_2',
                page_ajax: page_ajax,
                danhmuc: danhmuc,
                danhmuc_text: danhmuc_text,
                level_id: level_id,
                level_id_text: level_id_text,
                language_id: language_id,
                language_id_text: language_id_text
            },
            cache: false,
            success:function(result){
                //var old_notify_id = $('.mask > ul li:first-child').attr('data-id');
                if(result !=0){
                    $('#ajaxloader').addClass('disabled');
                    $('.chemanhinh').hide();
                    //$('.content-course-cat').append(result);
                    $('.content-course-cat').append('<div class="content_course">'+result+'<div>');
                    $('.content_course').css('opacity',1);
                    $('.content-course-cat').removeClass('loading');
                }
            }
        });
    });


    //xử lý sự kiện khi click vào từng trang
    $('body').delegate('.sotrangajax','click',function(){
        var page_ajax_term = $(this).attr('data-id');
        var term_id = $(this).attr('data-term');
        var name_term = $(this).attr('data-name');
        $('.content-course-cat').addClass('loading');
        $('.content_course').css('opacity',0.2);
        $('.chemanhinh').show();
        $('#ajaxloader').removeClass('disabled');
        $('.content_course').remove();
        $.ajax({
            type: "POST",
            url: ajaxurl,
            data: {
                action: 'hienthikhoahoctrangchuphantrang',
                page_ajax_term: page_ajax_term,
                term_id: term_id,
                name_term: name_term
            },
            cache: false,
            success:function(result){
                //var old_notify_id = $('.mask > ul li:first-child').attr('data-id');
                if(result !=0){
                    $('#ajaxloader').addClass('disabled');
                    $('.chemanhinh').hide();
                    //$('.content-course-cat').append(result);
                    $('.content-course-cat').append('<div class="content_course">'+result+'<div>');
                    $('.content_course').css('opacity',1);
                    $('.content-course-cat').removeClass('loading');
                }
            }
        });
    });


    //xử ký sự kiện scroll chuột khi đến với tiêu đề thì chuyển menu thành tên tiêu đề tương ứng
    $(window).bind('scroll', function() {
        var bottomwindow = $(window).scrollTop();
        if($('.course_description').length  > 0 ){
            var bottomdivdes = $('.course_description').offset().top;
            var bottomdivcur = $('.course_curriculum').offset().top;
            var bottomdivins = $('.thongtingianvien').offset().top;
            var bottomdivre = $('.course_reviews').offset().top;
            var hiddenmenu = $('.hiddenmenu').offsetHeight;
            if (bottomdivdes < bottomwindow + 120) {
                $('.btn-description').addClass('activehiddenmenu');
                $('.btn-curriculum').removeClass('activehiddenmenu');
                $('.hiddenmenu').slideDown(100);
            } else {
                $('.hiddenmenu').slideUp(100);
            }
            if (bottomdivcur < bottomwindow + 120) {
                $('.btn-description').removeClass('activehiddenmenu');
                $('.btn-instrutor').removeClass('activehiddenmenu');
                $('.btn-curriculum').addClass('activehiddenmenu');

            }
            if (bottomdivins < bottomwindow + 120) {
                $('.btn-curriculum').removeClass('activehiddenmenu');
                $('.btn-instrutor').addClass('activehiddenmenu');
                $('.btn-review').removeClass('activehiddenmenu');

            }
            if (bottomdivre < bottomwindow + 120) {
                $('.btn-instrutor').removeClass('activehiddenmenu');
                $('.btn-review').addClass('activehiddenmenu');
            }
        }
    });

    $('.btn-curriculum').click(function(){
        $('html,body').animate({
                scrollTop: $('.course_curriculum').offset().top - 110},
            'fast');
    });

    $('.btn-description').click(function(){
        $('html,body').animate({
                scrollTop:  $('.course_description').offset().top - 110},
            'fast');
    });

    $('.btn-instrutor').click(function(){
        $('html,body').animate({
                scrollTop:  $('.thongtingianvien').offset().top - 70},
            'fast');
    });

    $('.btn-review').click(function(){
        $('html,body').animate({
                scrollTop:  $('.course_reviews').offset().top - 110},
            'fast');
    });

    //ẩn button continue bị dư course ở khóa học
    var buttonContinue = $('.button-hoc').find('.gia-hidenmenu').find('#continue_course');
    if(buttonContinue.length > 0){
        buttonContinue.parent().remove();
    }
    $('.khoahoclienquan').find('.btn_course_continue').removeClass('btn_course_continue');

    //xử lý khi user chưa đăng nhập mà click vào button take this course thì hiện thị đăng ký
    $('.unlogin').click(function(){
        $('.vbplogin').trigger('click');
    });

    //hiển thị khóa học mặc định khi click vào nút tất cả
    $('.tatcakhoahoc').click(function(e,k){

        if(k!=1){
            $('.menu_level input[id=level]').prop("checked", true);
            $('.menu_ngonngu input[id=language]').prop("checked", true);
        }

        var level_id = $('input[name=level]:checked').val();
        var language_id = $('input[name=language]:checked').val();
        var level_id_text = $('input[name=level]:checked').parent().find('label').text();
        var language_id_text = $('input[name=language]:checked').parent().find('label').text();

        //hien danh sach cap do va ngon ngu
        $('.menu_level').css('display','block');
        $('.menu_ngonngu').css('display','block');


        $('.danhmuckhoahoc').removeClass('active_menu');
        $(this).addClass('active_menu');
        $('.content-course-cat').addClass('loading');
        $('.content_course').css('opacity',0.2);
        $('.chemanhinh').show();
        $('#ajaxloader').removeClass('disabled');
        $('.content_course').remove();
        $.ajax({
            type: "POST",
            url: ajaxurl,
            data: {
                action: 'hienthikhoahocmacdinh',
                level_id: level_id,
                language_id: language_id,
                level_id_text: level_id_text,
                language_id_text: language_id_text

            },
            cache: false,
            success:function(result){
                //var old_notify_id = $('.mask > ul li:first-child').attr('data-id');
                if(result !=0){
                    $('#ajaxloader').addClass('disabled');
                    $('.chemanhinh').hide();
                    //$('.content-course-cat').append(result);
                    $('.content-course-cat').append('<div class="content_course">'+result+'<div>');
                    $('.content_course').css('opacity',1);
                    $('.content-course-cat').removeClass('loading');
                }
            }
        });
    });

    //click raido menu trang chủ load khóa học
    $('input[type=radio][name=level]').change(function() {
        $('.danhmuckhoahoc.active_menu').trigger('click','1');
        if($('.tatcakhoahoc').hasClass('active_menu')){
            $('.tatcakhoahoc').trigger('click','1');
        }

    });

    $('input[type=radio][name=language]').change(function() {
        $('.danhmuckhoahoc.active_menu').trigger('click','1');
        if($('.tatcakhoahoc').hasClass('active_menu')){
            $('.tatcakhoahoc').trigger('click','1');
        }
    });

    //xử lý sự kiện khi click vào chữ x trên breadcum thì quay lại trang chủ
    $('body').delegate('.breadcrumb-level .icon-x','click',function(){
        //window.location.reload();
        var kiemtra = $(this).attr('data-id');
        if(kiemtra==1){
            $('.tatcakhoahoc').trigger('click','1');
            //$('.danhmuckhoahoc').removeClass('active_menu');
            //
            //$('.danhmuckhoahoc.active_menu').trigger('click','1');
            //if($('.tatcakhoahoc').hasClass('active_menu')){
            //    $('.tatcakhoahoc').trigger('click');
            //}
        }else{
            if(kiemtra==2){
                $('.menu_level input[id=level]').prop("checked", true);
                $('.danhmuckhoahoc.active_menu').trigger('click','1');
                if($('.tatcakhoahoc').hasClass('active_menu')){
                    $('.tatcakhoahoc').trigger('click','1');
                }
            }else{
                $('.menu_ngonngu input[id=language]').prop("checked", true);
                $('.danhmuckhoahoc.active_menu').trigger('click','1');
                if($('.tatcakhoahoc').hasClass('active_menu')){
                    $('.tatcakhoahoc').trigger('click','1');
                }
            }
        }
    });

    $('.button-dangnhap-dangky').click(function(){
        $('.error-login').hide();
        var username = $('#name-login').val();
        var password = $('#pass-login').val();
        $.ajax({
            type: "POST",
            url: ajaxurl,
            data: {
                action: 'dangnhaptrangdangky',
                username: username,
                password: password
            },
            cache: false,
            success:function(result){
                if(!$.isNumeric(result)){
                    window.location.replace(result);
                }else{
                    $('.error-login').fadeIn(100);
                }

            }
        });
    });

    //xử lý lấy tổng thời gian khóa học ở trang chi tiết khóa học
    if($('.course_lesson').length > 0){
        //var total_time_minute = 0;
        //var total_time_hourse = 0;
        //$('.course_lesson').each(function(){
        //    var unit_time = $(this).find('b').text();
        //    var array_split = unit_time.split(':');
        //    total_time_minute += parseInt(array_split[1]);
        //    total_time_hourse += parseInt(array_split[0]);
        //});
        //total_time_hourse += Math.round(total_time_minute/60);
        //$('.course_details').find('.icon-clock').parent().html('<i class="icon-clock"></i>'+total_time_hourse+' Hourse');
    }

    $("#id-dangnhap-it").click(function(){
        $('.noidungthongbaoloading').show();
        $('.error-login').hide();
        var username = $("#side-user-login").val();
        var password = $("#sidebar-user-pass").val();

        $.ajax({
            type: "POST",
            url: ajaxurl,
            data: {
                action: 'dangnhapmacdinh',
                username: username,
                password: password
            },
            cache: false,
            success:function(result){
                if(result==0){
                    $('.noidungthongbaoloading').hide();
                    window.location.reload();
                }else{
                    $('.noidungthongbaoloading').hide();
                    $('.error-login').fadeIn(100);
                }

            }
        });

    });

    // load popup thông tin khóa học
    $('.icon-info').click(function(){
        $('#thongtinkhoahoc').addClass('hienpopupthongtinkhoahoc');
        $('.shadow-login').show();
        $('body').css('overflow', 'hidden');

    });

    //xử lý X thông tin khóa học và đánh giá khóa học
    $('.icon-x.danhgia').click(function(){
        if($('#thongtinkhoahoc').hasClass('hienpopupthongtinkhoahoc')){
            $('#thongtinkhoahoc').removeClass('hienpopupthongtinkhoahoc');
        }
        if($('#ratingreviewkhoahoc').hasClass('hienpopupthongtinkhoahoc')){
            $('#ratingreviewkhoahoc').removeClass('hienpopupthongtinkhoahoc');
        }
        $('.shadow-login').hide();
        $('body').css('overflow-y', 'scroll');
    });

    // load popup đánh giá khóa học
    $('.icon-star').click(function(){

        $('#ratingreviewkhoahoc').addClass('hienpopupthongtinkhoahoc');
        $('.shadow-login').show();
        $('body').css('overflow', 'hidden');
        var id= $('.datacourseid').val();
        $('.loaddanhsachdanhgia').empty();
        $('.loaddanhsachdanhgia').append('<i class="loadingreview icon-refresh glyphicon-refresh-animate"></i>Đang tải...');


        $.ajax({
            type: "POST",
            url: ajaxurl,
            data: {
                action: "load_danh_sach_danh_gia_khoa_hoc",
                id: id
            },
            cache: false,
            success: function (result) {
                $('.loaddanhsachdanhgia').empty();
                $('.loaddanhsachdanhgia').append(result);
            }
        })

    });


    // xử lý đánh giá đánh giá khóa học
    // xử lý đánh giá đánh giá khóa học
    $('body').delegate('.danhgiakhoahoc','click',function(){
        var id = $(this).attr('data-id');
        var tieude = $('.tieudedanhgia').val();
        var noidung = $('.noidungdanhgia').val();
        var danhgia = $('input[name=review_rating]:checked').val();
        if(noidung.length<6){
            alert("Nội dung đánh giá ít nhất là 6 ký tự");
        }else {
            $('.chemanhinh').css('display','block');
            $('#ajaxloader').removeClass('disabled');
            $('.loadingdanhgia').removeClass('anpopupthongtinkhoahoc');
            $.ajax({
                type: "POST",
                url: ajaxurl,
                data: {
                    action: "themdanhgiakhoahoc",
                    id: id,
                    tieude: tieude,
                    noidung: noidung,
                    danhgia: danhgia
                },
                cache: false,
                success: function (result) {
                    $('.chemanhinh').css('display','none');
                    $('#ajaxloader').addClass('disabled');
                    if ($.isNumeric(result)) {
                        $('.loadingdanhgia').addClass('anpopupthongtinkhoahoc');
                        $('.icon-x.danhgia').trigger('click');
                        $('.noidungxulydanhgia').empty();
                        var dulieu='<span data-course="'+id+'" data-id="'+parseInt(result)+'" class="btn btn-primary capnhatdanhgia">Cập Nhật Đánh Giá</span>';
                        dulieu+='<a href="#" data-course="'+id+'" data-id="'+parseInt(result)+'" class="xoadanhgia">Xóa đánh giá của bạn</a>';
                        dulieu+='<p class="loadingcapnhatdanhgia anpopupthongtinkhoahoc"><i class="icon-refresh glyphicon-refresh-animate"></i>Đang cập nhật...</p>';
                        $('.noidungxulydanhgia').append(dulieu);

                    } else {
                        alert(result);
                    }
                }
            })

        }
    });

    // xử lý cập nhật đánh giá khóa học
    $('body').delegate('.capnhatdanhgia','click',function(){
        var id = $(this).attr('data-id');
        var course_id = $(this).attr('data-course');
        var tieude = $('.tieudedanhgia').val();
        var noidung = $('.noidungdanhgia').val();
        var danhgia = $('input[name=review_rating]:checked').val();
        $('.loadingcapnhatdanhgia').removeClass('anpopupthongtinkhoahoc');
        if(noidung.length<6){
            alert("Nội dung đánh giá ít nhất là 6 ký tự");
        }else {
            $('.chemanhinh').css('display','block');
            $('#ajaxloader').removeClass('disabled');
            $.ajax({
                type: "POST",
                url: ajaxurl,
                data: {
                    action: "capnhatdanhgiakhoahoc",
                    id: id,
                    tieude: tieude,
                    noidung: noidung,
                    danhgia: danhgia,
                    course_id : course_id
                },
                cache: false,
                success: function (result) {
                    $('.chemanhinh').css('display','none');
                    $('#ajaxloader').addClass('disabled');
                    if ($.isNumeric(result)) {
                        $('.loadingcapnhatdanhgia').addClass('anpopupthongtinkhoahoc');
                        $('.icon-x.danhgia').trigger('click');
                    } else {
                        alert(result);
                    }
                }
            })
        }
    });


    //xử lý xóa đánh giá khóa học
    $('body').delegate('.xoadanhgia','click',function(){
        var id = $(this).attr('data-id');
        var course_id = $(this).attr('data-course');
        $('.chemanhinh').css('display','block');
        $('#ajaxloader').removeClass('disabled');
        $.ajax({
            type: "POST",
            url: ajaxurl,
            data: {
                action: "xoadanhgiakhoahoc",
                id: id,
                course_id : course_id
            },
            cache: false,
            success: function (result) {
                $('.chemanhinh').css('display','none');
                $('#ajaxloader').addClass('disabled');
                if ($.isNumeric(result)) {
                    $('.icon-x.danhgia').trigger('click');
                    $('.tieudedanhgia').val("");
                    $('.noidungdanhgia').val("");
                    $('input[value="1"]').prop("checked", true);
                    $('.noidungxulydanhgia').empty();
                    var dulieu='<span data-id="'+course_id+'" class="btn btn-primary danhgiakhoahoc">Đánh Giá</span>';
                    dulieu+='<p class="loadingdanhgia anpopupthongtinkhoahoc"><i class="icon-refresh glyphicon-refresh-animate"></i>Đang lưu...</p>';
                    $('.noidungxulydanhgia').append(dulieu);
                } else {
                    alert(result);
                }
            }
        })
    });

    //xem thêm đánh giá
    $('body').delegate('.xemthemdanhgia','click',function(){
        var page = $(this).attr('data-page');
        var id = $(this).attr('data-course');
        var number  = $(this).attr('data-number');
        var total = $(this).attr('data-total');
        $('.loaddingdanhgia').removeClass('anpopupthongtinkhoahoc');
        $.ajax({
            type: "POST",
            url: ajaxurl,
            data: {
                action: "xem_them_danh_gia_khoa_hoc",
                page: page,
                id : id,
                number : number
            },
            cache: false,
            success: function (result) {
                $('.noidungdanhsachdanhgia').append(result);
                $('.xemthemdanhgia').attr('data-page',parseInt(page)+1);
                $('.loaddingdanhgia').addClass('anpopupthongtinkhoahoc');
                if(number*(parseInt(page)+1)>=total){
                    $('.xemthemdanhgia').addClass('anpopupthongtinkhoahoc');
                }
            }
        })
    });

    //xóa ASSIGNMENT
    $('.xoanopbaicuahocsinh').click(function(){
        var comment_id = $(this).attr('data-comment-id');

        var cf = confirm("Bạn có chắc chắn muốn cập nhật bản mới?");

        if (cf == true) {
            $('.chemanhinh').css('display','block');
            $('#ajaxloader').removeClass('disabled');
            $.ajax({
                type: "POST",
                url: ajaxurl,
                data: {
                    action: "xoa_assigment",
                    comment_id: comment_id
                },
                cache: false,
                success: function (result) {
                    window.location.reload();
                }
            })
        }

    });

    $('.hienxacnhandangkykhoahoc').click(function(e){
        e.stopPropagation();
        //$('.xacnhandangkykhoahoc').removeClass('anpopupthongtinkhoahoc');
        $('.xacnhandangkykhoahoc').addClass('hienpopupxacnhandangkykhoahoc');
        $('.course_button').addClass('showbuttontrangchu');
        $('.shadow-login').show();
        $('body').css('overflow', 'hidden');

    });

    $('.huydangkykhoahoc').click(function(e){
        e.stopPropagation();
        $('.xacnhandangkykhoahoc').removeClass('hienpopupxacnhandangkykhoahoc');
        //$('#xacnhandangkykhoahoc').addClass('anpopupthongtinkhoahoc');
        $('.shadow-login').hide();
        $('body').css('overflow-y', 'scroll');
    });

    // search menu in search.php
    // xử lý filter search course
    $('.searchfilter ul li input[type="checkbox"]').change(function(){
        if($(this).hasClass("capdo")){
            if($(this).attr('checked')){
                var capdo=$(this).attr("id");
                var ngonngu=$('#ngonngu').val();
                var linktrangchu = $('#linktrangchu').val();
                var tukhoa = $('#tukhoa').val();
                if(ngonngu==""){
                    var link = linktrangchu+"/?s="+tukhoa+"&level="+capdo;
                    window.location.replace(link);
                }else{
                    var link = linktrangchu+"/?s="+tukhoa+"&level="+capdo+"&language="+ngonngu;
                    window.location.replace(link);
                }
            }else{

                var ngonngu=$('#ngonngu').val();
                var linktrangchu = $('#linktrangchu').val();
                var tukhoa = $('#tukhoa').val();
                if(ngonngu==""){
                    var link = linktrangchu+"/?s="+tukhoa;
                    window.location.replace(link);
                }else{
                    var link = linktrangchu+"/?s="+tukhoa+"&language="+ngonngu;
                    window.location.replace(link);
                }
            }
        }else{
            if($(this).attr('checked')){
                var ngonngu=$(this).attr("id");
                var capdo=$('#capdo').val();
                var linktrangchu = $('#linktrangchu').val();
                var tukhoa = $('#tukhoa').val();
                if(capdo==""){
                    var link = linktrangchu+"/?s="+tukhoa+"&language="+ngonngu;
                    window.location.replace(link);
                }else{
                    var link = linktrangchu+"/?s="+tukhoa+"&level="+capdo+"&language="+ngonngu;
                    window.location.replace(link);
                }
            }else{

                var capdo=$('#capdo').val();
                var linktrangchu = $('#linktrangchu').val();
                var tukhoa = $('#tukhoa').val();
                if(capdo==""){
                    var link = linktrangchu+"/?s="+tukhoa;
                    window.location.replace(link);
                }else{
                    var link = linktrangchu+"/?s="+tukhoa+"&level="+capdo;
                    window.location.replace(link);
                }
            }

        }
    });

    $('.timkiembinhluan').keyup(function(e){
        var noidungtimkiem = $('.timkiembinhluan').val();
        var id=$(this).attr('data-course-id');
        $('.NoiDungThaoLuan').empty();
        $('.NoiDungThaoLuan').append('<i class="loadingreview icon-refresh glyphicon-refresh-animate"></i>Đang tải...');
        $.ajax({
            type: "POST",
            url: ajaxurl,
            data:{
                action: "timkiembinhluan",
                tieude: noidungtimkiem,
                id :id
            },
            cache:false,
            success: function(result){
                $('.NoiDungThaoLuan').empty();
                $('.NoiDungThaoLuan').append(result);
            }
        });
    });

    $('.xacnhandangkykhoahoc').click(function(e){
        e.stopPropagation();
    })

    //    ẩn scroll màn hình ngoài khi bấm vào khóa học hiện tại
    $('.iconmediaplayer').click(function(){
        $('body').css('overflow', 'hidden');
    })
//    ẩn scroll màn hình ngoài khi bấm vào khóa học hiện tại sau đó bấm vào start course
    $('.unit_prevnext .unit.unit_button').click(function(){
        $('body').css('overflow', 'hidden');
    })


    //$('.TV').click(function(){
    //    $('body').css('overflow', 'hidden');
    //});
    //
    //$('.body').delegate('.curriculum_content','click', function(){
    //    alert('âsd');
    //})

    $('.c').tooltip();
    $('.d').tooltip();
    $('.e').tooltip();

    //xử lý hiển thị thanh toán bằng thẻ ngân hàng khi load trangh thanh toán khóa hoc
    $("#btn_deposit").trigger('click');

    // click chuyển đến tab đánh giá ở trang chi tiết khóa học
    $('.đenanhgiakhoahoc').click(function(){
        $('.tab-btn.btn-review').trigger('click');
    });

    $('.anhien').click(function(){
        var id=$(this).attr('dt-toggle');
        var c_icon="icon_"+$(this).attr('dt-toggle');
        $('#'+id).slideToggle();
        $('.'+c_icon).fadeToggle(0);
    });

    $('.dongkhoahoccomingsoon').click(function(e){
        e.stopPropagation();
        $('.khoahoccomingsoon').removeClass('hienpopupxacnhandangkykhoahoc');
        //$('#xacnhandangkykhoahoc').addClass('anpopupthongtinkhoahoc');
        $('.shadow-login').hide();
        $('body').css('overflow-y', 'scroll');
    });

    $('.hienthikhoahoccomingsoon').click(function(e){
        //e.stopPropagation();
        //$('.xacnhandangkykhoahoc').removeClass('anpopupthongtinkhoahoc');
        $('.khoahoccomingsoon').addClass('hienpopupxacnhandangkykhoahoc');
        //$('.course_button').addClass('showbuttontrangchu');
        $('.shadow-login').show();
        $('body').css('overflow', 'hidden');

    });

 	$("#sidebar-user-pass").keyup(function(event){
       		 if(event.keyCode == 13){
           		 $('#id-dangnhap-it').trigger('click');
       		 }
   	 });

    //live chat
	
	window.$zopim||(function(d,s){var z=$zopim=function(c){z._.push(c)},$=z.s=
	d.createElement(s),e=d.getElementsByTagName(s)[0];z.set=function(o){z.set.
	_.push(o)};z._=[];z.set._=[];$.async=!0;$.setAttribute("charset","utf-8");
	$.src="//v2.zopim.com/?2Qp9LXUsp2lNcxdeAxeOwBiPy2i7fhBB";z.t=+new Date;$.
	type="text/javascript";e.parentNode.insertBefore($,e)})(document,"script");


});



