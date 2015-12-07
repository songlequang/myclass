<?php
/*
Template Name: Thanh Toán Khóa Học
*/

get_header();
global $current_user;
get_currentuserinfo();
$point_need = 0;
$point_course = 0;
$point_user = apply_filters('get_point_user',0);
$user_id = get_current_user_id();
$user_email= $current_user->user_email;
$pay_date = current_time('mysql');
$user_name = $current_user->display_name;

global $wpdb;
//Lay so tien cu cua user
$cmycred_default = get_user_meta( get_current_user_id(), 'mycred_default', true );
$cmycred_default_total = get_user_meta( get_current_user_id(), 'mycred_default_total', true );

function KiemTraTokenTonTai($matoken){
    global $wpdb;
    $check = false;
    $query = 'SELECT * FROM '.$wpdb->prefix.'paycard'.' WHERE token="'.$matoken.'"';
    $result = $wpdb->get_results($query);
    $count = count($result);
    if($count > 0){
            $check = true;
    }else{
        $check = false;
    }
    return $check;
}

function LuuToken($matoken){
    global $wpdb;
    $query = 'INSERT INTO '.$wpdb->prefix.'paycard'.' ( token ) VALUES(%s)';
    $result = $wpdb->query($wpdb->prepare($query,array($matoken)));
    if(!$result){
        echo '<script>alert("Lỗi không xác định !")</script>';
    }
}

function LuuLogNapCardUser($user_id,$pay_date,$token,$user_name,$user_email,$money){
    global $wpdb;
    $query = 'INSERT INTO '.$wpdb->prefix.'paycard_log'.' ( user_id,pay_date,token,user_name,user_email,pay_money ) VALUES(%d,%s,%s,%s,%s,%s)';
    $result = $wpdb->query($wpdb->prepare($query,array($user_id,$pay_date,$token,$user_name,$user_email,$money)));
    if(!$result){
        echo '<script>alert("Lỗi không xác định !")</script>';
    }
}


require_once(dirname(__FILE__).'/config.php');
require_once(dirname(__FILE__).'/includes/lib/nusoap.php');
require_once(dirname(__FILE__).'/includes/nganluong.microcheckout.class.php');
require_once(dirname(__FILE__).'/includes/MobiCard.php');
require_once(dirname(__FILE__).'/includes/NL_Checkoutv3.php');

$linkjsNganLuong = dirname(__FILE__)."/includes/nganluong.apps.mcflow.js";

//Xử lý nạp bằng thẻ cào
if(isset($_POST['NLNapThe'])){
    $soseri = $_POST['txtSoSeri'];
    $sopin = $_POST['txtSoPin'];
    $type_card = $_POST['select_method'];


    if ($_POST['txtSoSeri'] == "" ) {
        echo '<script>alert("Vui lòng nhập Số Seri");</script>';
        echo "<script>location.href='".$_SERVER['HTTP_REFERER']."';</script>";
        exit();
    }
    if ($_POST['txtSoPin'] == "" ) {
        echo '<script>alert("Vui lòng nhập Mã Thẻ");</script>';
        echo "<script>location.href='".$_SERVER['HTTP_REFERER']."';</script>";
        exit();
    }

    $arytype= array(92=>'VMS',93=>'VNP',107=>'VIETTEL',121=>'VCOIN',120=>'GATE');
    //Tiến hành kết nối thanh toán Thẻ cào.
    $call = new MobiCard();
    $rs = new Result();
    $coin1 = rand(10,999);
    $coin2 = rand(0,999);
    $coin3 = rand(0,999);
    $coin4 = rand(0,999);
    $ref_code = $coin4 + $coin3 * 1000 + $coin2 * 1000000 + $coin1 * 100000000;

    $rs = $call->CardPay($sopin,$soseri,$type_card,$ref_code,"Tên khách hàng","Mobile Khách Hàng"," Email Khách hàng");

    if($rs->error_code == '00') {
        // Cập nhật data tại đây
        $check = KiemTraTokenTonTai($rs->card_serial);
        if($check){
            if(isset($_GET['course_id'])){
                wp_redirect( home_url().'/thanh-toan-khoa-hoc/?course_id='.$_GET['course_id'].'' );
            }else{
                wp_redirect( home_url().'/thanh-toan-khoa-hoc/');
            }

        }else{
            LuuToken($rs->card_serial);
            $card_amount = $rs->card_amount;
            if(empty($cmycred_default)){
                $amount_new = substr($card_amount,0,strlen($card_amount)-3);
                update_user_meta($user_id,'mycred_default',$amount_new);
                update_user_meta($user_id,'mycred_default_total',$amount_new);
            }else{
                $amount_new = substr($card_amount,0,strlen($card_amount)-3);
                $amount_add = (int)$amount_new + $cmycred_default;
                $amount_add_total = (int)$amount_new + $cmycred_default_total;

                update_user_meta($user_id,'mycred_default',$amount_add);
                update_user_meta($user_id,'mycred_default_total',$amount_add_total);
            }
	    LuuLogNapCardUser($user_id,$pay_date,$rs->card_serial,$user_name,$user_email,$rs->card_amount);
            echo  '<script>alert("Bạn đã nạp thành công '.$rs->card_amount.' vào trong tài khoản.'.'");</script>'; //$total_results;

            $courseid = $_GET['course_id'];
            if(empty($courseid)){
                $duongdan = get_home_url()."/danh-sach-khoa-hoc/";
                echo '<script> window.location.href = "'.$duongdan.'"; </script>';
//            header("Location: ".get_home_url()."/danh-sach-khoa-hoc");
            }else{
                $tienkhoahoc = get_post_meta($courseid,'vibe_mycred_points',true);

                if($amount_add>=$tienkhoahoc){
                    $sotiencondu = $amount_add - $tienkhoahoc;
                    add_user_meta( $user_id, $courseid, '1437619528');
                    add_user_meta( $user_id, 'course_status'.$courseid, '2');
                    update_user_meta($user_id,'mycred_default',$sotiencondu);
                    update_user_meta($user_id,'mycred_default_total',$sotiencondu);

                    $duongdansau = get_permalink($courseid);
                    echo '<script> window.location.href = "'.$duongdansau.'"; </script>';

                }else{
                    $tienconthieu = $tienkhoahoc-$amount_add;
//                echo '<script>
//                    alert("Bạn còn thiếu '.$tienkhoahoc.' điểm ( '.$tienkhoahoc.'.000 Đ ) để có thể học khóa học: '.get_the_title( $courseid ).');
//
//                    </script>';
                    $duongdanthanhtoan = get_home_url()."/thanh-toan-khoa-hoc/?course_id=".$courseid;
//                echo '<script> window.location.href = "'.$duongdanthanhtoan.'"; </script>';
                    echo '<script>alert("Bạn không đủ tiền để học khóa học này! Vui lòng nạp thêm tiền để có thể học.");
                    window.location.href = "'.$duongdanthanhtoan.'"; </script>';
                }
            }

        }

    }
    else {
        echo  '<script>alert("Lỗi :'.$rs->error_message.'");</script>';
    }

    //var_dump($rs);

}
//End xử lý nạp cào

//Xử lý nạp băng thẻ ATM
if(isset($_GET['token'])){
    $nlcheckout= new NL_CheckOutV3(MERCHANT_ID,MERCHANT_PASS,RECEIVER);
    $nl_result = $nlcheckout->GetTransactionDetail($_GET['token']);

    if($nl_result){
        $nl_errorcode           = (string)$nl_result->error_code;
        $nl_transaction_status  = (string)$nl_result->transaction_status;
        if($nl_errorcode == '00') {
            if($nl_transaction_status == '00' || $nl_transaction_status == '04') {
                //trạng thái thanh toán thành công

                $check = KiemTraTokenTonTai($_GET['token']);
                if($check){
                    if(isset($_GET['course_id'])){
                        wp_redirect( home_url().'/thanh-toan-khoa-hoc/?course_id='.$_GET['course_id'].'' );
                    }else{
                        wp_redirect( home_url().'/thanh-toan-khoa-hoc/');
                    }
                }else{
                    LuuToken($_GET['token']);

                    $card_amount = $nl_result->total_amount;
                    if(empty($cmycred_default)){
                        $amount_new = substr($card_amount,0,strlen($card_amount)-3);
                        $amount_extend = $amount_new + ($amount_new * 10)/100;
                        update_user_meta($user_id,'mycred_default',$amount_extend);
                        update_user_meta($user_id,'mycred_default_total',$amount_extend);

                    }else{

                        $amount_new = substr($card_amount,0,strlen($card_amount)-3);
                        $amount_add = (int)$amount_new + $cmycred_default;
                        $amount_add_total = (int)$amount_new + $cmycred_default_total;

                        $amount_add_extend = $amount_add + ($amount_new * 10)/100;
                        $amount_add_extend_total = $amount_add_total + ($amount_new * 10)/100;
                        update_user_meta($user_id,'mycred_default',$amount_add_extend);
                        update_user_meta($user_id,'mycred_default_total',$amount_add_extend_total);

                    }
		    LuuLogNapCardUser($user_id,$pay_date,$_GET['token'],$user_name,$user_email,$nl_result->total_amount);	
                    echo  '<script>alert("Bạn đã nạp thành công '.$nl_result->total_amount.' vào trong tài khoản.'.'");</script>'; //$total_results;

                    $courseid = $_GET['course_id'];
                    if(empty($courseid)){
                        $duongdan = get_home_url()."/danh-sach-khoa-hoc/";
                        echo '<script> window.location.href = "'.$duongdan.'"; </script>';
//            header("Location: ".get_home_url()."/danh-sach-khoa-hoc");
                    }else{
                        $tienkhoahoc = get_post_meta($courseid,'vibe_mycred_points',true);

                        if($amount_add>=$tienkhoahoc){
                            $sotiencondu = $amount_add - $tienkhoahoc;
                            add_user_meta( $user_id, $courseid, '1437619528');
                            add_user_meta( $user_id, 'course_status'.$courseid, '2');
                            update_user_meta($user_id,'mycred_default',$sotiencondu);
                            update_user_meta($user_id,'mycred_default_total',$sotiencondu);

                            $duongdansau = get_permalink($courseid);
                            echo '<script> window.location.href = "'.$duongdansau.'"; </script>';

                        }else{
                            $tienconthieu = $tienkhoahoc-$amount_add;
//                echo '<script>
//                    alert("Bạn còn thiếu '.$tienkhoahoc.' điểm ( '.$tienkhoahoc.'.000 Đ ) để có thể học khóa học: '.get_the_title( $courseid ).');
//
//                    </script>';
                            $duongdanthanhtoan = get_home_url()."/thanh-toan-khoa-hoc/?course_id=".$courseid;
//                echo '<script> window.location.href = "'.$duongdanthanhtoan.'"; </script>';
                            echo '<script>alert("Bạn không đủ tiền để học khóa học này! Vui lòng nạp thêm tiền để có thể học.");
                            window.location.href = "'.$duongdanthanhtoan.'"; </script>';
                        }
                    }

                }

                //echo "<pre>";
                //print_r( $nl_result);
                //echo "</pre>";
                //echo "Cập nhật trạng thái thành công";
            }

        }else{
            echo $nlcheckout->GetErrorMessage($nl_errorcode);
        }
    }

}
// End xử lý băng thẻ ATM
?>


<?php
$course_id = $_GET['course_id'];
?>

<section id="content">
<div id="buddypress">
<div class="container">
<div id="item-body" >
<div  style="margin: -20px;background-color: #F9F9F9">
<?php if(isset($_GET['course_id'])){ ?>
<div class="row naptienvaotaikhoan"style="background-color: rgb(0, 155, 155)">
    <div class="col-md-12 col-sm-12">
        <h3 >Nạp Tiền Vào Tài Khoản</h3>
    </div>
</div>

<div class="row">
    <div class="col-md-6 col-sm-6 " style="border-right: 1px solid gray">
        <div class="thanhtoankhoahoc">
            <h4>Thanh toán khóa học</h4>
            <?php
            if(empty($course_id)){
                ?>
                Tên khóa học : Không có ! <br />
                Học phí :
            <?php }else{ ?>
                Mã khóa học : <?php echo $course_id ?><br/>
                Tên khóa học : <?php echo get_the_title($course_id) ?> <br />
                Học phí :  <?php $course_credits =get_post_meta($course_id,'vibe_course_credits',true)  ;?>
            <?php } ?>

            <?php
            if(isset($course_credits) && $course_credits !='' ){
                $credits[]= '<strong>'.$course_credits.'</strong>';
            }
            ?>
            <?php $credit = apply_filters('get_point_course', $course_id); ?>
            <?php
            if(!empty($credit)){
                $point_course=$credit;
                $credits_html .= '<span>&nbsp;' . $credit . '.000 Đ</span> ( '.$credit.' điểm )';
                echo $credits_html.' <strong></strong>';
            }else{
                echo _e('Không có !');
            }
            ?>
            <?php

            ?>
        </div>
    </div>

    <div class="col-md-6 col-sm-6 ">
        <div class="thanhtoantaikhoan">
            <?php if(is_user_logged_in()){ ?>
                <!--                Tài khoản của bạn :  --><?php //do_action('wplms_header_top_login'); ?><!-- <br />-->
                <h5> Tài Khoản của bạn</h5>
                <span>Số dư : <?php echo $point_user.'.000 đồng'?> ( <?php echo $point_user ?> điểm )</span><br/>
                <?php if(!empty($course_id)){ ?>
                    Bạn cần nạp thêm : <?php
                    echo apply_filters('get_point_course', $course_id) - $point_user;
                    ?>.000 Đ ( <?php echo apply_filters('get_point_course', $course_id) - $point_user; ?> điểm )
                <?php } ?>
            <?php }else{
                echo _e('Vui lòng đăng nhập !');
            } ?>


        </div>
    </div>
</div>
<?php }?>
    <div class="row naptienvaotaikhoan" style="background-color: rgb(0, 155, 155)">
        <div class="col-md-12 col-sm-12">
            <h3 >Hình Thức Thanh Toán</h3>
        </div>

    </div>
    <div class="row naptienvaotaikhoan anhien" dt-toggle="coll_2" style="background-color: rgb(10, 99, 99) ">
        <div class="col-md-12 col-sm-12">
            <h3 style="float: left;">1. Nạp Bằng Điện Thoại</h3>
        </div>
        <i class="glyphicon-plus icon_coll_2"></i><i class="glyphicon-minus icon_coll_2" style="display:none;"></i>
    </div>

    <div class="row" id="coll_2" style="display: none">
    <div class="col-md-12 napbangthecao">
        <h5>Hướng dẫn nạp tiền bằng thẻ cào điện thoại : </h5>
        <ol>
            <li> <span>1/</span> Cào lớp tráng bạc để biết mã thẻ. </li>
            <li> <span>2/</span> Chọn loại thẻ và nhập mã thẻ cùng số seri rồi ấn nút "Nạp ngay". <i><span style="color:#ff5a00;font-weight:bold;text-decoration:underline;">Lưu ý</span> viết liền không khoảng trắng hoặc dấu - </i></li>
            <li> <span>3/</span> Quay lại bước 2 để nạp thẻ tiếp theo. </li>
        </ol>

        <form name="napthe" action="#" method="post">
            <div id="body12" style="border: 1px solid #444444;  margin: 0 auto;  padding: 10px;  width: 600px;">
                <div style="color:#444444;margin-top:10px;font-size:15px;text-transform:uppercase;font-weight:bold" align="center">
                    Chọn loại thẻ để <nạp></nạp>
                </div>

                <table align="center">

                    <tr>
                        <td colspan="3">
                            <table>
                                <tr>
                                    <td style="padding-left:0px;padding-top:5px" align="right" ><label for="92"><img  src="<?php echo get_stylesheet_directory_uri().'/includes/images/mobifone.jpg'?>" /></label> </td>
                                    <td style="padding-left:10px;padding-top:5px"><label for="93"><img  src="<?php echo get_stylesheet_directory_uri().'/includes/images/vinaphone.jpg' ?>" /></label></td>
                                    <td style="padding-top:5px;padding-left:5px" align="left"><label for="107"><img  src="<?php echo get_stylesheet_directory_uri().'/includes/images/viettel.jpg'?>" width="110" height="35" /></label></td>
                                    <td style="padding-top:5px;padding-left:5px" align="left"><label for="121"><img width="100" height="35" src="<?php echo get_stylesheet_directory_uri().'/includes/images/vtc.jpg'?>"></label> </td>
                                    <td style="padding-top:5px;padding-left:5px" align="left"> <label for="120"><img width="100" height="35" src="<?php echo get_stylesheet_directory_uri().'/includes/images/gate.jpg'?>"></label></td>
                                </tr>
                                <tr>
                                    <td align="center" style="padding-bottom:0px;">
                                        <input type="radio" name="select_method" checked="true" value="VMS" id="92"  />
                                    </td>
                                    <td align="center" style="padding-bottom:0px;padding-left:5px">
                                        <input type="radio"  name="select_method" value="VNP" id="93" />
                                    </td>
                                    <td align="center" style="padding-bottom:0px;padding-right:0px">
                                        <input type="radio"  name="select_method" value="VIETTEL" id="107" />
                                    </td>
                                    <td align="center" style="padding-right:10px">
                                        <input type="radio" id="121" value="VCOIN" name="select_method">
                                    </td>

                                    <td align="center" style="padding-bottom:0px;padding-right:0px">
                                        <input type="radio" id="120" value="GATE" name="select_method">
                                    </td>

                                </tr>
                            </table>
                        </td>
                    </tr>

                    <tr>
                        <td align="right" style="padding-bottom:10px">Số Seri :</td>
                        <td colspan="2"><input type="text" id="txtSoSeri" name="txtSoSeri" style="height:25px;width:200px" /></td>
                    </tr>
                    <tr>
                        <td align="right">Mã số thẻ : </td>
                        <td colspan="2">
                            <input type="text" id="txtSoPin" name="txtSoPin" style="height:25px;width:200px" />

                        </td>
                    </tr>

                    <tr>
                        <td colspan="3" align="center" style="padding-bottom:10px;padding-right:10px">
                            <input type="submit" id="ttNganluong" name="NLNapThe" value="Nạp Thẻ"  />
                        </td>
                    </tr>
                </table>

            </div>
        </form>
    </div>
</div><p></p>

<div class="row naptienvaotaikhoan napbangthenganhang anhien" dt-toggle="coll_3" style="background-color: rgb(10, 99, 99) ">
    <div class="col-md-12 col-sm-12 ">
        <h3 style="float:left;">2. Nạp Bằng Thông Qua Thẻ Ngân Hàng</h3>
    </div>
    <i class="glyphicon-plus icon_coll_3"></i><i class="glyphicon-minus icon_coll_3" style="display:none;"></i>
</div>
<div class="noidungthenganhang" id="coll_3" style="display: none">

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" dir="ltr" lang="vi" xml:lang="vi">
<head profile="http://gmpg.org/xfn/11">
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
    <title>Demo tích hợp Nganluong.vn </title>
    <style>

        ul.bankList {
            clear: both;
            height: 202px;
            width: 636px;
        }
        ul.bankList li {
            list-style-position: outside;
            list-style-type: none;
            cursor: pointer;
            float: left;
            margin-right: 0;
            padding: 5px 2px;
            text-align: center;
            width: 90px;
        }
        .list-content li {
            list-style: none outside none;
            margin: 0 0 10px;
        }

        .list-content li .boxContent {
            display: none;
            width: 636px;
            border:1px solid #cccccc;
            padding:10px;
        }
        .list-content li.active .boxContent {
            display: block;
        }
        .list-content li .boxContent ul {
            height:280px;
        }

        i.VISA, i.MASTE, i.AMREX, i.JCB, i.VCB, i.TCB, i.MB, i.VIB, i.ICB, i.EXB, i.ACB, i.HDB, i.MSB, i.NVB, i.DAB, i.SHB, i.OJB, i.SEA, i.TPB, i.PGB, i.BIDV, i.AGB, i.SCB, i.VPB, i.VAB, i.GPB, i.SGB,i.NAB,i.BAB
        { width:80px; height:30px; display:block; background:url(https://www.nganluong.vn/webskins/skins/nganluong/checkout/version3/images/bank_logo.png) no-repeat;}
        i.MASTE { background-position:0px -31px}
        i.AMREX { background-position:0px -62px}
        i.JCB { background-position:0px -93px;}
        i.VCB { background-position:0px -124px;}
        i.TCB { background-position:0px -155px;}
        i.MB { background-position:0px -186px;}
        i.VIB { background-position:0px -217px;}
        i.ICB { background-position:0px -248px;}
        i.EXB { background-position:0px -279px;}
        i.ACB { background-position:0px -310px;}
        i.HDB { background-position:0px -341px;}
        i.MSB { background-position:0px -372px;}
        i.NVB { background-position:0px -403px;}
        i.DAB { background-position:0px -434px;}
        i.SHB { background-position:0px -465px;}
        i.OJB { background-position:0px -496px;}
        i.SEA { background-position:0px -527px;}
        i.TPB { background-position:0px -558px;}
        i.PGB { background-position:0px -589px;}
        i.BIDV { background-position:0px -620px;}
        i.AGB { background-position:0px -651px;}
        i.SCB { background-position:0px -682px;}
        i.VPB { background-position:0px -713px;}
        i.VAB { background-position:0px -744px;}
        i.GPB { background-position:0px -775px;}
        i.SGB { background-position:0px -806px;}
        i.NAB { background-position:0px -837px;}
        i.BAB { background-position:0px -868px;}

        ul.cardList li {
            cursor: pointer;
            float: left;
            margin-right: 0;
            padding: 5px 4px;
            text-align: center;
            width: 90px;
        }
    </style>
</head>
<?php
if(@$_POST['nlpayment']) {
//            include('config.php');
//            include('includes/NL_Checkoutv3.php');
//        require_once(dirname(__FILE__).'/config.php');
    $nlcheckout= new NL_CheckOutV3(MERCHANT_ID,MERCHANT_PASS,RECEIVER);

    $array_items[0]= array('item_name1' => 'Product name',
        'item_quantity1' => 1,
        'item_amount1' => $_POST['total_amount'],
        'item_url1' => 'http://nganluong.vn/');


//	 $payment_method =$_POST['option_payment'];
    $payment_method ='ATM_ONLINE';
    $bank_code =isset($_POST['bankcode'])?$_POST['bankcode']:'';
    $order_code ="macode_oerder123";
    $total_amount=$_POST['total_amount'];;
    $payment_type ='';
    $discount_amount =0;
    $order_description='';
    $tax_amount=0;
    $fee_shipping=0;
    $return_url = home_url().'/thanh-toan-khoa-hoc';
    $cancel_url =urlencode('http://thanhtoanonline.vn/?portal=topupairtime&abc=23434') ;


    $buyer_fullname =$_POST['buyer_fullname'];
    $buyer_email =$_POST['buyer_email'];
    $buyer_mobile =$_POST['buyer_mobile'];

    $buyer_address ='';




    if($payment_method !='' && $buyer_email !="" && $buyer_mobile !="" && $buyer_fullname !="" && filter_var( $buyer_email, FILTER_VALIDATE_EMAIL )  ){
//		if($payment_method =="VISA"){
//
//			$nl_result= $nlcheckout->VisaCheckout($order_code,$total_amount,$payment_type,$order_description,$tax_amount,
//											  $fee_shipping,$discount_amount,$return_url,$cancel_url,$buyer_fullname,$buyer_email,$buyer_mobile,
//									          $buyer_address,$array_items);
//
//		}elseif($payment_method =="NL"){
//			$nl_result= $nlcheckout->NLCheckout($order_code,$total_amount,$payment_type,$order_description,$tax_amount,
//												$fee_shipping,$discount_amount,$return_url,$cancel_url,$buyer_fullname,$buyer_email,$buyer_mobile,
//												$buyer_address,$array_items);
//
//		}else
        if($payment_method =="ATM_ONLINE" && $bank_code !='' ){
            $nl_result= $nlcheckout->BankCheckout($order_code,$total_amount,$bank_code,$payment_type,$order_description,$tax_amount,
                $fee_shipping,$discount_amount,$return_url,$cancel_url,$buyer_fullname,$buyer_email,$buyer_mobile,
                $buyer_address,$array_items) ;

            ?>
            <script type="text/javascript">
                <!--
                window.location = "<?php echo(string)$nl_result->checkout_url; ?>"
                //-->
            </script>
        <?PHP
        }


//		if ($nl_result->error_code =='00'){


        //Cập nhât order với token  $nl_result->token để sử dụng check hoàn thành sau này

        //https://www.nganluong.vn/checkout.api.nganluong.post.php?cur_code=usd&function=SetExpressCheckout&version=3.1&merchant_id=24338&receiver_email=hoannet@gmail.com&merchant_password=f1bfd514f667cebd7595218b5a40d5b1&order_code=228&total_amount=0.1&payment_method=VISA&payment_type=&order_description=&tax_amount=0&fee_shipping=0&discount_amount=0&return_url=http://smiletouristvietnam.com/book/successpayment&cancel_url=http://smiletouristvietnam.com/book/successpayment&buyer_fullname=&buyer_email=&buyer_mobile=&buyer_address=&total_item=1&item_name1=228&item_quantity1=1&item_amount1=0.1&item_url1=http://nganluong.vn/
//		}else{
//			echo $nl_result->error_message;
//		}

    }
    else{
        echo "<h3> Bạn chưa nhập đủ thông tin khách hàng </h3>";
    }
}

?>

<body>

<h3>Chọn phương thức thanh toán</h3>

<center>
<form  name="NLpayBank" action="#" method="post">
<table style="clear:both;width:500px;padding-left:46px;font-size: 12pt;"">
    <tr><td>Số tiền chuyển khoảng </td>
        <td>
            <select  name="total_amount" style="width:270px" id="fullname"  class="field-check form-control" >
                <option value="50000">50.000 VNĐ</option>
                <option value="100000">100.000 VNĐ</option>
                <option value="200000">200.000 VNĐ</option>
                <option value="500000">500.000 VNĐ</option>
                <option value="1000000">1.000.000 VNĐ</option>
            </select>
        </td>
    </tr>
</table><br/>
<ul class="list-content">
    <!--		<li class="active">-->
    <!--			<label><input type="radio" value="NL" name="option_payment" selected="true">Thanh toán bằng Ví điện tử NgânLượng</label>-->
    <!--			<div class="boxContent">-->
    <!--				<p>-->
    <!--				Thanh toán trực tuyến AN TOÀN và ĐƯỢC BẢO VỆ, sử dụng thẻ ngân hàng trong và ngoài nước hoặc nhiều hình thức tiện lợi khác.-->
    <!--				Được bảo hộ & cấp phép bởi NGÂN HÀNG NHÀ NƯỚC, ví điện tử duy nhất được cộng đồng ƯA THÍCH NHẤT 2 năm liên tiếp, Bộ Thông tin Truyền thông trao giải thưởng Sao Khuê-->
    <!--				<br/>Giao dịch. Đăng ký ví NgânLượng.vn miễn phí <a href="https://www.nganluong.vn/?portal=nganluong&amp;page=user_register" target="_blank">tại đây</a></p>-->
    <!--			</div>-->
    <!--		</li>-->
    <li class="active">
        <!--			<label><input type="radio" value="ATM_ONLINE" name="option_payment" selected="true">Thanh toán online bằng thẻ ngân hàng nội địa</label>-->
        <div class="boxContent">
            <p><i>
                    <span style="color:#ff5a00;font-weight:bold;text-decoration:underline;">Lưu ý</span>: Bạn cần đăng ký Internet-Banking hoặc dịch vụ thanh toán trực tuyến tại ngân hàng trước khi thực hiện.</i></p>

            <ul class="cardList clearfix">
                <li class="bank-online-methods ">
                    <label for="bidv_ck_on">
                        <i class="BIDV" title="Ngân hàng Đầu tư &amp; Phát triển Việt Nam"></i>
                        <input type="radio" value="BIDV"  name="bankcode" >

                    </label></li>

                <li class="bank-online-methods ">
                    <label for="vcb_ck_on">
                        <i class="VCB" title="Ngân hàng TMCP Ngoại Thương Việt Nam"></i>
                        <input type="radio" value="VCB"  name="bankcode" >

                    </label></li>

                <li class="bank-online-methods ">
                    <label for="vnbc_ck_on">
                        <i class="DAB" title="Ngân hàng Đông Á"></i>
                        <input type="radio" value="DAB"  name="bankcode" >

                    </label></li>

                <li class="bank-online-methods ">
                    <label for="tcb_ck_on">
                        <i class="TCB" title="Ngân hàng Kỹ Thương"></i>
                        <input type="radio" value="TCB"  name="bankcode" >

                    </label></li>

                <li class="bank-online-methods ">
                    <label for="sml_atm_mb_ck_on">
                        <i class="MB" title="Ngân hàng Quân Đội"></i>
                        <input type="radio" value="MB"  name="bankcode" >

                    </label></li>

                <li class="bank-online-methods ">
                    <label for="shb_ck_on">
                        <i class="SHB" title="Ngân hàng Sài Gòn - Hà Nội"></i>
                        <input type="radio" value="SHB"  name="bankcode" >

                    </label></li>

                <li class="bank-online-methods ">
                    <label for="sml_atm_vib_ck_on">
                        <i class="VIB" title="Ngân hàng Quốc tế"></i>
                        <input type="radio" value="VIB"  name="bankcode" >

                    </label></li>

                <li class="bank-online-methods ">
                    <label for="sml_atm_vtb_ck_on">
                        <i class="ICB" title="Ngân hàng Công Thương Việt Nam"></i>
                        <input type="radio" value="ICB"  name="bankcode" >

                    </label></li>

                <li class="bank-online-methods ">
                    <label for="sml_atm_exb_ck_on">
                        <i class="EXB" title="Ngân hàng Xuất Nhập Khẩu"></i>
                        <input type="radio" value="ICB"  name="bankcode" >

                    </label></li>

                <li class="bank-online-methods ">
                    <label for="sml_atm_acb_ck_on">
                        <i class="ACB" title="Ngân hàng Á Châu"></i>
                        <input type="radio" value="ACB"  name="bankcode" >

                    </label></li>

                <li class="bank-online-methods ">
                    <label for="sml_atm_hdb_ck_on">
                        <i class="HDB" title="Ngân hàng Phát triển Nhà TPHCM"></i>
                        <input type="radio" value="HDB"  name="bankcode" >

                    </label></li>

                <li class="bank-online-methods ">
                    <label for="sml_atm_msb_ck_on">
                        <i class="MSB" title="Ngân hàng Hàng Hải"></i>
                        <input type="radio" value="MSB"  name="bankcode" >

                    </label></li>

                <li class="bank-online-methods ">
                    <label for="sml_atm_nvb_ck_on">
                        <i class="NVB" title="Ngân hàng Nam Việt"></i>
                        <input type="radio" value="NVB"  name="bankcode" >

                    </label></li>

                <li class="bank-online-methods ">
                    <label for="sml_atm_vab_ck_on">
                        <i class="VAB" title="Ngân hàng Việt Á"></i>
                        <input type="radio" value="VAB"  name="bankcode" >

                    </label></li>

                <li class="bank-online-methods ">
                    <label for="sml_atm_vpb_ck_on">
                        <i class="VPB" title="Ngân Hàng Việt Nam Thịnh Vượng"></i>
                        <input type="radio" value="VPB"  name="bankcode" >

                    </label></li>

                <li class="bank-online-methods ">
                    <label for="sml_atm_scb_ck_on">
                        <i class="SCB" title="Ngân hàng Sài Gòn Thương tín"></i>
                        <input type="radio" value="SCB"  name="bankcode" >

                    </label></li>

                <li class="bank-online-methods ">
                    <label for="ojb_ck_on">
                        <i class="OJB" title="Ngân hàng Đại Dương"></i>
                        <input type="radio" value="OJB"  name="bankcode" >

                    </label></li>

                <li class="bank-online-methods ">
                    <label for="bnt_atm_pgb_ck_on">
                        <i class="PGB" title="Ngân hàng Xăng dầu Petrolimex"></i>
                        <input type="radio" value="PGB"  name="bankcode" >

                    </label></li>

                <li class="bank-online-methods ">
                    <label for="bnt_atm_gpb_ck_on">
                        <i class="GPB" title="Ngân hàng TMCP Dầu khí Toàn Cầu"></i>
                        <input type="radio" value="GPB"  name="bankcode" >

                    </label></li>

                <li class="bank-online-methods ">
                    <label for="bnt_atm_agb_ck_on">
                        <i class="AGB" title="Ngân hàng Nông nghiệp &amp; Phát triển nông thôn"></i>
                        <input type="radio" value="AGB"  name="bankcode" >

                    </label></li>

                <li class="bank-online-methods ">
                    <label for="bnt_atm_sgb_ck_on">
                        <i class="SGB" title="Ngân hàng Sài Gòn Công Thương"></i>
                        <input type="radio" value="SGB"  name="bankcode" >

                    </label></li>

                <li class="bank-online-methods ">
                    <label for="bnt_atm_nab_ck_on">
                        <i class="NAB" title="Ngân hàng Nam Á"></i>
                        <input type="radio" value="NAB"  name="bankcode" >

                    </label></li>

                <li class="bank-online-methods ">
                    <label for="sml_atm_bab_ck_on">
                        <i class="BAB" title="Ngân hàng Bắc Á"></i>
                        <input type="radio" value="BAB"  name="bankcode" >

                    </label></li>

            </ul>

        </div>
    </li>
    <!--		<li>-->
    <!--			<label><input type="radio" value="VISA" name="option_payment" selected="true">Thanh toán bằng thẻ Visa hoặc MasterCard</label>-->
    <!--			<div class="boxContent">-->
    <!--				<p><span style="color:#ff5a00;font-weight:bold;text-decoration:underline;">Lưu ý</span>:Dùng thẻ do các ngân hàng trong nước phát hành.</p>-->
    <!--				-->
    <!--			</div>-->
    <!--		</li>-->
</ul>

<?php
$user = get_user_by('id',get_current_user_id());
?>

<table style="clear:both;width:500px;padding-left:46px">
    <tr style="display: none"><td colspan="2"><p><span style="color:#ff5a00;font-weight:bold;text-decoration:underline;display:none">Lưu ý</span> Bạn nhập đầy đủ thông tin sau </td>

    </tr>
    <tr style="display: none"><td>Họ Tên: </td>
        <td>
            <input type="text" style="width:270px" id="fullname" name="buyer_fullname" class="field-check " value="<?php echo $user->display_name; ?>">
        </td>
    </tr>
    <tr style="display: none"><td>Email: </td>
        <td>
            <input type="text" style="width:270px" id="fullname" name="buyer_email" class="field-check " value="<?php echo $user->user_email; ?>">
        </td>
    </tr>
    <tr style="display: none"><td>Số Điện thoại: </td>
        <td>
            <input type="text" style="width:270px" id="fullname" name="buyer_mobile" class="field-check " value="<?php if(get_user_meta(get_current_user_id(),'billing_phone',true))  echo get_user_meta(get_current_user_id(),'billing_phone',true); else echo '0983599954'; ?>">
        </td>
    </tr>
    <tr><td></td>
        <td style="text-align:center;">
            <input type="submit" name="nlpayment" value="thanh toán" style="font-size:11pt;"/>
        </td>
    </tr>
</table>

</form>
</center>
<script src="https://www.nganluong.vn/webskins/javascripts/jquery_min.js" type="text/javascript"></script>
<script language="javascript">
    $('input[name="option_payment"]').bind('click', function() {
        $('.list-content li').removeClass('active');
        $(this).parent().parent('li').addClass('active');
    });
</script>
</body>
</html>
</div>
<p></p>
    <div class="row naptienvaotaikhoan anhien" dt-toggle="coll_4" style="background-color: #fa3031">
        <div class="col-md-12 col-sm-12 ">
            <h3 style="float: left;">3. Chuyển khoản (Cộng thêm 10% giá trị chuyển khoản)</h3>
        </div>
        <i class="glyphicon-plus icon_coll_4"></i><i class="glyphicon-minus icon_coll_4" style="display:none;"></i>
    </div>

<div class="row chuyenkhoannganhang" id="coll_4" style="display: none">

    <div class="col-md-12 noidungchuyenkhoan">
                        <span>
                            <h4>  <?php  _e('Chuyển khoản ngân hàng','vibe') ?>​​</h4>
                            <?php _e('Bạn có thể đến bất kỳ ngân hàng nào ở Việt Nam (hoặc sử dụng Internet Banking) để chuyển tiền theo thông tin bên dưới:','vibe') ?>​<br/>
                            <?php  _e('Số tài khoản: <b>0071 00485 7814</b>','vibe') ?>​​<br/>
                            <?php  _e('Chủ tài khoản: <b>Lê Quang Song</b>','vibe') ?><br/>​
                            <?php  _e('Ngân hàng: Ngân hàng Vietcombank, chi nhánh Kì Đồng, TPHCM','vibe') ?>​​<br/><br/>

                            <?php  _e('Số tài khoản: <b>711A A172 3168</b>','vibe') ?>​​<br/>
                            <?php  _e('Chủ tài khoản: <b>Lê Quang Song</b>','vibe') ?><br/>​
                            <?php  _e('Ngân hàng: Ngân hàng Vietin Bank, TPHCM','vibe') ?>​​<br/><br/>

                            <?php  _e('Ghi chú khi chuyển khoản:','vibe') ?>​​<br/>
                            <?php  _e(' Tại mục "Ghi chú" khi chuyển khoản, bạn ghi rõ: Số điện thoại - Họ Tên - Email đăng ký học - Mã Khóa học đăng ký​​','vibe') ?>​​<br/>
                            <?php  _e('Ví dụ: 0909090909 - Nguyen Thi Huong Lan - lannguyen0803@gmail.com - ADR004','vibe') ?>​​<br/>
                        </span>

                         <span>
                            <h4>  <?php  _e('Chuyển tiền qua PAYPAL','vibe') ?>​​<br/></h4>
                             <?php  _e('Địa chỉ email nhận tiền: song.lequang@gmail.com. Tỉ giá áp dụng: 1 USD = 20.300 VNĐ (tỉ giá trên PayPal).','vibe') ?>​​<br/>
                             <?php  _e('Tại mục "Message" khi chuyển tiền, bạn ghi rõ: Số điện thoại - Họ Tên - Email đăng ký học – Mã Khóa học.​​​','vibe') ?>​​<br/>
                              </span>
    </div>
    <br />
    <i><b>
        <span style="color:#ff5a00;font-weight:bold;text-decoration:underline;">Lưu ý:</span>
        <?php  _e('Giao dịch sẽ hoàn tất khi Itclass.vn nhận được tiền từ ngân hàng, thường là từ 1 – 24 giờ. ITClass.vn sẽ nạp tiền vào tài khoản của bạn và email báo cho bạn biết khi giao dịch hoàn tất.​​','vibe') ?>​​
    </b></i>
    <br/>
    <b> Mọi thắc mắc xin liên hệ <u>Email:</u> support@myclass.vn. <u>Hotline:</u> 0961.05.10.14</b>
</div>

</div>
</div>
</div>
</div>

</section>


<?php
get_footer();
?>

