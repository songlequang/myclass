<?php get_header();

$page_array=get_option('bp-pages');
if(isset($page_array['register'])){
    $id = $page_array['register'];
}
?>

<section id="content">
    <div class="container">
        <div class="row content">
            <div class="col-md-6 col-sm-5">
                <form action="" method="post">
                    <div class="row">
                        <div class="col-md-1 col-sm-1">

                        </div>

                        <div class="form-dang-ky col-md-9 col-sm-9">
                            <label class="comment-form-social-connect">Đăng ký tài khoản</label>
                            <div>
                                <span><i class="icon-email"></i></span>
                                <input placeholder="Email" class="form-control" type="text" name="email" value="">
                            </div>

                            <div>
                                <span><i class="icon-lock"></i></span>
                                <input placeholder="Mật khẩu" class="form-control" type="password" name="password" value="">
                            </div>

                            <div>
                                <span><i class="icon-users"></i></span>
                                <input placeholder="Họ tên" class="form-control" type="text" name="username" value="">
                            </div>

                            <div>
                                <span><i class="icon-phone"></i></span>
                                <input placeholder="Số điện thoại" class="form-control" type="text" name="phone" value="">
                            </div>

                            <div class="check_field">
                                <strong>
                                    <?php kiemtradangky($_POST['username'],$_POST['password'],$_POST['email']) ?>
                                </strong>
                            </div>

                            <input type="submit" name="submit" value="Đăng ký"/>
                        </div>

                        <div class="col-md-1 col-sm-1">

                        </div>
                    </div>

                </form>
            </div>

            <div class="col-md-6 col-sm-7">
                <div class="row">
                    <div class="col-md-1 col-sm-1">

                    </div>

                    <div class="form-dang-nhap col-md-9 col-sm-9">
                        <?php //do_action( 'login_form' ); ?>
			<p class="comment-form-social-connect">
                           <label>Đăng nhập với mạng xã hội</label>
                        </p>
			<a href="http://it.myclass.vn/wp-login.php?loginFacebook=1&redirect=http://it.myclass.vn" onclick="window.location = 'http://it.myclass.vn/wp-login.php?loginFacebook=1&redirect='+window.location.href; return false;"><div class="social-btn"><i class="icon-facebook social-icon"></i><span class="btn-text">Đăng nhập bằng Facebook</span></div></a>
                  
                        <h4>Bạn đã có tài khoản</h4>
                        <div>
                            <span><i class="icon-email"></i></span>
                            <input placeholder="Email" class="form-control" type="text" id="name-login" name="name-login" value="">
                        </div>
                        <div>
                            <span><i class="icon-lock"></i></span>
                            <input placeholder="Mật khẩu" type="password" class="form-control" type="text" id="pass-login" name="pass-login" value="">
                        </div>
                        <span class="error-login">Tài khoản hoặc mật khẩu không đúng !</span>
                        <button class="button-dangnhap-dangky">Đăng nhập</button>
                    </div>

                    <div class="col-md-1 col-sm-1">

                    </div>
                </div>

            </div>
        </div>
    </div>
</section><!-- #content -->


<?php get_footer(); ?>

<?php
function kiemtradangky($username,$password,$email){
    global $reg_errors;
    $reg_errors = new WP_Error;

    if(isset($_POST['submit'])){
        if ( empty( $username ) || empty( $password ) || empty( $email ) ) {
            $reg_errors->add('field', 'Vui lòng nhập đầy đủ thông tin');
        }

        if ( 4 > strlen( $username ) ) {
            $reg_errors->add( 'username_length', 'Họ tên phải lớn hơn 4 ký tự !' );
        }

        //if ( username_exists( $username ) )
        //    $reg_errors->add('user_name', 'tên đăng nhập đã tồn tại !');

        //if ( ! validate_username( $username ) ) {
        //    $reg_errors->add( 'username_invalid', 'Vui lòng nhập họ tên !' );
        //}

        if ( 5 > strlen( $password ) ) {
            $reg_errors->add( 'password', 'Mật khẩu lớn hơn 5 ký tự !' );
        }

        if ( !is_email( $email ) ) {
            $reg_errors->add( 'email_invalid', 'Vui lòng nhập email !' );
        }

        if ( email_exists( $email ) ) {
            $reg_errors->add( 'email', 'Email hoặc tên đăng nhập đã tồn tại !' );
        }

        if ( is_wp_error( $reg_errors ) ) {

            foreach ( $reg_errors->get_error_messages() as $error ) {

                echo '<div>';
                echo '<strong>Lỗi </strong>:';
                echo $error . '<br/>';
                echo '</div>';
                return ;
            }

        }
        $userdata = array(
            'user_login'    =>   $email,
            'user_email'    =>   $email,
            'user_pass'     =>   $password,
	    'display_name'  =>	$username,
            'nickname'      =>   $username,
            'user_status'   =>   0
        );
 
	    $to = $email;
            $subject = 'Tài khoản của bạn đã được xác thực';
            $body = 'Chào '.$username.'<br/>
              Việc đăng ký trên Myclass.vn đã được xác thực. Sau đây là thông tin tài khoản của bạn: <br /><br />
              Tên đăng nhập: '.$email.' <br/>
              Mật khẩu: '.$password.'<br/><br />
              Bạn hãy đăng nhập vào itclass.vn để tham gia các khóa học Lập Trình tốt nhất, mang tính thực tiễn cao của chúng tôi. <br />
              Thân mến <br />
              <a href="http://it.myclass.vn">Myclass.vn</a>';
                $headers = array('Content-Type: text/html; charset=UTF-8');

                wp_mail( $to, $subject, $body, $headers );
                wp_redirect( home_url().'/danh-sach-khoa-hoc' );
        $user_id = wp_insert_user( $userdata );
	update_user_meta($user_id,'billing_phone',$_POST['phone']);

        $noidung = 'Bạn đã đăng ký tài khoản thành công tại Website '.get_home_url().' \r\n Thông tin tài khoản \r\n Tên đăng nhập : '.$email.' \r\n Mật khẩu : '.$password.' \r\n Cảm ơn bạn đã đăng ký học tại Itclass.vn !';
    


    }


}

?>
