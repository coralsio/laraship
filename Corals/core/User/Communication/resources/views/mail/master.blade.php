<!DOCTYPE html>
<html>
<head>
    <title></title>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta http-equiv="X-UA-Compatible" content="IE=edge"/>
    <style type="text/css">
        /* CLIENT-SPECIFIC STYLES */
        body, table, td, a {
            -webkit-text-size-adjust: 100%;
            -ms-text-size-adjust: 100%;
        }

        table, td {
            mso-table-lspace: 0pt;
            mso-table-rspace: 0pt;
        }

        img {
            -ms-interpolation-mode: bicubic;
        }

        /* RESET STYLES */
        img {
            border: 0;
            height: auto;
            line-height: 100%;
            outline: none;
            text-decoration: none;
        }

        table {
            border-collapse: collapse !important;
        }

        body {
            height: 100% !important;
            margin: 0 !important;
            padding: 0 !important;
            width: 100% !important;
        }

        /* iOS BLUE LINKS */
        a[x-apple-data-detectors] {
            color: inherit !important;
            text-decoration: none !important;
            font-size: inherit !important;
            font-family: inherit !important;
            font-weight: inherit !important;
            line-height: inherit !important;
        }

        /* MEDIA QUERIES */
        @media screen and (max-width: 480px) {
            .mobile-hide {
                display: none !important;
            }

            .mobile-center {
                text-align: center !important;
            }
        }

        /* ANDROID CENTER FIX */
        div[style*="margin: 16px 0;"] {
            margin: 0 !important;
        }
    </style>
<body style="margin: 0 !important; padding: 0 !important; background-color: #eeeeee;" bgcolor="#eeeeee">

<!-- HIDDEN PREHEADER TEXT -->
<div style="display: none; font-size: 1px; color: #fefefe; line-height: 1px; font-family: Open Sans, Helvetica, Arial, sans-serif; max-height: 0px; max-width: 0px; opacity: 0; overflow: hidden;">
</div>

<table border="0" cellpadding="0" cellspacing="0" width="100%">
    <tr>
        <td align="center" style="background-color: #eeeeee;" bgcolor="#eeeeee">
            <!--[if (gte mso 9)|(IE)]>
            <table align="center" border="0" cellspacing="0" cellpadding="0" width="600">
                <tr>
                    <td align="center" valign="top" width="600">
            <![endif]-->
            <table align="center" border="0" cellpadding="0" cellspacing="0" width="100%" style="max-width:600px;">
                <tr>
                    <td align="center" valign="top" style="font-size:0; padding: 35px;" bgcolor="#044767">
                        <!--[if (gte mso 9)|(IE)]>
                        <table align="center" border="0" cellspacing="0" cellpadding="0" width="600">
                            <tr>
                                <td align="left" valign="top" width="300">
                        <![endif]-->
                        <div style="display:inline-block; max-width:50%; min-width:100px; vertical-align:top; width:100%;">

                            <table align="left" border="0" cellpadding="0" cellspacing="0" width="100%"
                                   style="max-width:300px;">
                                <tr>
                                    <td align="left" valign="top"
                                        style="font-family: Open Sans, Helvetica, Arial, sans-serif; font-size: 36px; font-weight: 800; line-height: 48px;"
                                        class="mobile-center">
                                        <h1 style="font-size: 36px; font-weight: 800; margin: 0; color: #ffffff;">
                                            {{ \Settings::get('site_name') }}</h1>
                                    </td>
                                </tr>
                            </table>
                        </div>
                        <!--[if (gte mso 9)|(IE)]>
                        </td>
                        <td align="right" width="300">
                        <![endif]-->
                        <div style="display:inline-block; max-width:50%; min-width:100px; vertical-align:top; width:100%;"
                             class="mobile-hide">
                            <table align="left" border="0" cellpadding="0" cellspacing="0" width="100%"
                                   style="max-width:300px;">
                                <tr>
                                    <td align="right" valign="top"
                                        style="font-family: Open Sans, Helvetica, Arial, sans-serif; font-size: 36px; font-weight: 800; line-height: 48px;"
                                        class="mobile-center">
                                        <a href="{{ url('/') }}" target="_blank"
                                           style="color: #ffffff; text-decoration: none;">
                                            <img src="{{ \Settings::get('site_logo') }}"
                                                 height="48"
                                                 style="display: block; border: 0px;height:48px;width: auto;"/></a>
                                    </td>
                                </tr>
                            </table>
                        </div>
                        <!--[if (gte mso 9)|(IE)]>
                        </td>
                        </tr>
                        </table>
                        <![endif]-->
                    </td>
                </tr>
                <tr>
                    <td align="center" style="padding: 35px; background-color: #ffffff;" bgcolor="#ffffff">
                        <!--[if (gte mso 9)|(IE)]>
                        <table align="center" border="0" cellspacing="0" cellpadding="0" width="600">
                            <tr>
                                <td align="center" valign="top" width="600">
                        <![endif]-->
                        {{-- email body --}}
                        @yield('body')
                        {{-- end email body --}}
                        <!--[if (gte mso 9)|(IE)]>
                        </td>
                        </tr>
                        </table>
                        <![endif]-->
                    </td>
                </tr>
                <tr>
                    <td align="center" style="background-color: #ffffff;" bgcolor="#ffffff">
                        <img src="{!! asset('assets/corals/images/email/arrow-up.png') !!}" width="46" height="22"
                             style="display: block; border: 0px;"/>
                    </td>
                </tr>
                <tr>
                    <td align="center"
                        style=" padding: 35px; background-color: #1b9ba3; border-bottom: 20px solid #48afb5;"
                        bgcolor="#1b9ba3">
                        <!--[if (gte mso 9)|(IE)]>
                        <table align="center" border="0" cellspacing="0" cellpadding="0" width="600">
                            <tr>
                                <td align="center" valign="top" width="600">
                        <![endif]-->
                        <table align="center" border="0" cellpadding="0" cellspacing="0" width="100%"
                               style="max-width:600px;">
                            <tr>
                                <td align="center">
                                    <table>
                                        <tr>
                                            @foreach(\Settings::get('social_links', []) as $key => $link)
                                                <td style="padding: 0 10px;">
                                                    <a href="{{ $link }}" target="_blank">
                                                        <img src="{!! asset('assets/corals/images/social_icons/48/'.$key.'.png') !!}"
                                                             width="35" height="29"
                                                             style="display: block; border: 0px;"/></a>
                                                </td>
                                            @endforeach
                                        </tr>
                                    </table>
                                </td>
                            </tr>
                        </table>
                        <!--[if (gte mso 9)|(IE)]>
                        </td>
                        </tr>
                        </table>
                        <![endif]-->
                    </td>
                </tr>
                <tr>
                    <td align="center" style="padding: 35px; background-color: #ffffff;" bgcolor="#ffffff">
                        <!--[if (gte mso 9)|(IE)]>
                        <table align="center" border="0" cellspacing="0" cellpadding="0" width="600">
                            <tr>
                                <td align="center" valign="top" width="600">
                        <![endif]-->
                        <table align="center" border="0" cellpadding="0" cellspacing="0" width="100%"
                               style="max-width:600px;">
                            <tr>
                                <td align="center">
                                    <img src="{{ \Settings::get('site_logo') }}" height="48"
                                         style="display: block; border: 0px;height:48px;width: auto;"/>
                                </td>
                            </tr>
                            {{--<tr>--}}
                            {{--<td align="center"--}}
                            {{--style="font-family: Open Sans, Helvetica, Arial, sans-serif; font-size: 14px; font-weight: 400; line-height: 24px; padding: 5px 0 10px 0;">--}}
                            {{--<p style="font-size: 14px; font-weight: 800; line-height: 18px; color: #333333;">--}}
                            {{--675 Massachusetts Avenue<br>--}}
                            {{--Cambridge, MA 02139--}}
                            {{--</p>--}}
                            {{--</td>--}}
                            {{--</tr>--}}
                            {{--<tr>--}}
                            {{--<td align="left"--}}
                            {{--style="font-family: Open Sans, Helvetica, Arial, sans-serif; font-size: 14px; font-weight: 400; line-height: 24px;">--}}
                            {{--<p style="font-size: 14px; font-weight: 400; line-height: 20px; color: #777777;">--}}
                            {{--If you didn't create an account using this email address, please ignore this--}}
                            {{--email or <a href="{{ url('/') }}" target="_blank" style="color: #777777;">unsusbscribe</a>.--}}
                            {{--</p>--}}
                            {{--</td>--}}
                            {{--</tr>--}}
                        </table>
                        <!--[if (gte mso 9)|(IE)]>
                        </td>
                        </tr>
                        </table>
                        <![endif]-->
                    </td>
                </tr>
            </table>
            <!--[if (gte mso 9)|(IE)]>
            </td>
            </tr>
            </table>
            <![endif]-->
        </td>
    </tr>
</table>

</body>
</html>