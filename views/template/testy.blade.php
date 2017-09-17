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

    <style>
        @page{
            margin: 0;
            /*margin: 300px 10%;*/
            padding-top: 30%;
        }
    </style>
    <style type="text/css">
        body {
            color: #000033;
            font-family: "verdana", "sans-serif";
            margin: 0px;
            padding-top: 0px;
            font-size: 0.8em;
            height: 100%;
            /*position: relative;*/
            padding-bottom: 150px;

        }



        #footer_spacer_row td {
            padding: 0px;
            border-bottom: 1px solid #000033;
            background-color: #F7CF07;
            height: 2px;
            font-size: 2px;
            line-height: 2px;
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

        body{
            width: 80%;
            margin: 300px 10%;
        }

        .header-lead {
            max-width: 100%;
            width: 100%;
            top: 0;
            left: 0;
            position: fixed;
            z-index: -1000;
        }
        footer, .footer-last {
            max-width: 100%;
            width: 100%;
            position: fixed;
            left: 0;
            bottom: 0;
            z-index: -1000;
        }

        .content-block {
            page-break-inside: avoid;
        }
    </style>
</head>
<body>

<img src="{{ url('/pdf/pdf-header.jpg') }}" alt="header" class="header-lead">

<div class="content">
    @yield("content")
</div>
<footer>
    <img src="{{ url('/pdf/pdf-footer.jpg') }}" alt="footer-img0" class="footer-last">
</footer>
</body>
</html>