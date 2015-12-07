jQuery(document).ready(function($){

    $('.timkiemhocsinh').click(function(){
        var kt=0;
        if($(".timtheoten").is(":checked")){
            kt=1;
        }else{
            kt=2;
        }
        var dulieu=$('.giatri').val();

        $.ajax({
            type: "POST",
            url: ajaxurl,
            data: {
                action: 'tim_kiem_hoc_sinh',
                kt : kt,
                dulieu : dulieu
            },
            success:function(result){
                $('#the-list').empty();
                $('#the-list').append(result);
            }
        });
    });

    $('body').delegate('.themuser', 'click', function(){
        var dulieu=$('.id_course').val();
        var id=$('.themuser').attr('data-id-user');
        $.ajax({
            type: "POST",
            url: ajaxurl,
            data: {
                action: 'them_hoc_sinh_vao_khoa_hoc',
                dulieu : dulieu,
                id : id
            },
            success:function(result){
                if($.isNumeric(result)){
                    alert('Mã khóa học không đúng');
                }else{
                    alert('Đã thêm học sinh thành công');
                    window.location.reload();
                }
            }
        });
    });

});
