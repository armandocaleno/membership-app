<!doctype html>
<html lang="es">

<head>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
    <title>Suscripcion por expirar!</title>
    <style>
        @media only screen and (max-width: 620px) {
            table.body h1 {
                font-size: 28px !important;
                margin-bottom: 10px !important;
            }

            table.body p,
            table.body ul,
            table.body ol,
            table.body td,
            table.body span,
            table.body a {
                font-size: 16px !important;
            }

            table.body .wrapper,
            table.body .article {
                padding: 10px !important;
            }

            table.body .content {
                padding: 0 !important;
            }

            table.body .container {
                padding: 0 !important;
                width: 100% !important;
            }

            table.body .main {
                border-left-width: 0 !important;
                border-radius: 0 !important;
                border-right-width: 0 !important;
            }

            table.body .btn table {
                width: 100% !important;
            }

            table.body .btn a {
                width: 100% !important;
            }

            table.body .img-responsive {
                height: auto !important;
                max-width: 100% !important;
                width: auto !important;
            }
        }

        @media all {
            .ExternalClass {
                width: 100%;
            }

            .ExternalClass,
            .ExternalClass p,
            .ExternalClass span,
            .ExternalClass font,
            .ExternalClass td,
            .ExternalClass div {
                line-height: 100%;
            }

            .apple-link a {
                color: inherit !important;
                font-family: inherit !important;
                font-size: inherit !important;
                font-weight: inherit !important;
                line-height: inherit !important;
                text-decoration: none !important;
            }

            #MessageViewBody a {
                color: inherit;
                text-decoration: none;
                font-size: inherit;
                font-family: inherit;
                font-weight: inherit;
                line-height: inherit;
            }

            .link {
                text-decoration: none;
                font-size: 12px;
            }

            .contenedor {
                display: inline-block;
                text-align: center;
                margin-right: 4px;
                margin-left: 4px;
                background-color: goldenrod;
            }

            .centrado {
                top: 50%;
                left: 50%;
                font-size: 20px;
                font-weight: 900;
            }
        }
    </style>
</head>

<body
    style="background-color: #f6f6f6; font-family: sans-serif; -webkit-font-smoothing: antialiased; font-size: 14px; line-height: 1.4; margin: 0; padding: 0; -ms-text-size-adjust: 100%; -webkit-text-size-adjust: 100%;">
    <span class="preheader"
        style="color: transparent; display: none; height: 0; max-height: 0; max-width: 0; opacity: 0; overflow: hidden; mso-hide: all; width: 0;">eMarket</span>
    <table role="presentation" border="0" cellpadding="0" cellspacing="0" class="body"
        style="border-collapse: separate; mso-table-lspace: 0pt; mso-table-rspace: 0pt; background-color: #f6f6f6; width: 100%;"
        width="100%" bgcolor="#f6f6f6">
        <tr>
            <td style="font-family: sans-serif; font-size: 14px; vertical-align: top;" valign="top">&nbsp;</td>
            <td class="container"
                style="font-family: sans-serif; font-size: 14px; vertical-align: top; display: block; max-width: 580px; padding: 10px; width: 580px; margin: 0 auto;"
                width="580" valign="top">
                <div class="content"
                    style="box-sizing: border-box; display: block; margin: 0 auto; max-width: 580px; padding: 10px;">

                    <!-- START CENTERED WHITE CONTAINER -->
                    <table role="presentation" class="main"
                        style="border-collapse: separate; mso-table-lspace: 0pt; mso-table-rspace: 0pt; background: #ffffff; border-radius: 3px; width: 100%;"
                        width="100%">

                        <!-- START MAIN CONTENT AREA -->
                        <tr>
                            <td class="wrapper"
                                style="font-family: sans-serif; font-size: 14px; vertical-align: top; box-sizing: border-box; padding: 20px;"
                                valign="top">
                                <table role="presentation" border="0" cellpadding="0" cellspacing="0"
                                    style="border-collapse: separate; mso-table-lspace: 0pt; mso-table-rspace: 0pt; width: 100%;"
                                    width="100%">
                                    <tr>
                                        <td style="font-family: sans-serif; font-size: 14px; vertical-align: top;"
                                            valign="top">
                                            <p
                                                style="font-family: sans-serif; font-size: 14px; font-weight: normal; margin: 0; margin-bottom: 15px;">
                                                Hola </p>
                                            <h3>{{ $suscription->customer->name }}</h3>
                                            <p>tu suscripción {{ $suscription->number }} está por expirar </p>
                                            @php
                                                $end_date = \Carbon\Carbon::parse($suscription->end_date);
                                            @endphp
                                            <p
                                                style="font-family: sans-serif; font-size: 14px; font-weight: normal; margin: 0; margin-bottom: 15px;">
                                                <strong>eMarket</strong> te comunica que el plan 
                                                <strong>{{ $suscription->plan->name }}</strong> expira el <strong>{{ $end_date->translatedFormat('d \d\e F \d\e\l Y') }}</strong>.
                                                Comunícate con nosotros para renovar y continuar disfrutando de nuestro servicio.
                                            </p>

                                            <table role="presentation" border="0" cellpadding="0" cellspacing="10"
                                                style="border-collapse: separate;">
                                                    <tr>
                                                        <td><strong>Plan:</strong></td>
                                                        <td>{{ $suscription->plan->name }}</td>
                                                    </tr>
                                                    <tr>
                                                        <td><strong>Dispositivos:</strong></td>
                                                        <td>{{ $suscription->plan->devices }}</td>
                                                    </tr>
                                                    <tr>
                                                        <td><strong>Tiempo:</strong></td>
                                                        <td>{{ $suscription->plan->months }} meses</td>
                                                    </tr>
                                                    <tr>
                                                        <td><strong>Total:</strong></td>
                                                        <td>{{ $suscription->plan->price }}</td>
                                                    </tr>
                                                </table>

                                            <p
                                                style="font-family: sans-serif; font-size: 12px; font-weight: normal; margin: 0; margin-bottom: 15px;color: #999999;">
                                                Recuerde que puede solicitar soporte técnico personalizado comunicándose al teléfono: 0999999999 
                                            </p>
                                            <div>
                                                <a href="#"
                                                    target="_blank" rel="noopener noreferrer"
                                                    style="font-size: 14px; font-weight: bold; display:flex; align-items:center; gap:10px">

                                                    <p>eMarket</p>
                                                </a>

                                            </div>
                                            <p
                                                style="font-family: sans-serif; font-size: 14px; font-weight: normal; margin: 0; margin-bottom: 15px;">
                                                Saludos.</p>
                                        </td>
                                    </tr>
                                </table>
                            </td>
                        </tr>

                        <!-- END MAIN CONTENT AREA -->
                    </table>
                    <!-- END CENTERED WHITE CONTAINER -->

                    <!-- START FOOTER -->
                    <div class="footer" style="clear: both; margin-top: 10px; text-align: center; width: 100%;">
                        <table role="presentation" border="0" cellpadding="0" cellspacing="0"
                            style="border-collapse: separate; mso-table-lspace: 0pt; mso-table-rspace: 0pt; width: 100%;"
                            width="100%">
                            <tr>
                                <td class="content-block"
                                    style="font-family: sans-serif; vertical-align: top; padding-bottom: 10px; padding-top: 10px; color: #999999; font-size: 12px; text-align: center;"
                                    valign="top" align="center">
                                    <span class="apple-link"
                                        style="color: #999999; font-size: 12px; text-align: center;">eMarket,
                                        Guayaquil - Ecuador</span>
                                    {{-- <br> Don't like these emails? <a href="http://i.imgur.com/CScmqnj.gif" style="text-decoration: underline; color: #999999; font-size: 12px; text-align: center;">Unsubscribe</a>. --}}
                                </td>
                            </tr>
                            <tr>
                                <td class="content-block powered-by"
                                    style="font-family: sans-serif; vertical-align: top; padding-bottom: 10px; padding-top: 10px; color: #999999; font-size: 12px; text-align: center;"
                                    valign="top" align="center">
                                    Powered by <a href="#"
                                        style="color: #999999; font-size: 12px; text-align: center; text-decoration: none;">eMarket</a>.
                                </td>
                            </tr>
                        </table>
                    </div>
                    <!-- END FOOTER -->

                </div>
            </td>
            <td style="font-family: sans-serif; font-size: 14px; vertical-align: top;" valign="top">&nbsp;</td>
        </tr>
    </table>
</body>

</html>
