<?php
/**
 * Created by PhpStorm.
 * User: Wizston (Olawale Adegboyega)
 * Filename: testy.blade.php
 * Date: 12-Aug-17
 * Time: 4:42 PM
 * Descr:
 */
?>


<!DOCTYPE html>
<html>
<head>
    <title></title>

    <style type="text/css">
        body {
            color: #000033;
            font-family: "verdana", "sans-serif";
            margin: 0px;
            padding-top: 0px;
            font-size: 0.8em;
            height: 100%;
            position: relative;
            padding-bottom: 150px
        }

        .colHead {
            font-weight: bold;
            padding-top: 15px;
        }

        /* Footer
 -----------------------------------------------------------------------*/
        #footer {
            color: #FFFFff;
            border-top: 1px solid #000033;
        }

        #copyright {
            padding: 5px;
            font-size: 0.6em;
            background-color: #114C8D;
        }

        #footer_spacer_row {
            border-spacing: 0;
            width: 100%;
        }
        .footertwo {
            position: absolute;
            left: 0px; bottom: 0; right: 0px; height: 150px;
            background: url("{{ public_path() . '/pdf/pdf-footer.jpg' }}");
        }

        #footer_spacer_row td {
            padding: 0px;
            border-bottom: 1px solid #000033;
            background-color: #F7CF07;
            height: 2px;
            font-size: 2px;
            line-height: 2px;
        }

        .logo-text {
            display: inline-block;
            margin-left: 64px;
            color: #f3f3f3;
            font-size: 21px;
        }

        .iban-header {
            color: #f3f3f3;
            margin: 20px 0 0 12px;
            font-size: 11px;
        }
        .footertwo p:first-child
        {
            color: #F3F3F3;
            width: 80%;
            margin: 90px auto 0 auto;
        }
        .footertwo p span
        {
            color: #F3F3F3 !important;
        }

        h1,h2,h3,h4,h5,h6 {
            color: #1d2590;
        }

        .content {
            width: 80%;
            margin: 0 10%;
        }
    </style>
</head>
<body>

<div style="background: url('{{ public_path() .'/pdf/pdf-header.jpg' }}'); min-height: 225px;background-size: contain;background-repeat: no-repeat;">

    <div class="logo-text">
        <span class="logo-top-text">Uw</span> <br>
        <span class="logo-top-text">Voordeel</span>
    </div>
    <br>
    <table class="iban-header">
        <tr>
            <td>IBAN</td>
            <td>NL91 ABNA 0417 1643 00</td>
        </tr>
        <tr>
            <td>BTW</td>
            <td>855755659B01</td>
        </tr>
        <tr>
            <td>KVK</td>
            <td>0417 1643 00</td>
        </tr>
    </table>

</div>



<div class="content">
    @yield("content")
</div>

<div class="footertwo">
    {!! isset($contentBlock[46]) ? $contentBlock[46] : '' !!}
</div>
</body>
</html>