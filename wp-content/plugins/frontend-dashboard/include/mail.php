<?php
/**
 * to send emails with plain text template
 */

class RhMail {
    private $base_template;

    public function __construct() {
        $this->base_template = $this->base_template();
    }

    public function base_template(){
        ob_start();
        ?>
        <!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
        <html xmlns:fb="http://www.facebook.com/2008/fbml" xmlns:og="http://opengraph.org/schema/">
        <head>
            <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1">
            <!-- NAME: 1 COLUMN -->
            <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
            <meta name="viewport" content="width=device-width, initial-scale=1.0">
            <style type="text/css">
                body,#bodyTable,#bodyCell{
                    height:100% !important;
                    margin:0;
                    padding:0;
                    width:100% !important;
                }
                table{
                    border-collapse:collapse;
                }
                img,a img{
                    border:0;
                    outline:none;
                    text-decoration:none;
                }
                h1,h2,h3,h4,h5,h6{
                    margin:0;
                    padding:0;
                }
                p{
                    margin:1em 0;
                    padding:0;
                }
                a{
                    word-wrap:break-word;
                }
                .ReadMsgBody{
                    width:100%;
                }
                .ExternalClass{
                    width:100%;
                }
                .ExternalClass,.ExternalClass p,.ExternalClass span,.ExternalClass font,.ExternalClass td,.ExternalClass div{
                    line-height:100%;
                }
                table,td{
                    mso-table-lspace:0pt;
                    mso-table-rspace:0pt;
                }
                #outlook a{
                    padding:0;
                }
                img{
                    -ms-interpolation-mode:bicubic;
                }
                body,table,td,p,a,li,blockquote{
                    -ms-text-size-adjust:100%;
                    -webkit-text-size-adjust:100%;
                }
                #templatePreheader,#templateHeader,#templateBody,#templateFooter{
                    min-width:100%;
                }
                #bodyCell{
                    padding:20px;
                }
                .mcnImage{
                    vertical-align:bottom;
                }
                .mcnTextContent img{
                    height:auto !important;
                }
                body,#bodyTable{
                    background-color:#FFF;
                }
                #bodyCell{
                    border-top:0;
                }
                #templateContainer{
                    border:0;
                }
                h1{
                    color:#282733 !important;
                    display:block;
                    font-family:Helvetica;
                    font-size:32px;
                    font-style:normal;
                    font-weight:bold;
                    line-height:125%;
                    letter-spacing:-1px;
                    margin:0;
                    text-align:center;
                }
                h2{
                    color:#282733 !important;
                    display:block;
                    font-family:Helvetica;
                    font-size:26px;
                    font-style:normal;
                    font-weight:normal;
                    line-height:125%;
                    letter-spacing:-.75px;
                    margin:0;
                    text-align:center;
                }
                h3{
                    color:#666666 !important;
                    display:block;
                    font-family:Helvetica;
                    font-size:14px;
                    font-style:normal;
                    font-weight:normal;
                    line-height:125%;
                    letter-spacing:-.5px;
                    margin:0;
                    text-align:left;
                }
                h4{
                    color:#808080 !important;
                    display:block;
                    font-family:Helvetica;
                    font-size:16px;
                    font-style:normal;
                    font-weight:bold;
                    line-height:125%;
                    letter-spacing:normal;
                    margin:0;
                    text-align:left;
                }
                #templatePreheader{
                    background-color:#f2f2f2;
                    border-top:0;
                    border-bottom:0;
                }
                .preheaderContainer .mcnTextContent,.preheaderContainer .mcnTextContent p{
                    color:#606060;
                    font-family:Helvetica;
                    font-size:11px;
                    line-height:125%;
                    text-align:left;
                }
                .preheaderContainer .mcnTextContent a{
                    color:#606060;
                    font-weight:normal;
                    text-decoration:underline;
                }
                #templateHeader{
                    background-color:#ffffff;
                    /*border-top:10px solid #6ebdc5;*/
                    border-bottom:0;
                }
                .headerContainer .mcnTextContent,.headerContainer .mcnTextContent p{
                    color:#606060;
                    font-family:Helvetica;
                    font-size:15px;
                    line-height:150%;
                    text-align:left;
                }
                .headerContainer .mcnTextContent a{
                    color:#6DC6DD;
                    font-weight:normal;
                    text-decoration:underline;
                    word-break: break-all;
                }
                #templateBody{
                    background-color:#FFFFFF;
                    border-top:0;
                    border-bottom:0;
                }
                .bodyContainer .mcnTextContent,.bodyContainer .mcnTextContent p{
                    color:#;
                    font-family:Helvetica;
                    font-size:14px;
                    line-height:150%;
                    text-align:left;
                }
                .bodyContainer .mcnTextContent a{
                    color:#78bec4;
                    font-weight:normal;
                    text-decoration:underline;
                }
                #templateFooter{
                    background-color:#f2f2f2;
                    border-top:0;
                    border-bottom:0;
                }
                .footerContainer .mcnTextContent,.footerContainer .mcnTextContent p{
                    color:#606060;
                    font-family:Helvetica;
                    font-size:11px;
                    line-height:125%;
                    text-align:left;
                }
                .footerContainer .mcnTextContent a{
                    color:#606060;
                    font-weight:normal;
                    text-decoration:underline;
                }

                button {
                    color: #fff!important;
                    border: 1px solid #2020af;
                    background: #2020af;
                    font-weight: 700;
                    font-weight: 400;
                    padding: 3px 12px;
                    display: inline-block;
                    border-radius: 0!important;
                    font-family: Roboto;
                    line-height: 1.4;
                    transition: background .2s ease-out;
                }


                @media only screen and (max-width: 480px){
                    body,table,td,p,a,li,blockquote{
                        -webkit-text-size-adjust:none !important;
                    }

                }	@media only screen and (max-width: 480px){
                    body{
                        width:100% !important;
                        min-width:100% !important;
                    }

                }	@media only screen and (max-width: 480px){
                    td[id=bodyCell]{
                        padding:10px !important;
                    }

                }	@media only screen and (max-width: 480px){
                    table[class=mcnTextContentContainer]{
                        width:100% !important;
                    }

                }	@media only screen and (max-width: 480px){
                    .mcnBoxedTextContentContainer{
                        max-width:100% !important;
                        min-width:100% !important;
                        width:100% !important;
                    }

                }	@media only screen and (max-width: 480px){
                    table[class=mcpreview-image-uploader]{
                        width:100% !important;
                        display:none !important;
                    }

                }	@media only screen and (max-width: 480px){
                    img[class=mcnImage]{
                        width:100% !important;
                    }

                }	@media only screen and (max-width: 480px){
                    table[class=mcnImageGroupContentContainer]{
                        width:100% !important;
                    }

                }	@media only screen and (max-width: 480px){
                    td[class=mcnImageGroupContent]{
                        padding:9px !important;
                    }

                }	@media only screen and (max-width: 480px){
                    td[class=mcnImageGroupBlockInner]{
                        padding-bottom:0 !important;
                        padding-top:0 !important;
                    }

                }	@media only screen and (max-width: 480px){
                    tbody[class=mcnImageGroupBlockOuter]{
                        padding-bottom:9px !important;
                        padding-top:9px !important;
                    }

                }	@media only screen and (max-width: 480px){
                    table[class=mcnCaptionTopContent],table[class=mcnCaptionBottomContent]{
                        width:100% !important;
                    }

                }	@media only screen and (max-width: 480px){
                    table[class=mcnCaptionLeftTextContentContainer],table[class=mcnCaptionRightTextContentContainer],table[class=mcnCaptionLeftImageContentContainer],table[class=mcnCaptionRightImageContentContainer],table[class=mcnImageCardLeftTextContentContainer],table[class=mcnImageCardRightTextContentContainer]{
                        width:100% !important;
                    }

                }	@media only screen and (max-width: 480px){
                    td[class=mcnImageCardLeftImageContent],td[class=mcnImageCardRightImageContent]{
                        padding-right:18px !important;
                        padding-left:18px !important;
                        padding-bottom:0 !important;
                    }

                }	@media only screen and (max-width: 480px){
                    td[class=mcnImageCardBottomImageContent]{
                        padding-bottom:9px !important;
                    }

                }	@media only screen and (max-width: 480px){
                    td[class=mcnImageCardTopImageContent]{
                        padding-top:18px !important;
                    }

                }	@media only screen and (max-width: 480px){
                    td[class=mcnImageCardLeftImageContent],td[class=mcnImageCardRightImageContent]{
                        padding-right:18px !important;
                        padding-left:18px !important;
                        padding-bottom:0 !important;
                    }

                }	@media only screen and (max-width: 480px){
                    td[class=mcnImageCardBottomImageContent]{
                        padding-bottom:9px !important;
                    }

                }	@media only screen and (max-width: 480px){
                    td[class=mcnImageCardTopImageContent]{
                        padding-top:18px !important;
                    }

                }	@media only screen and (max-width: 480px){
                    table[class=mcnCaptionLeftContentOuter] td[class=mcnTextContent],table[class=mcnCaptionRightContentOuter] td[class=mcnTextContent]{
                        padding-top:9px !important;
                    }

                }	@media only screen and (max-width: 480px){
                    td[class=mcnCaptionBlockInner] table[class=mcnCaptionTopContent]:last-child td[class=mcnTextContent]{
                        padding-top:18px !important;
                    }

                }	@media only screen and (max-width: 480px){
                    td[class=mcnBoxedTextContentColumn]{
                        padding-left:18px !important;
                        padding-right:18px !important;
                    }

                }	@media only screen and (max-width: 480px){
                    td[class=mcnTextContent]{
                        padding-right:18px !important;
                        padding-left:18px !important;
                    }

                }	@media only screen and (max-width: 480px){
                    table[id=templateContainer],table[id=templatePreheader],table[id=templateHeader],table[id=templateBody],table[id=templateFooter]{
                        max-width:600px !important;
                        width:100% !important;
                    }

                }	@media only screen and (max-width: 480px){
                    h1{
                        font-size:24px !important;
                        line-height:125% !important;
                    }

                }	@media only screen and (max-width: 480px){
                    h2{
                        font-size:20px !important;
                        line-height:125% !important;
                    }

                }	@media only screen and (max-width: 480px){
                    h3{
                        font-size:18px !important;
                        line-height:125% !important;
                    }

                }	@media only screen and (max-width: 480px){
                    h4{
                        font-size:16px !important;
                        line-height:125% !important;
                    }

                }	@media only screen and (max-width: 480px){
                    table[class=mcnBoxedTextContentContainer] td[class=mcnTextContent],td[class=mcnBoxedTextContentContainer] td[class=mcnTextContent] p{
                        font-size:18px !important;
                        line-height:125% !important;
                    }

                }	@media only screen and (max-width: 480px){
                    table[id=templatePreheader]{
                        display:block !important;
                    }

                }	@media only screen and (max-width: 480px){
                    td[class=preheaderContainer] td[class=mcnTextContent],td[class=preheaderContainer] td[class=mcnTextContent] p{
                        font-size:14px !important;
                        line-height:115% !important;
                    }

                }	@media only screen and (max-width: 480px){
                    td[class=headerContainer] td[class=mcnTextContent],td[class=headerContainer] td[class=mcnTextContent] p{
                        font-size:18px !important;
                        line-height:125% !important;
                    }

                }	@media only screen and (max-width: 480px){
                    td[class=bodyContainer] td[class=mcnTextContent],td[class=bodyContainer] td[class=mcnTextContent] p{
                        font-size:18px !important;
                        line-height:125% !important;
                    }

                }	@media only screen and (max-width: 480px){
                    td[class=footerContainer] td[class=mcnTextContent],td[class=footerContainer] td[class=mcnTextContent] p{
                        font-size:14px !important;
                        line-height:115% !important;
                    }

                }	@media only screen and (max-width: 480px){
                    td[class=footerContainer] a[class=utilityLink]{
                        display:block !important;
                    }

                }
            </style>
        </head>

        <body leftmargin="0" marginwidth="0" topmargin="0" marginheight="0" offset="0" style="margin: 0;padding: 0;-ms-text-size-adjust: 100%;-webkit-text-size-adjust: 100%;background-color: #FFF;height: 100% !important;width: 100% !important;">
        <center>
            <table align="center" border="0" cellpadding="0" cellspacing="0" height="100%" width="100%" id="bodyTable" style="border-collapse: collapse;mso-table-lspace: 0pt;mso-table-rspace: 0pt;-ms-text-size-adjust: 100%;-webkit-text-size-adjust: 100%;margin: 0;padding: 0;background-color: #FFF;height: 100% !important;width: 100% !important;">
                <tr>
                    <td align="center" valign="top" id="bodyCell" style="mso-table-lspace: 0pt;mso-table-rspace: 0pt;-ms-text-size-adjust: 100%;-webkit-text-size-adjust: 100%;margin: 0;padding: 20px;border-top: 0;height: 100% !important;width: 100% !important;">
                        <!-- BEGIN TEMPLATE // -->
                        <table border="0" cellpadding="0" cellspacing="0" width="600" id="templateContainer" style="border-collapse: collapse;mso-table-lspace: 0pt;mso-table-rspace: 0pt;-ms-text-size-adjust: 100%;-webkit-text-size-adjust: 100%;border: 0;">

                            <tr>
                                <td align="center" valign="top" style="mso-table-lspace: 0pt;mso-table-rspace: 0pt;-ms-text-size-adjust: 100%;-webkit-text-size-adjust: 100%;">
                                    <!-- BEGIN HEADER // -->
                                    <table border="0" cellpadding="0" cellspacing="0" width="600" id="templateHeader" style="border-collapse: collapse;mso-table-lspace: 0pt;mso-table-rspace: 0pt;-ms-text-size-adjust: 100%;-webkit-text-size-adjust: 100%;min-width: 100%;background-color: #ffffff;/*border-top: 10px solid #6ebdc5;*/border-bottom: 0;border-bottom: 0;">
                                        <tr>
                                            <td valign="top" class="headerContainer" style="mso-table-lspace: 0pt;mso-table-rspace: 0pt;-ms-text-size-adjust: 100%;-webkit-text-size-adjust: 100%;">
                                                <table border="0" cellpadding="0" cellspacing="0" width="100%" class="mcnTextBlock" style="border-collapse: collapse;mso-table-lspace: 0pt;mso-table-rspace: 0pt;-ms-text-size-adjust: 100%;-webkit-text-size-adjust: 100%;">
                                                    <tbody class="mcnTextBlockOuter">
                                                    <tr>
                                                        <td valign="top" class="mcnTextBlockInner" style="mso-table-lspace: 0pt;mso-table-rspace: 0pt;-ms-text-size-adjust: 100%;-webkit-text-size-adjust: 100%;">

                                                            <table align="left" border="0" cellpadding="0" cellspacing="0" width="100%" class="mcnTextContentContainer" style="border-collapse: collapse;mso-table-lspace: 0pt;mso-table-rspace: 0pt;-ms-text-size-adjust: 100%;-webkit-text-size-adjust: 100%;">
                                                                <tbody>
                                                                <tr>

                                                                    <td valign="top" class="mcnTextContent" style="padding-top: 9px;padding-right: 18px;padding-bottom: 0px;padding-left: 18px;mso-table-lspace: 0pt;mso-table-rspace: 0pt;-ms-text-size-adjust: 100%;-webkit-text-size-adjust: 100%;color: #606060;font-family: Helvetica;font-size: 15px;line-height: 150%;text-align: left;">
                                                                        <img style="width:200px;height:38px;margin:0 0 0px 0;padding-right:30px;" alt="" width="200" height="38" src="http://roofhub.com/wp-content/uploads/2018/04/RoofHub_LogoWP.jpg" class="CToWUd">
                                                                    </td>
                                                                </tr>
                                                                </tbody>
                                                            </table>

                                                        </td>
                                                    </tr>
                                                    </tbody>
                                                </table>
                                            </td>
                                        </tr>

                                        <tr>
                                            <td valign="top" class="bodyContainer" style="mso-table-lspace: 0pt;mso-table-rspace: 0pt;-ms-text-size-adjust: 100%;-webkit-text-size-adjust: 100%;">
                                                <table border="0" cellpadding="0" cellspacing="0" width="100%" class="mcnTextBlock" style="border-collapse: collapse;mso-table-lspace: 0pt;mso-table-rspace: 0pt;-ms-text-size-adjust: 100%;-webkit-text-size-adjust: 100%;">
                                                    <tbody class="mcnTextBlockOuter">
                                                    <tr>
                                                        <td valign="top" class="mcnTextBlockInner" style="mso-table-lspace: 0pt;mso-table-rspace: 0pt;-ms-text-size-adjust: 100%;-webkit-text-size-adjust: 100%;">

                                                            <table align="left" border="0" cellpadding="0" cellspacing="0" width="100%" class="mcnTextContentContainer" style="border-collapse: collapse;mso-table-lspace: 0pt;mso-table-rspace: 0pt;-ms-text-size-adjust: 100%;-webkit-text-size-adjust: 100%;">
                                                                <tbody>
                                                                <tr>

                                                                    <td valign="top" class="mcnTextContent" style="padding-top: 9px;padding-right: 18px;padding-bottom: 9px;padding-left: 18px;mso-table-lspace: 0pt;mso-table-rspace: 0pt;-ms-text-size-adjust: 100%;-webkit-text-size-adjust: 100%;color: #606060;font-family: Helvetica;font-size: 15px;line-height: 150%;text-align: left;">
                                                                        {{message}}
                                                                    </td>
                                                                </tr>
                                                                </tbody>
                                                            </table>

                                                        </td>
                                                    </tr>
                                                    </tbody>
                                                </table>
                                            </td>
                                        </tr>

                                        <tr>
                                            <td valign="top" class="footerContainer" style="mso-table-lspace: 0pt;mso-table-rspace: 0pt;-ms-text-size-adjust: 100%;-webkit-text-size-adjust: 100%;">
                                                <table border="0" cellpadding="0" cellspacing="0" width="100%" class="mcnTextBlock" style="border-collapse: collapse;mso-table-lspace: 0pt;mso-table-rspace: 0pt;-ms-text-size-adjust: 100%;-webkit-text-size-adjust: 100%;">
                                                    <tbody class="mcnTextBlockOuter">
                                                    <tr>
                                                        <td valign="top" class="mcnTextBlockInner" style="mso-table-lspace: 0pt;mso-table-rspace: 0pt;-ms-text-size-adjust: 100%;-webkit-text-size-adjust: 100%;">

                                                            <table align="left" border="0" cellpadding="0" cellspacing="0" width="100%" class="mcnTextContentContainer" style="border-collapse: collapse;mso-table-lspace: 0pt;mso-table-rspace: 0pt;-ms-text-size-adjust: 100%;-webkit-text-size-adjust: 100%;">
                                                                <tbody>
                                                                <tr>


                                                                    <td class="mcnTextContent" style="padding: 0 30px 40px;border-top: 1px solid #e1e1e4;line-height: 24px;font-size: 15px;color: #717274;text-align: center;width: 100%;">
                                                                        <table border="0" cellpadding="0" cellspacing="0" width="100%" align="center" style="margin-top:20px;background-color:white">
                                                                            <tbody>
                                                                            <tr>
                                                                                <td align="center" style="text-align: center;">
                                                                                    <img style="width:80px;height:20px;margin:0 0px 0px 0;vertical-align: middle;" alt="" width="20" height="20" src="http://roofhub.com/wp-content/uploads/2018/04/RoofHub_LogoWP.jpg" class="CToWUd"> <span style="vertical-align: middle;">Made by <a href="<?=site_url()?>" style="text-decoration:none;color:#434245" target="_blank">ROOFHUB</a></span>
                                                                                </td>
                                                                            </tr>
                                                                            </tbody></table>
                                                                    </td>
                                                                </tr>
                                                                </tbody>
                                                            </table>

                                                        </td>
                                                    </tr>
                                                    </tbody>
                                                </table>
                                            </td>
                                        </tr>
                                    </table>
                                    <!-- // END HEADER -->
                                </td>
                            </tr>


                        </table>
                        <!-- // END TEMPLATE -->
                    </td>
                </tr>
            </table>
        </center>
        </body>
        </html>
        <?php
        $message = ob_get_contents();
        ob_end_clean();
        return $message;
    }



    public function send_general_email($message='', $to='', $subject='', $user = false){

        $subject = $subject;
        $body_html = str_ireplace( '{{message}}', $message, $this->base_template);

        $headers[] = 'From: RoofHub <no-reply@roofhub.com>' . "\r\n";
        $headers[] = 'Reply-To: no-reply@roofhub.com' . "\r\n";
        $headers[] = 'Content-Type: text/html; charset=UTF-8';

        $send_result = wp_mail( $to, $subject, $body_html, $headers );
        return $send_result;
    }


    public function new_account_email($name, $password, $email){
        ob_start();
        ?>
        <h1 style="font-size:30px;">
            Welcome to RoofHub!	</h1>
        <p style="font-size:17px;padding-right:30px;">

            You’ve joined <?=$name?> on <strong>RoofHub</strong>. Here are your account details.
        </p>


        <div style="padding-right:30px;">
            <table style="table-layout:fixed;border:1px solid #a0a0a2;border-radius:8px;padding:40px 0;margin-top:20px;width:100%;border-collapse:separate" border="0" cellpadding="0" cellspacing="0">
                <tbody><tr>
                    <td align="center" style="vertical-align:middle">
                        <img src="http://roofhub.com/wp-content/uploads/2018/04/RoofHub_LogoWP.jpg" style="height:150px;width:38px;min-width:38px;border-radius:4px;color:#ffffff;font-size:18px;line-height:38px" alt="" class="CToWUd">

                        <h3 style="font-weight:900;padding-top:10px;margin-bottom:7px;font-size:21px;font-size:21px;margin-top:0;margin-top:0;text-align: left;">RoofHub</h3>


                        <h4 style="margin-bottom:2px;font-size:17px;font-weight:400;text-align: center;">
                            URL:<a href="<?=site_url("/dashboard")?>" style="white-space:nowrap;color:#0576b9" target="_blank">roofhub.com</a>
                        </h4>
                        <h4 style="margin-bottom:0;font-size:17px;font-weight:400;text-align: center;">
                            Email:<a href="mailto:<?=$email?>" style="white-space:nowrap;color:#0576b9;text-align: center;" target="_blank"><?=$email?></a>
                        </h4>
                        <h4 style="margin-bottom:0;font-size:17px;font-weight:400;text-align: center;">
                            Password:<?=$password?>
                        </h4>

                        <table width="102" border="0" cellpadding="0" cellspacing="0" style="text-align:right;display:inline-table;width:auto;vertical-align:top;width:102px;text-align:right">
                            <tbody>
                            <tr>
                                <td width="100" style="padding-top:20px">
                                    <div><a href="<?=site_url('sign-in')?>" style="text-align:center;text-decoration:none;display:inline-block;width:100px;border:1px solid #c7cacd;border-radius:4px;background-color:#fbfbfa;color:#555459;font-size:14px;line-height:40px;font-weight:900" target="_blank" >Sign In</a></div>

                                </td>
                            </tr>
                            </tbody>
                        </table>

                    </td>
                </tr>
                </tbody></table>
        </div>

        <p style="font-size:17px;padding-right:30px;margin-top:40px">

            We’ll send you a few quick e-mails on how to get the most out of RoofHub. In the meantime, dive in and <a href="<?=site_url()?>" style="color:#0576b9" target="_blank">start exploring</a>. Again, we’re glad you’re here!
        </p>
        <?php
        $message = ob_get_contents();
        ob_end_clean();
        return $message;
    }


    public function new_request_email_to_customer($name, $link, $rnumber){
        ob_start();
        ?>
        <h1 style="font-size:30px;">We've received your request.</h1>
        <p style="font-size:17px;padding-right:30px;">
            Your request number is <strong><?=$rnumber?></strong>.<br><br>

            Your request is under process.<br><br>

            In the meantime, you can review your request <a href="<?=$link?>" target="_blank">here</a>.<br><br>

            Thanks in advance for your patience and support.<br><br>

            -The RoofHub Team
        </p>
        <?php
        $message = ob_get_contents();
        ob_end_clean();
        return $message;
    }

    public function new_request_email_to_admin($name, $link, $rnumber){
        ob_start();
        ?>
        <h1 style="font-size:30px;">You've received a new request from <?=$name?>.</h1>
        <p style="font-size:17px;padding-right:30px;">
            Customer request number is <strong><?=$rnumber?></strong>.<br><br>

            In the meantime, you can review new request <a href="<?=$link?>" target="_blank">here</a>.<br><br>

            -The RoofHub Administrator
        </p>
        <?php
        $message = ob_get_contents();
        ob_end_clean();
        return $message;
    }


}