@extends('template.theme')

@inject('preference', 'App\Models\Preference')
@inject('companyHelper', 'App\Helpers\CompanyHelper')

@section('scripts')
    @include('admin.template.editor')
    @include('admin.template.remove_alert')

    <script type="text/javascript">
        $(document).ready(function () {
            closeBrowser();

            var group = $('ol.serialization').sortable({
                group: 'serialization',
                onDrop: function ($item, container, _super) {
                    var data = group.sortable("serialize").get();
                    var jsonString = JSON.stringify(data, null, ' ');

                    $('#serializeOutput').val(jsonString);
                    _super($item, container);
                }
            });
        });
    </script>

    @if (Request::has('step'))
        <script type="text/javascript">
            $(document).ready(function () {
                var $privacy = $('#privacy-terms'),
                    $privacyWrapper = $('#privacy-wrapper'),
                    $agree = $('#agree'),
                    height = $privacy.height(),
                    top = $privacy.offset().top,
                    privacyWrapperHeight = $privacyWrapper.outerHeight();

                $privacy.on('scroll', function () {
                    $('#avOn').show();
                });

                $('#signature').jSignature();

                $('#signature').bind('change', function (e) {
                    $('#signatureInput').val($(this).jSignature('getData', 'image'));
                    $('#resetSignature').removeClass('disabled');
                });

                $('#resetSignature').on('click', function (e) {
                    $('#signature').jSignature('reset');
                });
            });
        </script>
    @endif
@stop

@section('content')
    <div class="content">
        @if($data != '')
            @include('admin.template.breadcrumb')

            @if (Request::has('step'))
                <div class="ui three mini steps">
                    <a href="{{ url('admin/companies/update/'.$data->id.'/'.$data->slug.'?step=1') }}"
                       class="link active step">
                        <i class="building icon"></i>
                        <div class="content">
                            <div class="title">Bedrijf</div>
                        </div>
                    </a>

                    <a href="{{ url('admin/reservations/create/'.$data->slug.'?step=2') }}" class="step">
                        <i class="calendar icon"></i>
                        <div class="content">
                            <div class="title">Reserveringen</div>
                        </div>
                    </a>

                    <a href="{{ url('faq/3/restaurateurs?step=3&slug='.$data->slug) }}" class="step">
                        <i class="question mark icon"></i>
                        <div class="content">
                            <div class="title">Veelgestelde Vragen</div>
                        </div>
                    </a>
                </div><br/><br/>
            @endif

            <?php echo Form::open(array('url' => 'admin/' . $slugController . '/update/' . $data->id . '/' . $data->slug . (Request::has('step') ? '?step=1' : ''), 'method' => 'post', 'class' => 'ui form', 'files' => true)) ?>
            <?php echo Form::hidden('serialize', '', array('id' => 'serializeOutput')); ?>
            <div class="left section">
                @if ($userAdmin && !Request::has('step'))
                    <div class="field">
                        <div class="ui checkbox">
                            <?php echo Form::checkbox('no_show', 1, $data->no_show) ?>
                            <label>Niet tonen op homepagina en zoeken</label>
                        </div>
                    </div>

                    <div class="field">
                        <label>Kliks</label>
                        <?php echo Form::text('clicks', $data->clicks) ?>
                    </div>
                @endif

                <div class="field">
                    <label>Naam</label>
                    <?php echo Form::text('name', $data->name) ?>
                </div>

                <div class="ui top attached tabular menu">
                    <a class="active item" data-tab="first">Over ons</a>

                    @if ($userAdmin)
                        <a class="item" data-tab="second">Menu</a>
                        <a class="item" data-tab="third">Korting</a>
                    @endif
                </div>

                <div class="ui bottom attached active tab segment" data-tab="first">


                    <div class="field">
                        <label>Korte omschrijving (meta omschrijving) (Max 255 tekens)</label>
                        <?php echo Form::textarea('description', $data->description, array('rows' => '2')); ?>
                    </div>

                    <div class="field">
                        <label>Over ons</label>
                        <?php echo Form::textarea('about_us', $data->about_us, array('class' => 'editor')) ?>
                    </div>
                </div>

                <div class="ui bottom attached tab segment" data-tab="second">
                    <div class="field">
                        <label>Menu</label>
                        <?php echo Form::textarea('menu', $data->menu, array('class' => 'editor')) ?>
                    </div>
                </div>

                <div class="ui bottom attached tab segment" data-tab="third">
                    <div class="field">
                        <label>Korting opmerking</label>
                        <?php echo Form::textarea('discount_comment', $data->discount_comment, array('class' => 'editor')); ?>
                    </div>
                </div>

                <?php
                $owner = Sentinel::getUserRepository()->findById($data->user_id);
                $waiter = Sentinel::getUserRepository()->findById($data->waiter_user_id);
                $caller = Sentinel::getUserRepository()->findById($data->caller_id);
                ?>

                @if($userAdmin && !Request::has('step'))
                    <br>
                    <div class="three fields">
                        <div class="field">
                            <label>Eigenaar</label>
                            Kies een gebruiker uit die eigenaar is van dit bedrijf.<br><br>

                            <div id="companiesOwnersSearch " class="ui search custom_class">
                                <div class="ui icon fluid input ">
                                    <input class="prompt" type="text"
                                           value="<?php echo(isset($owner) ? $owner->name : ''); ?>"
                                           placeholder="Typ een naam in..">
                                    <i class="search icon"></i>
                                </div>

                                <div class="results"></div>
                            </div>

                            <input type="hidden" name="owner" value="<?php echo $data->user_id; ?>">
                        </div>

                        <div class="field">
                            <label>Bediening</label>
                            Kies een gebruiker uit die de bediening is van dit bedrijf.<br/><br/>

                            <div id="companiesWaitersSearch" class="ui search custom_class">
                                <div class="ui icon fluid input">
                                    <input class="prompt" type="text"
                                           value="<?php echo(isset($waiter) ? $waiter->name : ''); ?>"
                                           placeholder="Typ een naam in..">
                                    <i class="search icon"></i>
                                </div>

                                <div class="results"></div>
                            </div>

                            <input type="hidden" name="waiter" value="<?php echo $data->waiter_user_id; ?>">
                        </div>

                        <div class="field">
                            <label>Beller</label>
                            Kies een gebruiker uit die de beller is van dit bedrijf.<br/><br/>

                            <div id="companiesCallerSearch" class="ui search custom_class">
                                <div class="ui icon fluid input">
                                    <input class="prompt" type="text"
                                           value="<?php echo(isset($caller) ? $caller->name : ''); ?>"
                                           placeholder="Typ een naam in..">
                                    <i class="search icon"></i>
                                </div>

                                <div class="results"></div>
                            </div>

                            <input type="hidden" name="caller" value="<?php echo $data->caller_id; ?>">
                        </div>
                    </div>
                @endif

                <h4>Contactgegevens</h4>
                <div class="three fields">
                    <div class="field">
                        <label>Adres</label>
                        <?php echo Form::text('address', $data->address) ?>
                    </div>

                    <div class="field">
                        <label>Postcode</label>
                        <?php echo Form::text('zipcode', $data->zipcode) ?>
                    </div>

                    <div class="field">
                        <label>Woonplaats</label>
                        <?php echo Form::text('city', $data->city) ?>
                    </div>
                </div>

                <div class="three fields">
                    <div class="field">
                        <label>E-mailadres (Reserveren)</label>
                        <?php echo Form::text('email', $data->email) ?>
                    </div>

                    <div class="field">
                        <label>Telefoonnummer (Reserveren)</label>
                        <?php echo Form::text('phone', $data->phone) ?>
                    </div>

                    <div class="field">
                        <label>Website</label>
                        <?php echo Form::text('website', $data->website) ?>
                    </div>
                </div>

                <div class="three fields">
                    <div class="field">
                        <label>Facebook pagina (URL)</label>
                        <?php echo Form::text('facebook', trim($data->facebook) != '' ? $data->facebook : 'https://www.facebook.com/uwvoordeelpas'); ?>
                    </div>
                </div>

                <h4>Administratie</h4>
                <div class="three fields">
                    <div class="field">
                        <label>Contactpersoon</label>
                        <?php echo Form::text('contact_name', $data->contact_name) ?>
                    </div>

                    <div class="field">
                        <label>Telefoon (contactpersoon)</label>
                        <?php echo Form::text('contact_phone', $data->contact_phone) ?>
                    </div>

                    <div class="field">
                        <label>Emailadres (contactpersoon)</label>
                        <?php echo Form::text('contact_email', $data->contact_email) ?>
                    </div>
                </div>

                <div class="three fields">
                    <div class="field">
                        <label>Functie</label>
                        <?php echo Form::text('contact_role', $data->contact_role) ?>
                    </div>

                    <div class="field {{ Request::has('step') || $admin ? '' : 'disabled' }}">
                        <label>KVK</label>
                        <?php echo Form::text('kvk', $data->kvk) ?>
                    </div>

                    <div class="field {{ Request::has('step') || $admin ? '' : 'disabled' }}">
                        <label>BTW</label>
                        <?php echo Form::text('btw', $data->btw) ?>
                    </div>
                </div>

                <div class="three fields">
                    <div class="field {{ Request::has('step')  || $admin ? '' : 'disabled' }}">
                        <label>IBAN Nummer</label>
                        <?php echo Form::text('financial_iban', $data->financial_iban) ?>
                    </div>

                    <div class="field {{ Request::has('step') || $admin ? '' : 'disabled' }}">
                        <label>IBAN tnv</label>
                        <?php echo Form::text('financial_iban_tnv', $data->financial_iban_tnv) ?>
                    </div>

                    <div class="field">
                        <label>E-mailadres (administratie)</label>
                        <?php echo Form::text('financial_email', $data->financial_email) ?>
                    </div>
                </div>

                <h4>Voorkeuren</h4>

                <div class="two fields">
                    <div class="field">
                        <label>Voorkeuren</label>
                        <?php
                        $preferences = array();
                        $preferences[''] = 'Voorkeuren';

                        foreach ($preference->where('category_id', 1)->get() as $prefData) {
                            $preferences[str_slug($prefData->name)] = $prefData->name;
                        }

                        echo Form::select('preferences[]', $preferences, json_decode($data->preferences), array('multiple' => true, 'class' => 'ui normal fluid search dropdown'));
                        ?>
                    </div>

                    <div class="field">
                        <label>Duurzaamheid</label>
                        <?php
                        $sustainability = array();
                        $sustainability[''] = 'Duurzaamheid';

                        foreach ($preference->where('category_id', 8)->get() as $prefData) {
                            $sustainability[str_slug($prefData->name)] = $prefData->name;
                        }

                        echo Form::select('sustainability[]', $sustainability, json_decode($data->sustainability), array('multiple' => true, 'class' => 'ui normal fluid search dropdown'));
                        ?>
                    </div>
                </div>

                <div class="two fields">
                    <div class="field">
                        <label>Keuken</label>
                        <?php
                        $kitchens = array();
                        $kitchens[''] = 'Keuken';

                        foreach ($preference->where('category_id', 2)->get() as $prefData) {
                            $kitchens[str_slug($prefData->name)] = $prefData->name;
                        }

                        echo Form::select('kitchens[]', $kitchens, json_decode($data->kitchens), array('multiple' => true, 'class' => 'ui normal fluid search dropdown'));
                        ?>
                    </div>

                    <div class="field">
                        <label>Allergie&euml;n</label>
                        <?php
                        $allergies = array();
                        $allergies[''] = 'Allergie&euml;n';

                        foreach ($preference->where('category_id', 3)->get() as $prefData) {
                            $allergies[str_slug($prefData->name)] = $prefData->name;
                        }

                        echo Form::select('allergies[]', $allergies, json_decode($data->allergies), array('multiple' => true, 'class' => 'ui normal fluid search dropdown'));
                        ?>
                    </div>
                </div>

                <div class="two fields">
                    <div class="field">
                        <label>Faciliteiten</label>
                        <?php
                        $facilities = array();
                        $facilities[''] = 'Faciliteiten';

                        foreach ($preference->where('category_id', 7)->get() as $prefData) {
                            $facilities[str_slug($prefData->name)] = $prefData->name;
                        }

                        echo Form::select('facilities[]', $facilities, json_decode($data->facilities), array('multiple' => true, 'class' => 'ui normal fluid search dropdown'));
                        ?>
                    </div>

                    <div class="field">
                        <label>(klant van Wifi Online)</label>
                        <?php
                        $kids = array();
                        $kids[''] = 'Wi-Fi';

                        foreach ($preference->where('category_id', 6)->get() as $prefData) {
                            $kids[str_slug($prefData->name)] = $prefData->name;
                        }

                        echo Form::select('kids[]', $kids, json_decode($data->kids), array('multiple' => true, 'class' => 'ui normal fluid search dropdown'));
                        ?>
                    </div>
                </div>

                <div class="two fields">
                    @if ($admin)
                        <div class="field">
                            <label>Korting</label>
                            <?php
                            $discount = array();
                            $discount['NULL'] = 'Geen korting';

                            foreach ($preference->where('category_id', 5)->get() as $prefData) {
                                $discount[rawurldecode($prefData->name)] = $prefData->name;
                            }

                            echo Form::select('discount[]', $discount, is_array(json_decode($data->discount)) != '' ? json_decode($data->discount) : 'NULL', array('multiple' => true, 'class' => 'ui normal fluid search dropdown'));
                            ?>
                        </div>
                    @endif

                    <div class="field">
                        <label>Soort</label>
                        <?php
                        $price = array();
                        $price[''] = 'Soort';

                        foreach ($preference->where('category_id', 4)->get() as $prefData) {
                            $price[str_slug($prefData->name)] = $prefData->name;
                        }

                        echo Form::select('price[]', $price, json_decode($data->price), array('multiple' => true, 'class' => 'ui normal search dropdown'));
                        ?>
                    </div>
                </div>

                <div class="two fields">
                    <div class="field">
                        <label>Regio</label>
                        <select name="regio[]" multiple="" class="ui normal search multiple dropdown">
                            <option value="">Regio</option>
                            <?php
                            $regions = array();
                            if (!is_null(json_decode($data->regio))) {
                                $regions = json_decode($data->regio);
                            }
                            foreach ($preference->where('category_id', 9)->get() as $prefData) {
                                $selected = in_array($prefData->id, $regions) ? "selected" : "";
                                echo "<option value='" . $prefData->id . "' $selected>" . $prefData->name . "</option>";
                            }
                            ?>
                        </select>
                    </div>

                    <div class="field">
                        <label>Kortingsdagen</label>
                        <?php
                        $discountDaysArray = array(
                            'multiple' => true,
                            'id' => 'discountDays',
                            'class' => 'ui normal search dropdown ' . (count(json_decode($data->discount)) == 0 ? 'disabled' : '')
                        );

                        if (count(json_decode($data->discount)) == 0) {
                            $discountDaysArray['disabled'] = 'disabled';
                        }

                        echo Form::select('days[]', Config::get('preferences.days'), json_decode($data->days), $discountDaysArray);
                        ?>
                    </div>

                </div>

                @if(Request::has('step') || $admin)
                    <div class="field">
                        <label>Prijs per persoon</label>
                        <?php echo Form::text('price_per_guest', $data->price_per_guest) ?>
                    </div>
                @endif

                @if (Request::has('step'))
                    <div id="privacy-terms">
                        <div id="privacy-wrapper">
                            <h2>Algemene voorwaarden</h2>
                            {!! isset($contentBlock[43]) ? $contentBlock[43] : '' !!}
                        </div>
                    </div><br/>

                    <div class="field">
                        <div class="ui checkbox">
                            <?php echo Form::checkbox('av', 1); ?>
                            <label>Ik ga akkoord met de voorwaarden op <strong>{{ date('d-m-Y H:i') }}</strong> met IP
                                adres <strong>{{ Request::getClientIp() }}</strong></label>
                        </div>
                    </div>

                    @if ($data->signature_url == NULL)
                        <h4>Vul hieronder uw handtekening in:</h4>
                        <div id="signature"></div>

                        <button id="resetSignature" class="ui small button disabled">Verwijder handtekening</button>
                        <br/><br/>
                    @else
                        <h4>Uw handtekening</h4>
                        <img src="data:{{ $data->signature_url }}"/><br/>
                    @endif
                    <?php echo Form::hidden('signature', $data->signature_url, array('id' => 'signatureInput')); ?>
                @endif

                @if (Request::has('step'))
                    <button class="ui tiny button" type="submit">
                        <i class="checkmark green icon"></i> Ik ga akkoord
                    </button>
                @else
                    <button class="ui tiny button" type="submit">
                        <i class="pencil icon"></i> Wijzigen
                    </button>

                    <a href="{{ url('restaurant/'.$data->slug) }}" target="_blank" class="ui tiny button"><i
                                class="file icon"></i> Voorbeeld</a>
                @endif
            </div>

            <div class="right section" style="padding-left: 20px;">
                <div class="field">
                    <div class="field">
                        <label>Logo</label>
                        <?php echo Form::file('logo'); ?><br/><br/>
                        <div class="ui one cards">
                            @foreach($logoItem as $id => $images)
                                <div class="card">
                                    <div class="image">
                                        <img src="{{ url($images->getUrl()) }}">
                                    </div>
                                    <div class="extra">
                                        <a href="{{ url('admin/'.$slugController.'/crop/image/'.$data->slug.'/'.$id.'?type=logo') }}">
                                            <i class="crop icon"></i> Uitknippen
                                        </a>

                                        <a href="{{ url('public/'.$images->getUrl()) }}" target="_blank">
                                            <i class="expand icon"></i> Voorbeeld
                                        </a>

                                        <a href="{{ url('admin/'.$slugController.'/delete/image/'.$data->slug.'/'.$id.'?type=logo') }}">
                                            <i class="trash icon"></i>
                                        </a>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                    <div class="ui divider"></div>

                    @if($admin && !Request::has('step'))
                        <div class="field">
                            <label>Documenten</label>
                            <?php echo Form::file('pdf[]', array('multiple' => true)); ?><br/><br/>

                            <div class="ui one cards">
                                @foreach($documentItems as $id => $doc)
                                    <div class="card">
                                        <div class="content">
                                            <a href="{{ url('public/'.$doc->getUrl()) }}" target="_blank" class="meta">
                                                {{ $doc->file_name }}
                                            </a>
                                        </div>
                                        <div class="extra">
                                            <a href="{{ url('public/'.$doc->getUrl()) }}" target="_blank"><i
                                                        class="download icon"></i></a>
                                            <a href="{{ url('admin/'.$slugController.'/delete/image/'.$data->slug.'/'.$id.'?type=documents') }}"><i
                                                        class="trash icon"></i></a>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        </div>

                        <div class="ui divider"></div>
                    @endif

                    <div class="field">
                        <label>Afbeeldingen (maximaal 6)</label>

                        <i class="info icon"></i> Tip: Om de volgorde te bepalen kunt u de afbeeldingen sleepen in de
                        juiste volgorde, door het witte vlak naar beneden of boven te slepen<br><br>
                        <?php echo Form::file('images[]', array('multiple' => true, 'class' => 'multi with-preview')); ?>
                        <br/><br/>

                        <ol class="serialization">
                            @foreach($media as $id => $images)
                                <li class="ui segment" data-id="{{ $images->id }}">
                                    <img src="{{ url($images->getUrl('thumb')) }}" class="ui image"><br>
                                <!-- <a href="{{ url('admin/'.$slugController.'/crop/image/'.$data->slug.'/'.$id.'?type=images') }}">
							     		<i class="crop icon"></i> Uitknippen
							     	</a> -->

                                    <a href="{{ url('public/'.$images->getUrl()) }}" target="_blank">
                                        <i class="expand icon"></i> Voorbeeld
                                    </a>

                                    <a href="{{ url('admin/'.$slugController.'/delete/image/'.$data->slug.'/'.$id.'?type=images') }}"><i
                                                class="trash icon"></i></a>

                                </li>
                            @endforeach
                        </ol>
                    </div>
                </div>
            </div>
            <?php echo Form::close(); ?>

            <div class="clear"></div>
        @else
            <div class="ui error message">Het formulier met record ID <strong>{{ Request::segment(4) }}</strong> is niet
                gevonden.
            </div>
        @endif
    </div>
    <div class="clear"></div>
@stop
