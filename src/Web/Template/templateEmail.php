<meta name="x-apple-disable-message-reformatting">
<title></title>
<!--[if mso]>
<noscript>
    <xml>
        <o:OfficeDocumentSettings>
            <o:PixelsPerInch>96</o:PixelsPerInch>
        </o:OfficeDocumentSettings>
    </xml>
</noscript>
<![endif]-->
<style>
    table, td, div, h1, p {
        font-family: Arial, sans-serif;
    }
</style>
</head>
<body style="margin:0;padding:0;">
<table role="presentation" style="width:100%;border-collapse:collapse;border:0;border-spacing:0;background:#ffffff;">
    <tr>
        <td align="center" style="padding:0;">
            <table role="presentation"
                   style="width:602px;border-collapse:collapse;border:1px solid #cccccc;border-spacing:0;text-align:left;">
                <tr>
                    <td align="center" style="background:#70bbd9;">
                        <img src="https://uploaddeimagens.com.br/images/004/663/941/original/bannerComprimido.png"
                             alt="" width="900"

                             style="height:auto;display:block;"/>
                    </td>
                </tr>
                <tr>
                    <td style="padding:36px 30px 42px 30px; background-color: #334155">
                        <table role="presentation"
                               style="width:100%;border-collapse:collapse;border:0;border-spacing:0;">
                            <tr>
                                <td style="padding:0 0 36px 0;color:#cccccc;">
                                    <h1 style="font-size:24px;margin:0 0 20px 0;font-family:Arial,sans-serif;">
                                        <?= $tituloTemplate ?></h1>
                                    <p style="margin:0 0 12px 0;font-size:16px;line-height:24px;font-family:Arial,sans-serif;">
                                        <?= $contentTemplate ?></p>
                                    <p style="margin:0;font-size:16px;line-height:24px;font-family:Arial,sans-serif;">
                                        <?php
                                        if ($linkEnableTemplate) { ?>
                                            <a href="<?= $linkTemplate ?>"
                                               style="color:#cccccc;text-decoration:underline;">
                                                Clique aqui
                                            </a>
                                            <?php
                                        } ?>

                                    </p>
                                </td>
                            </tr>
                        </table>
                    </td>
                </tr>
                <tr>
                    <td style="padding:30px;background:#1F2937;">
                        <table role="presentation"
                               style="width:100%;border-collapse:collapse;border:0;border-spacing:0;font-size:9px;font-family:Arial,sans-serif;">
                            <tr>
                                <td style="padding:0;width:50%; " align="center ">
                                    <p style="width: 100%;color: #cccccc;text-align: center; font-size: .8rem"> &copy;2023
                                        RCode All
                                        rights reserved.
                                    </p>
                                </td>

                            </tr>
                        </table>
                    </td>
                </tr>
            </table>
        </td>
    </tr>
</table>
</body>
</html>
