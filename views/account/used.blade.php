@extends('template.theme')



@section('content')
<div class="content">
    <div class="ui breadcrumb">
            <a href="{{ url('/') }}" class="section">Home</a>
            <i class="right chevron icon divider"></i>

            <a href="{{ url('/account/giftcards') }}" data-activates="slide-out">Kopen Giftcard</a>
            <i class="right chevron icon divider"></i>

            <div class="active section">Gebruik giftcard</div>
        </div>
        <div class="ui divider"></div>


    <div id="formList">
        <table class="ui very basic collapsing sortable celled table list" style="width: 100%;">
            <thead>
                <tr>
                    <th data-slug="created_at" class="three wide">Datum</th>
                    <th data-slug="name" class="two wide">Naam</th>
                    <th data-slug="code" class="two wide">Code</th>
                    <th data-slug="amount" class="two wide">Bedrag</th>
                    
                </tr>
            </thead>
            <tbody class="list search">
                @if(count($data) > 0)
                @foreach($data as $fetch)
                <?php 
                $date = \Carbon\Carbon::create(
                    date('Y', strtotime($fetch['created_at'])),
                    date('m', strtotime($fetch['created_at'])),
                    date('d', strtotime($fetch['created_at']))
                );
                ?>
                <tr>
                    <td>
                        <i class="calendar icon"></i> 
                        {{ $date->formatLocalized('%d %B %Y') }}<br> 

                        <i class="clock icon"></i>
                        {{ date('H:i', strtotime($fetch['created_at'])) }}
                    </td>
                    <td>{{$fetch['name']}}</td>
                    <td>{{$fetch['code']}}</td>
                    <td>{{$fetch['amount']}}</td>
                    
                </tr>
                @endforeach
                @else
                <tr><td colspan="4"><div class="ui error message">Er is geen data gevonden.</div></td></tr>
                @endif
            </tbody>
        </table>
    </div>

    {!! with(new \App\Presenter\Pagination($data->appends($paginationQueryString)))->render() !!}
</div>
<div class="clear"></div>
@stop
