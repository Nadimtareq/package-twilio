@extends('template.theme')

@section('content')
<div class="container">
    @include('admin.template.breadcrumb')
    <style>
        .skill-panel-text{
                color: white;
            }
            .skill-title{
                
                font-size: 3em;
                font-weight: bold;
                padding-bottom: 30px;
            }

            .skill-sub{
                font-size: 1.2em;
                font-weight: bold;
            }
            .chart-box{
                background: #fff;
                border: 1px solid #eee;
            }
            li h3, li h4{
                padding: 0;
                margin: 0;
                line-height: 11px;
            }
            thead tr{
                background: #2BBBAD;
            }
            .btn-danger{
                background: #C64333;
            }
            .btn-danger:hover{
                background: #DD4B39;
            }
            .btn-primary{
                background: #0650A2;
            }
            .btn-primary:hover{
                background: #146ABC;
            }

            .skill-panel-box, .chart-blog{
                margin-bottom: 10px;
            }
            .stats-table{
                height: 400px;
            }
    </style>
    <div class="side_menus">
        {{Form::open(array('url' => 'preferences', 'method' => 'post', 'class' => 'ui form'))}}
        <div class="content">
            <div class="row">
                <div class="jsearch col-md-3 col-sm-3 col-xs-12">
                    <div class="ui normal floating basic search selection dropdown">
                        <input name="source" type="hidden" value="{{ Request::input('source') }}">
                            <div class="text">
                                Partij
                            </div>
                            <i class="dropdown icon">
                            </i>
                            <div class="menu">
                                <a class="item" data-value="seatme" href="{{ url('admin/statistics/reservations?'.http_build_query(array_add($queryString, 'source', 'seatme'))) }}">
                                    SeatMe
                                </a>
                                <a class="item" data-value="eetnu" href="{{ url('admin/statistics/reservations?'.http_build_query(array_add($queryString, 'source', 'eetnu'))) }}">
                                    EetNU
                                </a>
                                <a class="item" data-value="couverts" href="{{ url('admin/statistics/reservations?'.http_build_query(array_add($queryString, 'source', 'couverts'))) }}">
                                    Couverts
                                </a>
                                <a class="item" data-value="wifi" href="{{ url('admin/statistics/reservations?'.http_build_query(array_add($queryString, 'source', 'wifi'))) }}">
                                    Wi-Fi
                                </a>
                                @if (count($sources) >= 1)
                            @foreach ($sources as $source)
                                <a class="item" data-value="{{ $source }}" href="{{ url('admin/statistics/reservations?'.http_build_query(array_add($queryString, 'source', str_slug($source)))) }}">
                                    {{ $source }}
                                </a>
                                @endforeach
                        @endif
                            </div>
                        </input>
                    </div>
                </div>
                <div class="jsearch col-md-3 col-sm-3 col-xs-6">
                    <?php echo Form::open(array('method' =>
                    'get')) ?>
                    <div class="ui input">
                        <?php
                    echo Form::text(
                        'from',
                        '',
                        array(
                            'class' =>
                        'datepicker_no_min_date',
                            'placeholder' => 'Startdatum'
                        )
                    );
                    ?>
                    </div>
                </div>
                <div class="jsearch col-md-3 col-sm-3 col-xs-6">
                    <div class="ui input">
                        <?php
                    echo Form::text(
                        'to',
                        '',
                        array(
                            'class' =>
                        'datepicker_no_min_date',
                            'placeholder' => 'Einddatum'
                        )
                    );
                    ?>
                    </div>
                </div>
                <div class="jsearch col-md-2 col-sm-2 col-xs-6">
                    <button class="ui button btn-primary" type="submit">
                        <i class="search icon">
                        </i>
                    </button>
                </div>
            </div>
        </div>
        <?php echo Form::close(); ?>
    </div>
    <div class="skill-blog" style="background: transparent !important;">
        <div class="skill-panel">
            <div class="row">
                <div class="col-md-3 col-xs-12">
                   <a href="{{ url('admin/users') }}"> <div class="skill-panel-box massage">
                        <div class="skill-panel-inner-icon">
                            <i class="fa fa-comments">
                            </i>
                        </div>
                        <div class="skill-panel-text">
                            <div class="skill-title">
                                {{ count(\App\User::all()) }}

                            </div>
                            <div class="skill-sub">
                                gebruikers
                            </div>
                        </div>
                        <div class="skill-panel-icon">
                            <i class="fa fa-angle-right">
                            </i>
                        </div>
                    </div></a>
                </div>
                <div class="col-md-3 col-xs-12">
                     <a href="{{ url('admin/companies') }}"><div class="skill-panel-box usar">
                        <div class="skill-panel-inner-icon">
                            <i class="fa fa-user">
                            </i>
                        </div>
                        <div class="skill-panel-text">
                            <div class="skill-title">
                                {{--{{ $topStatistics->topCompanies }}--}}
                                    {{ count(\App\Models\Company::all()) }}
                            </div>
                            <div class="skill-sub">
                                bedrijven
                            </div>
                        </div>
                        <div class="skill-panel-icon">
                            <i class="fa fa-angle-right">
                            </i>
                        </div>
                    </div></a>
                </div>
                <div class="col-md-3 col-xs-12">
                     <a href="{{ url('admin/affiliates') }}"><div class="skill-panel-box email">
                        <div class="skill-panel-inner-icon">
                            <i class="fa fa-envelope-o">
                            </i>
                        </div>
                        <div class="skill-panel-text">
                            <div class="skill-title">
                                {{--{{ $topStatistics->topAffiliate }}--}}
                                    {{ count(\App\Models\Affiliate::all()) }}
                            </div>
                            <div class="skill-sub">
                                affiliaties
                            </div>
                        </div>
                        <div class="skill-panel-icon">
                            <i class="fa fa-angle-right">
                            </i>
                        </div>
                    </div></a>
                </div>
                <div class="col-md-3 col-xs-12">
                   <a href="{{ url('admin/reservations/clients') }}"> <div class="skill-panel-box note">
                        <div class="skill-panel-inner-icon">
                            <i class="fa fa-pencil-square-o">
                            </i>
                        </div>
                        <div class="skill-panel-text">
                            <div class="skill-title">
                                {{--{{ $topStatistics->topReservations }}--}}
                                    {{ $totalReservation }}
                            </div>
                            <div class="skill-sub">
                                reserveringens
                            </div>
                        </div>
                        <div class="skill-panel-icon">
                            <i class="fa fa-angle-right">
                            </i>
                        </div>
                    </div></a>
                </div>
            </div>
        </div>
    </div>
    <div class="panel panel-default">
        <div class="panel-body">
            <?php
// echo count($topDays);
if (count($topDays) >= 1):
    $topDaysList=[];
    foreach ($topDays as $index=>$topDay):
        $topDaysList[] = [$dayName[$topDay->nameRow + 1], $topDay->countRow];
        if($index >= 6) { break;}
    endforeach;

endif;

// Top times chart
if (count($topTimes) >= 1):
    $topTimesList=[];
    foreach ($topTimes as $index=>$topTime):
        $topTimesList[] = [date('H:i', strtotime($topTime->nameRow)), $topTime->countRow];
        if($index >= 6) { break;}
    endforeach;

endif;


// Top Persons chart
if (count($topPersons) >= 1):
    $topPersonsList=[];
    foreach ($topPersons as $index=>$topPerson):
        $topPersonsList[] = [ $topPerson->nameRow.' '.$topPerson->nameRow == 1 ? 'persoon' : 'personen', $topPerson->countRow];
        if($index >= 6) { break;}
    endforeach;

endif;



?>
        </div>
    </div>
    <style>
        .chart-box{
            min-height: 300px;
        }
    </style>
    <script type="text/javascript" src="https://www.gstatic.com/charts/loader.js"></script>
    <script type="text/javascript">
      google.charts.load("current", {packages:["corechart"]});
      google.charts.setOnLoadCallback(drawChart);
      function drawChart() {
        var data = new google.visualization.DataTable();
        data.addColumn('string', 'Year');
        data.addColumn('number', 'Balance');
        data.addRows(<?= json_encode($topDaysList) ?>);

        var options = {
          is3D: true,
          slices: {  0: {offset: 0.2},
                    1: {offset: 0.25},
                    2: {offset: 0.3},
          },
        };

        var chart = new google.visualization.PieChart(document.getElementById('topDaysChart'));
        chart.draw(data, options);   

         var dataTime = new google.visualization.DataTable();
        dataTime.addColumn('string', 'Year');
        dataTime.addColumn('number', 'Balance');
        dataTime.addRows(<?= json_encode($topTimesList) ?>);
        
        var chartTime = new google.visualization.PieChart(document.getElementById('topTimesChart'));
        chartTime.draw(dataTime, options);

        var dataPerson = new google.visualization.DataTable();
        dataPerson.addColumn('string', 'Year');
        dataPerson.addColumn('number', 'Balance');
        dataPerson.addRows(<?= json_encode($topPersonsList) ?>);
        
        var chartPerson = new google.visualization.PieChart(document.getElementById('topPersonsChart'));
        chartPerson.draw(dataPerson, options);    
      }

      
    </script>
    <div class="chart-panel">
        <div class="container">
            <div class="row">
                <div class="col-md-4 col-sm-4 col-xs-12 chart-blog">
                    <div class="chart-box">
                        <div class="chart-text text-center">
                            <h5>
                                Top reservering: dagen
                            </h5>
                        </div>
                        
                        <div id="donut-example" style="height: 250px; padding-right: 20px;">
                            <div id="topDaysChart"></div>
                            
                        </div>
                    </div>
                </div>
                <div class="col-md-4 col-sm-4 col-xs-12 chart-blog">
                    <div class="chart-box">
                        <div class="chart-text text-center">
                            <h5>
                               Top reservering: tijden
                            </h5>
                            
                        </div>
                        <div id="donut-example1">
                         <div id="donut-example" style="height: 250px;">
                            <div id="topTimesChart">
                            </div>
                        </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-4 col-sm-4 col-xs-12 chart-blog">
                    <div class="chart-box">
                        <div class="chart-text text-center">
                            <h5>
                               Top reservering: personen
                            </h5>
                            
                        </div>
                        <div id="donut-example2">
                        <div id="topPersonsChart">
                            </div>
                           
                        </div>
                    </div>
                </div>
            </div>
            
        </div>
    </div>

    <div class="row">
                   
                    <div class="col-md-4 col-sm-4 col-xs-12">
                        <div class="stats-table ui red segment bg-success">
                            
                               <h5>Top kliks: bedrijven</h5>                         
                          
                                <div class="ui divider">
                                </div>
                                <table class="table table-striped">
                                   <tbody>
                                        @if (count($topClicksCompanies) >= 1)
                                @foreach ($topClicksCompanies as $index =>$topClicksCompany)
                                        <tr>
                                            <td>
                                                {{ $topClicksCompany->nameRow }}
                                            </td>   
                                            <td>
                                                {{ $topClicksCompany->countRow }}x
                                            </td>                                         
                                        </tr>
                                          <?php if($index >=6){ break;} ?>
                                        @endforeach
                            @endif
                            </tbody>
                                  
                                </table>
                            </br>
                        </div>
                    </div>
                    <div class="col-md-4 col-sm-4 col-xs-12">
                        <div class="stats-table ui green segment">
                            <h5 class="">
                                Top kliks: FAQ
                            </h5>
                            
                                <div class="ui divider">
                                </div>
                                <table class="table table-striped">
                                  <tbody>
                                        @if (count($topClicksFaqs) >= 1)
                                @foreach ($topClicksFaqs as $index=> $topClicksFaq)
                                        <tr>
                                            <td>
                                                {{ $topClicksFaq->nameRow }}
                                            </td>
                                            <td>
                                                {{ $topClicksFaq->countRow }}x
                                            </td>
                                        </tr>
                                          <?php if($index >=6){ break;} ?>
                                        @endforeach
                            @endif
                                    </tbody>
                                </table>
                            </br>
                        </div>
                    </div>
                    <div class="col-md-4 col-sm-4 col-xs-12">
                        <div class="stats-table ui red segment">
                            <h5 class="">
                                Top kliks: affiliaties
                            </h5>
                       
                                <div class="ui divider">
                                </div>
                                <table class="table table-striped">
                                    
                                    <tbody>
                                        @if (count($topClicksAffiliates) >= 1)
                                @foreach ($topClicksAffiliates as $index=>$topClicksAffiliate)
                                        <tr>
                                            <td>
                                                {{ $topClicksAffiliate->nameRow }}
                                            </td>
                                            <td>
                                                {{ $topClicksAffiliate->countRow }}x
                                            </td>
                                        </tr>
                                        <?php if($index >=6){ break;} ?>
                                        @endforeach
                            @endif
                                    </tbody>
                                </table>
                            </br>
                        </div>
                    </div>
                </div>
            

    @endsection
</div>
