@extends('template.theme')

@section('scripts')
    @include('admin.template.remove_alert')

    <script type="text/javascript">
        $(document).ready(function() {
            var checkArray = [];

            $('.ui.child.checkbox').checkbox({
                onChecked: function() {
                    checkArray.push($(this).val());
                    var jsonArray = JSON.parse(JSON.stringify(checkArray));

                    $('#idArray').val(jsonArray);
                    $('#removeButton').removeClass('disabled');
                    $('#noShow, #yesShow').removeClass('disabled');
                },
                onUnchecked: function() {
                    for (var i = checkArray.length; i--;) {
                        if (checkArray[i] === $(this).val()) {
                            checkArray.splice(i, 1);
                            break; // Remove this line to remove all occurrences
                        }
                    }

                    var jsonArray = JSON.parse(JSON.stringify(checkArray));
                    $('#idArray').val(jsonArray);
                }
            });

        });
    </script>
@stop

@section('content')
<input type="hidden" id="idArray" name="idArr">

<div id="transfer" style="display: none;">
    Verplaats de geselecteerde affiliates naar een andere rubriek. Zodra deze zijn verplaats, dan zijn de affiliates niet meer beschikbaar in de oude rubruiek.<br><br>

    {{ csrf_field() }}

    <div class="ui selection transfer multiple normal dropdown search optgroup fluid">
        <input type="text" id="categorySelect" name="categorySelect">
        <span class="text">Kies een rubriek of subrubriek</span>
        <i class="dropdown icon"></i>

        <div class="menu">
            @foreach($categories as $category)
                <div class="item" data-value="{{ $category['id'] }}">
                    <strong>{{ $category['name'] }}</strong>
                </div>
                    
                @foreach($category['subcategories'] as $subcategory)
                    @if ($subcategory['name'] != NULL)
                        <div class="item" data-value="{{ $subcategory['id'] }}">
                            {{ $subcategory['name'] }}
                        </div>
                    @endif
                @endforeach
            @endforeach
        </div>
    </div>

    <button type="submit" name="submitTransfer" class="ui disabled button">Verplaatsen</button>
</div>

<div class="content">
    @include('admin.template.breadcrumb')
    <div class="buttonToolbar">  
        <div class="ui grid">
            <div class="row">
                <div class="left floated sixteen wide mobile eleven wide computer column">
                    <a href="{{ url('admin/'.$slugController.'/create') }}" class="ui icon blue button"><i class="plus icon"></i> Nieuw</a>
                            
                    <button id="yesShow" type="submit" name="action" value="show" class="ui disabled icon button">
                        <i class="check mark green icon"></i>  Show
                    </button>   

                    <button id="noShow" type="submit" name="action" value="noshow" class="ui disabled icon button">
                        <i class="red remove icon"></i>  No Show
                    </button>   

                    <button id="removeButton" type="submit" name="action" value="remove" class="ui disabled icon button">
                        <i class="trash icon"></i> Verwijderen
                    </button>   

                    <button id="transferButton" type="submit" name="action" data-value="" value="transfer" class="ui icon button">
                        <i class="arrow left icon"></i> Verplaatsen
                    </button>   
                </div>

                <div class="right floated sixteen wide mobile five wide computer column">
                    <div class="ui grid">
                        <div class="two column row">
                            <div class="column">
                            @include('admin.template.limit')
                            </div>

                            <div class="column">
                            @include('admin.template.search.form')
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="left floated sixteen wide mobile eleven wide computer column">
                    <a href="{{ url('admin/categories') }}" class="ui icon button"> <i class="list icon"></i> Hoofdrubrieken</a>
                    <a href="{{ url('admin/subcategories') }}" class="ui icon button"><i class="ordered list icon"></i> Subrubrieken </a>
                </div>

                
            </div>


            <!-- Changed by Ocean -->

            <div class="sixteen wide mobile four wide computer column">
                <div class="ui normal icon selection fluid dropdown">
                    <input type="hidden" name="filters" value="{{ Request::input('network') }}">
                    <i class="filter icon"></i>
                                          
                    <span class="text">Netwerk</span>

                    <i class="dropdown icon"></i>

                    <div class="menu">
                        <div class="header">
                            <i class="tags icon"></i>
                            Netwerk
                        </div>

                        <div class="scrolling menu">
                            <a class="item" 
                                href="{{ url('admin/'.$slugController.'?'.http_build_query(array('network' => 'affilinet', 'limit' => Request::input('limit')))) }}" 
                                data-value="affilinet">
                                Affilinet
                            </a>

                            <a class="item" 
                               href="{{ url('admin/'.$slugController.'?'.http_build_query(array('network' => 'familyblend', 'limit' => Request::input('limit')))) }}" 
                               data-value="familyblend">
                               FamilyBlend
                            </a>

                            <a class="item" 
                               href="{{ url('admin/'.$slugController.'?'.http_build_query(array('network' => 'daisycon', 'limit' => Request::input('limit')))) }}" 
                               data-value="daisycon">
                               Daisycon
                            </a>

                            <a class="item" 
                               href="{{ url('admin/'.$slugController.'?'.http_build_query(array('network' => 'tradedoubler', 'limit' => Request::input('limit')))) }}" 
                               data-value="tradedoubler">
                               Tradedoubler
                            </a>

                            <a class="item" 
                               href="{{ url('admin/'.$slugController.'?'.http_build_query(array('network' => 'tradetracker', 'limit' => Request::input('limit')))) }}" 
                               data-value="tradetracker">
                               Tradetracker
                            </a>

                            <a class="item" 
                               href="{{ url('admin/'.$slugController.'?'.http_build_query(array('network' => 'zanox', 'limit' => Request::input('limit')))) }}" 
                               data-value="zanox">
                               Zanox
                            </a>
                        </div>
                    </div>
                </div>
            </div>
            <div class="sixteen wide mobile twelve wide computer column">
            <form method="get" action="{{ url('admin/'.$slugController.'?'.http_build_query($queryString)) }}">
                <div class="ui input">
                    <?php
                    echo Form::text(
                            'from', old('from'), array(
                        'class' => 'datepicker_no_min_date',
                        'placeholder' => 'Datum van',
                        'style' => 'width: 300px;'
                            )
                    );
                    ?>
                </div>

                <div class="ui input">
                    <?php
                    echo Form::text(
                            'to', old('to'), array(
                        'class' => 'datepicker_no_min_date',
                        'placeholder' => 'Datum tot',
                        'style' => 'width: 300px;'
                            )
                    );
                    ?>
                </div>
                <button class="ui button" type="submit"><i class="search icon"></i></button>
            </form>
        </div>


        </div>
    </div>
                     
    <?php echo Form::open(array('id' => 'formList', 'url' => 'admin/'.$slugController.'/delete', 'method' => 'post')) ?>
    <input type="hidden" id="actionMan" name="action">
    
    <table class="ui very basic collapsing sortable celled table list" style="width: 100%;">
        <thead>
            <tr>
            	<th data-slug="disabled" class="disabledone wide">
            		<div class="ui master checkbox">
                        <input type="checkbox">
                        <label></label>
                    </div>
    			</th>
                <th data-slug="name">Naam</th>
                <th data-slug="compensations">Max spaartegoed</th>
                <th data-slug="clicks">Kliks</th>
                <th data-slug="clicks">winst</th>
                <th data-slug="updated_at" class="two wide">Gewijzigd op</th>
                <th data-slug="catName" class="two wide">Rubriek</th>
                <th data-slug="disabled" class="disabled"></th>
            </tr>
        </thead>
        <tbody class="list search">
            @if(count($data) >= 1)
                @include('admin/'.$slugController.'.list')
            @else
                <tr>
                    <td colspan="2"><div class="ui error message">Er is geen data gevonden.</div></td>
                </tr>
            @endif
            </tbody>
        </tbody>
    </table>
    <?php echo Form::close(); ?>
    
    {!! with(new \App\Presenter\Pagination($data->appends($paginationQueryString)))->render() !!}
</div>
<div class="clear"></div>
@stop