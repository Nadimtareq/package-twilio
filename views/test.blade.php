<?php
/**
 * Created by PhpStorm.
 * User: Wizston (Olawale Adegboyega)
 * Filename: test.blade.php
 * Date: 12-Aug-17
 * Time: 4:35 PM
 * Descr:
 */
?>
@extends("template.testy")

@section("content")


    <h1 style="color: #0D47A1;text-align: center;">Overeenkomst</h1>

    <table style="width: 100%;">
        <tbody>
        <tr>
            <td style="width: 500px;">
                Lorem ipsum dolor sit amet, consectetur adipisicing elit. A alias distinctio iste neque nihil officia repellat sit soluta temporibus? Adipisci aut cumque dolorum, nostrum quae quam quis suscipit tempora voluptatibus?
            </td>
        </tr>
        </tbody>
    </table>




    <table>
    <tr>
        <td></td>
    </tr>
    <tr>
        <td colspan="2" class="colHead">Bankgegevens t.b.v. automatisch incasso:</td>
    </tr>
    <tr>
        <td style="width: 300px;">IBAN:</td>
        <td style="border-bottom: 1px dotted #000;">lorem</td>
    </tr>
    <tr>
        <td style="width: 300px;">Ter name van:</td>
        <td style="border-bottom: 1px dotted #000;">lorem</td>
    </tr>
    <tr>
        <td colspan="2" class="colHead">Voor akkoord automatisch incasso:</td>
    </tr>
    <tr>
        <td style="width: 300px;">Datum:</td>
        <td style="border-bottom: 1px dotted #000;">Lorem iss</td>
    </tr>
    <tr>
        <td style="width: 300px;">Bedrijfsnaam:</td>
        <td style="border-bottom: 1px dotted #000;">Lorreem</td>
    </tr>
    <tr>
        <td style="width: 300px;">Naam tekenbevoegd persoon:</td>
        <td style="border-bottom: 1px dotted #000;">Loremmm</td>
    </tr>
    <tr>
        <td style="width: 300px;">Functie:</td>
        <td style="border-bottom: 1px dotted #000;">Lorerem</td>
    </tr>
    <tr>
        <td style="width: 300px;">Handtekening:</td>
        <td>
            @if (true)
                <img  style="width: 200px;" src="data:asasdsas">
            @endif
        </td>
    </tr>
    <tr>
        <td colspan="2" class="colHead">Websitegegevens:</td>
    </tr>
    <tr>
        <td style="width: 300px;">KVK:</td>
        <td style="border-bottom: 1px dotted #000;">kljaa</td>
    </tr>
    <tr>
        <td style="width: 300px;">BTW:</td>
        <td style="border-bottom: 1px dotted #000;">lk ajks afd</td>
    </tr>
    <tr>
        <td style="width: 300px;">Adres:</td>
        <td style="border-bottom: 1px dotted #000;">kjnank askjkas ass</td>
    </tr>
    <tr>
        <td style="width: 300px;">Postcode / plaats:</td>
        <td style="border-bottom: 1px dotted #000;">.kjjkk kjassasad</td>
    </tr>
    <tr>
        <td style="width: 300px;">E-mail reserveringen:</td>
        <td style="border-bottom: 1px dotted #000;">ljksndaskldsam asdas</td>
    </tr>
    <tr>
        <td style="width: 300px;">E-mail administratie:</td>
        <td style="border-bottom: 1px dotted #000;">kjkjl jasd askd</td>
    </tr>
    <tr>
        <td style="width: 300px;">Mobiel telefoonnummer:</td>
        <td style="border-bottom: 1px dotted #000;">kkjjas dfasdkjas</td>
    </tr>
    <tr>
        <td style="width: 300px;">Vast telefoonnummer:</td>
        <td style="border-bottom: 1px dotted #000;">KAKA</td>
    </tr>
</table>


<div style="page-break-before:always">&nbsp;</div>

<h4>Algemene Voorwaarden</h4>
    <div style="padding-top: 30%;">
{!! isset($contentBlock[43]) ? str_replace('<strong>4: Aansprakelijkheid UWvoordeelpas B.V.:</strong>', '<div style="page-break-before:always; padding-top: 35%;">&nbsp;</div><strong>4: Aansprakelijkheid UWvoordeelpas B.V.:</strong>', $contentBlock[43].'<br /><br />') : '' !!}
    </div>

<table>
    <tr>
        <td><span style="border-bottom: 1px dotted #000; padding-right: 20px; font-size: 16px;">POAOOAD</span></td>
        <td><span style="border-bottom: 1px dotted #000; padding-right: 20px; font-size: 16px;">LDAD</span></td>
        <td>
            @if (true)
                <span style="border-bottom: 1px dotted #000; padding-right: 20px; font-size: 16px;"><img style="width: 200px;" src="data:dsd"></span>
            @endif
        </td>
    </tr>
    <tr>
        <td style="width: 250px; font-weight: bold;">Naam contactpersoon:</td>
        <td style="width: 250px; font-weight: bold;">Datum:</td>
        <td style="width: 25px; font-weight: bold;">Handtekening:</td>
    </tr>
</table>
@endsection