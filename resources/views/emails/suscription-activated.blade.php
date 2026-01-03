<!doctype html>
<html>

<head>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
    <title>Suscripcion activada!</title>
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

            #logo{
                max-width: 120px;
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
                                style="font-family: sans-serif; font-size: 14px; vertical-align: top; box-sizing: border-box; padding: 10px 20px;"
                                valign="top">
                                <table role="presentation" border="0" cellpadding="0" cellspacing="0"
                                    style="border-collapse: separate; mso-table-lspace: 0pt; mso-table-rspace: 0pt; width: 100%;"
                                    width="100%">
                                    <tr>
                                        <td style="text-align: center; border-bottom: 1px solid #000; margin-top:-10px">
                                             {{-- Logo --}}
                                            <a href="#" target="_blank" rel="noopener noreferrer">
                                                <img src="{{ $message->embedData(file_get_contents(public_path('images/logo-emarket.png')), 'logo-emarket.png') }}" alt="Logo" id="logo">
                                            </a>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td style="font-family: sans-serif; font-size: 14px; vertical-align: top; padding-top: 8px;"
                                            valign="top">
                                            <p style="font-family: sans-serif; font-size: 14px; font-weight: normal; margin: 0; margin-bottom: 15px;">
                                                Hola, <strong>{{ $suscription->customer->name }}</strong>
                                            </p>
                                            
                                            <p>Reciba un cordial saludo.</p>
                                            @php
                                                $end_date = \Carbon\Carbon::parse($suscription->end_date);
                                            @endphp
                                            <p
                                                style="font-family: sans-serif; font-size: 14px; font-weight: normal; margin: 0; margin-bottom: 15px;">
                                                Gracias por confiar en <a href="www.emarket.com.ec" target="_blank" rel="noopener noreferrer" style="font-family: sans-serif; font-size: 14px;">eMarket</a> y por realizar el pago correspondiente a su plan <strong>{{ $suscription->plan->name }}</strong> 
                                                Valoramos su preferencia y reafirmamos nuestro compromiso de acompañarlo en todo momento para garantizar el correcto funcionamiento, estabilidad y continuidad de su sistema. 
                                            </p>

                                            <p
                                                style="font-family: sans-serif; font-size: 14px; font-weight: normal; margin: 0; margin-bottom: 15px;">
                                                Nos complace informarle que su plan ha sido activado exitosamente, por lo que desde este momento ya cuenta con todos los beneficios incluidos en el servicio.
                                            </p>

                                            <p
                                                style="font-family: sans-serif; font-size: 14px; font-weight: normal; margin: 0; margin-bottom: 15px;">
                                                Información del servicio contratado: 
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
                                                        <td><strong>Duración:</strong></td>
                                                        <td>{{ $suscription->plan->months }} meses</td>
                                                    </tr>
                                                    <tr>
                                                        <td><strong>Vigencia:</strong></td>
                                                        <td><strong>hasta el {{ $end_date->translatedFormat('d \d\e F \d\e\l Y') }}</strong></td>
                                                    </tr>
                                                    <tr>
                                                        <td><strong>Valor total:</strong></td>
                                                        <td>${{ $suscription->plan->price }}</td>
                                                    </tr>
                                                </table>
                                            <p
                                                style="font-family: sans-serif; font-size: 14px; font-weight: normal; margin: 0; margin-bottom: 15px;">
                                                Con este plan activo, usted dispone de los siguientes beneficios: 
                                            </p>

                                            <div>
                                                <ul>
                                                    @foreach ($suscription->plan->products as $product)
                                                        <li>{{ $product }}</li>
                                                    @endforeach
                                                </ul>
                                            </div>

                                            <p style="font-family: sans-serif; font-size: 12px; font-weight: normal; margin: 0; margin-bottom: 5px;color: #999999;">
                                                Es importante informarle que este servicio de soporte es completamente opcional y su contratación no afecta el correcto funcionamiento del sistema previamente instalado.  
                                                En caso de no renovar la suscripción anual, el sistema seguirá operando con normalidad; sin embargo, los beneficios mencionados anteriormente dejarán de estar disponibles.
                                            </p>

                                            <p style="font-family: sans-serif; font-size: 12px; font-weight: normal; margin: 0; margin-bottom: 5px;color: #999999;">
                                                En 
                                                <a href="www.emarket.com.ec" target="_blank" rel="noopener noreferrer" style="font-family: sans-serif; font-size: 12px;">eMarket</a>
                                                 estamos comprometidos con brindarle un servicio confiable, oportuno y de calidad, asegurando que su sistema funcione correctamente y cuente con el respaldo necesario para su operación diaria.
                                            </p>

                                            <p style="font-family: sans-serif; font-size: 12px; font-weight: normal; margin: 0; margin-bottom: 5px;color: #999999;">
                                                Para cualquier consulta o requerimiento de soporte técnico, puede comunicarse con nosotros al 099 560 7147. Estaremos encantados de asistirle.
                                            </p>

                                            <p style="font-family: sans-serif; font-size: 12px; font-weight: normal; margin: 0; margin-bottom: 5px;color: #999999;">
                                                Atentamente,
                                            </p>

                                            <a href="www.emarket.com.ec" target="_blank" rel="noopener noreferrer" style="font-family: sans-serif; font-size: 12px;">eMarket</a>
                                           
                                            
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
                                        style="color: #999999; font-size: 12px; text-align: center;">Equipo eMarket,
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
