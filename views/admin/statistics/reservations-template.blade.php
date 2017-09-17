@extends('template.theme')

@section('content')
<div class="content">
    @include('admin.template.breadcrumb')
    <div class="skill-blog">
        <div class="skill-panel">
            <div class="container">
                <div class="row">
                    <div class="col-md-3 col-sm-6 col-xs-6 skill-panel-blog">
                        <div class="skill-panel-box massage">
                            <div class="skill-panel-inner-icon">
                                <i class="fa fa-comments">
                                </i>
                            </div>
                            <div class="skill-panel-text">
                                <h2>
                                    26
                                </h2>
                                <h5>
                                    Visits Today
                                </h5>
                                <h5>
                                    Pages / Visit
                                </h5>
                            </div>
                            <div class="skill-panel-icon">
                                <i class="fa fa-angle-right">
                                </i>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3 col-sm-6 col-xs-6 skill-panel-blog">
                        <div class="skill-panel-box usar">
                            <div class="skill-panel-inner-icon">
                                <i class="fa fa-user">
                                </i>
                            </div>
                            <div class="skill-panel-text">
                                <h2>
                                    12
                                </h2>
                                <h5>
                                    % Unique Visitors
                                </h5>
                                <h5>
                                    Avg. Visit Duration
                                </h5>
                            </div>
                            <div class="skill-panel-icon">
                                <i class="fa fa-angle-right">
                                </i>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3 col-sm-6 col-xs-6 skill-panel-blog">
                        <div class="skill-panel-box email">
                            <div class="skill-panel-inner-icon">
                                <i class="fa fa-envelope-o">
                                </i>
                            </div>
                            <div class="skill-panel-text">
                                <h2>
                                    124
                                </h2>
                                <h5>
                                    Page Views
                                </h5>
                                <h5>
                                    % Bounce Rate
                                </h5>
                            </div>
                            <div class="skill-panel-icon">
                                <i class="fa fa-angle-right">
                                </i>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3 col-sm-6 col-xs-6 skill-panel-blog">
                        <div class="skill-panel-box note">
                            <div class="skill-panel-inner-icon">
                                <i class="fa fa-pencil-square-o">
                                </i>
                            </div>
                            <div class="skill-panel-text">
                                <h2>
                                    13
                                </h2>
                                <h5>
                                    Today's Earnings
                                </h5>
                                <h5>
                                    Pages Today's
                                </h5>
                            </div>
                            <div class="skill-panel-icon">
                                <i class="fa fa-angle-right">
                                </i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="chart-panel">
            <div class="container">
                <div class="row">
                    <div class="col-md-4 chart-blog">
                        <div class="chart-box">
                            <div class="chart-text">
                                <h5>
                                    Most Browser Used
                                </h5>
                                <p>
                                    Duis autem vel eum iriure dolor in hendrerit in vulputate...
                                </p>
                            </div>
                            <div id="donut-example">
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4 chart-blog">
                        <div class="chart-box">
                            <div class="chart-text">
                                <h5>
                                    Most Browser Used
                                </h5>
                                <p>
                                    Duis autem vel eum iriure dolor in hendrerit in vulputate...
                                </p>
                            </div>
                            <div id="donut-example1" style="height: 250px;">
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4 chart-blog">
                        <div class="chart-box">
                            <div class="chart-text">
                                <h5>
                                    Most Browser Used
                                </h5>
                                <p>
                                    Duis autem vel eum iriure dolor in hendrerit in vulputate...
                                </p>
                            </div>
                            <div id="donut-example2" style="height: 250px;">
                            </div>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-12 chart-button">
                        <button>
                            more info
                        </button>
                        <button>
                            active
                        </button>
                    </div>
                </div>
            </div>
        </div>
        <div class="chart-skill-panel">
            <div class="container">
                <div class="row">
                    <div class="col-md-8 chart-skill-panel-blog">
                        <div class="chart-skill-panel-chart">
                            <div class="chart-skill-panel-text">
                                <h4>
                                    Overchart Report
                                </h4>
                            </div>
                            <div id="area-example" style="height: 300px;">
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4 chart-skill-panel-blog">
                        <div class="chart-skill-panel-box">
                            <div class="chart-skill-panel-text">
                                <h4>
                                    Edit
                                </h4>
                            </div>
                            <div class="chart-skill-panel-inner">
                                <ul>
                                    <li>
                                        <h3>
                                            <i class="fa fa-comment">
                                            </i>
                                            New Comment
                                        </h3>
                                        <h4>
                                            Dec 10
                                        </h4>
                                    </li>
                                    <li>
                                        <h3>
                                            <i class="fa fa-twitter">
                                            </i>
                                            I Followers
                                        </h3>
                                        <h4>
                                            Dec 10
                                        </h4>
                                    </li>
                                    <li>
                                        <h3>
                                            <i class="fa fa-envelope">
                                            </i>
                                            Massage Sent
                                        </h3>
                                        <h4>
                                            Dec 10
                                        </h4>
                                    </li>
                                    <li>
                                        <h3>
                                            <i class="fa fa-credit-card-alt">
                                            </i>
                                            New Comment
                                        </h3>
                                        <h4>
                                            Dec 10
                                        </h4>
                                    </li>
                                    <li>
                                        <h3>
                                            <i class="fa fa-download">
                                            </i>
                                            New Task
                                        </h3>
                                        <h4>
                                            Dec 10
                                        </h4>
                                    </li>
                                    <li>
                                        <h3>
                                            <i class="fa fa-pie-chart">
                                            </i>
                                            Service Password
                                        </h3>
                                        <h4>
                                            Dec 10
                                        </h4>
                                    </li>
                                    <li>
                                        <h3>
                                            <i class="fa fa-shopping-cart">
                                            </i>
                                            service not available
                                        </h3>
                                        <h4>
                                            Dec 10
                                        </h4>
                                    </li>
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="chart-teble">
            <div class="col-md-12 wrap">
                <div class="table-responsive_wrap">
                    <table class="table table-bordred table-striped" id="mytable">
                        <thead>
                            <th>
                                <input id="checkall" type="checkbox"/>
                            </th>
                            <th>
                                #
                            </th>
                            <th>
                                Name
                            </th>
                            <th>
                                Email
                            </th>
                            <th>
                                Designation
                            </th>
                            <th>
                                Mobile Numberl
                            </th>
                            <th>
                                Action
                            </th>
                            <th>
                                Delete
                            </th>
                        </thead>
                        <tbody>
                            <tr class="checkbox-inner">
                                <td>
                                    <input class="checkthis" type="checkbox"/>
                                </td>
                                <td>
                                    1
                                </td>
                                <td>
                                    Irshad
                                </td>
                                <td>
                                    CB 106/107 Street # 11 Wah Cantt Islamabad Pakistan
                                </td>
                                <td>
                                    isometric.mohsin@gmail.com
                                </td>
                                <td>
                                    +923335586757
                                </td>
                                <td class="table-icon">
                                    <p data-placement="top" data-toggle="tooltip" title="Edit">
                                        <button class="btn btn-primary btn-xs" data-target="#edit" data-title="Edit" data-toggle="modal">
                                            <span class="fa fa-pencil">
                                            </span>
                                        </button>
                                    </p>
                                </td>
                                <td class="table-icon">
                                    <p data-placement="top" data-toggle="tooltip" title="Delete">
                                        <button class="btn btn-danger btn-xs" data-target="#delete" data-title="Delete" data-toggle="modal">
                                            <span class="fa fa-trash">
                                            </span>
                                        </button>
                                    </p>
                                </td>
                            </tr>
                            <tr class="checkbox-inner">
                                <td>
                                    <input class="checkthis" type="checkbox"/>
                                </td>
                                <td>
                                    2
                                </td>
                                <td>
                                    Irshad
                                </td>
                                <td>
                                    CB 106/107 Street # 11 Wah Cantt Islamabad Pakistan
                                </td>
                                <td>
                                    isometric.mohsin@gmail.com
                                </td>
                                <td>
                                    +923335586757
                                </td>
                                <td class="table-icon">
                                    <p data-placement="top" data-toggle="tooltip" title="Edit">
                                        <button class="btn btn-primary btn-xs" data-target="#edit" data-title="Edit" data-toggle="modal">
                                            <span class="fa fa-pencil">
                                            </span>
                                        </button>
                                    </p>
                                </td>
                                <td class="table-icon">
                                    <p data-placement="top" data-toggle="tooltip" title="Delete">
                                        <button class="btn btn-danger btn-xs" data-target="#delete" data-title="Delete" data-toggle="modal">
                                            <span class="fa fa-trash">
                                            </span>
                                        </button>
                                    </p>
                                </td>
                            </tr>
                            <tr class="checkbox-inner">
                                <td>
                                    <input class="checkthis" type="checkbox"/>
                                </td>
                                <td>
                                    3
                                </td>
                                <td>
                                    Irshad
                                </td>
                                <td>
                                    CB 106/107 Street # 11 Wah Cantt Islamabad Pakistan
                                </td>
                                <td>
                                    isometric.mohsin@gmail.com
                                </td>
                                <td>
                                    +923335586757
                                </td>
                                <td class="table-icon">
                                    <p data-placement="top" data-toggle="tooltip" title="Edit">
                                        <button class="btn btn-primary btn-xs" data-target="#edit" data-title="Edit" data-toggle="modal">
                                            <span class="fa fa-pencil">
                                            </span>
                                        </button>
                                    </p>
                                </td>
                                <td class="table-icon">
                                    <p data-placement="top" data-toggle="tooltip" title="Delete">
                                        <button class="btn btn-danger btn-xs" data-target="#delete" data-title="Delete" data-toggle="modal">
                                            <span class="fa fa-trash">
                                            </span>
                                        </button>
                                    </p>
                                </td>
                            </tr>
                            <tr class="checkbox-inner">
                                <td>
                                    <input class="checkthis" type="checkbox"/>
                                </td>
                                <td>
                                    4
                                </td>
                                <td>
                                    Irshad
                                </td>
                                <td>
                                    CB 106/107 Street # 11 Wah Cantt Islamabad Pakistan
                                </td>
                                <td>
                                    isometric.mohsin@gmail.com
                                </td>
                                <td>
                                    +923335586757
                                </td>
                                <td class="table-icon">
                                    <p data-placement="top" data-toggle="tooltip" title="Edit">
                                        <button class="btn btn-primary btn-xs" data-target="#edit" data-title="Edit" data-toggle="modal">
                                            <span class="fa fa-pencil">
                                            </span>
                                        </button>
                                    </p>
                                </td>
                                <td class="table-icon">
                                    <p data-placement="top" data-toggle="tooltip" title="Delete">
                                        <button class="btn btn-danger btn-xs" data-target="#delete" data-title="Delete" data-toggle="modal">
                                            <span class="fa fa-trash">
                                            </span>
                                        </button>
                                    </p>
                                </td>
                            </tr>
                            <tr class="checkbox-inner">
                                <td>
                                    <input class="checkthis" type="checkbox"/>
                                </td>
                                <td>
                                    5
                                </td>
                                <td>
                                    Irshad
                                </td>
                                <td>
                                    CB 106/107 Street # 11 Wah Cantt Islamabad Pakistan
                                </td>
                                <td>
                                    isometric.mohsin@gmail.com
                                </td>
                                <td>
                                    +923335586757
                                </td>
                                <td class="table-icon">
                                    <p data-placement="top" data-toggle="tooltip" title="Edit">
                                        <button class="btn btn-primary btn-xs" data-target="#edit" data-title="Edit" data-toggle="modal">
                                            <span class="fa fa-pencil">
                                            </span>
                                        </button>
                                    </p>
                                </td>
                                <td class="table-icon">
                                    <p data-placement="top" data-toggle="tooltip" title="Delete">
                                        <button class="btn btn-danger btn-xs" data-target="#delete" data-title="Delete" data-toggle="modal">
                                            <span class="fa fa-trash">
                                            </span>
                                        </button>
                                    </p>
                                </td>
                            </tr>
                            <tr class="checkbox-inner">
                                <td>
                                    <input class="checkthis" type="checkbox"/>
                                </td>
                                <td>
                                    6
                                </td>
                                <td>
                                    Irshad
                                </td>
                                <td>
                                    CB 106/107 Street # 11 Wah Cantt Islamabad Pakistan
                                </td>
                                <td>
                                    isometric.mohsin@gmail.com
                                </td>
                                <td>
                                    +923335586757
                                </td>
                                <td class="table-icon">
                                    <p data-placement="top" data-toggle="tooltip" title="Edit">
                                        <button class="btn btn-primary btn-xs" data-target="#edit" data-title="Edit" data-toggle="modal">
                                            <span class="fa fa-pencil">
                                            </span>
                                        </button>
                                    </p>
                                </td>
                                <td class="table-icon">
                                    <p data-placement="top" data-toggle="tooltip" title="Delete">
                                        <button class="btn btn-danger btn-xs" data-target="#delete" data-title="Delete" data-toggle="modal">
                                            <span class="fa fa-trash">
                                            </span>
                                        </button>
                                    </p>
                                </td>
                            </tr>
                            <tr class="checkbox-inner">
                                <td>
                                    <input class="checkthis" type="checkbox"/>
                                </td>
                                <td>
                                    7
                                </td>
                                <td>
                                    Irshad
                                </td>
                                <td>
                                    CB 106/107 Street # 11 Wah Cantt Islamabad Pakistan
                                </td>
                                <td>
                                    isometric.mohsin@gmail.com
                                </td>
                                <td>
                                    +923335586757
                                </td>
                                <td class="table-icon">
                                    <p data-placement="top" data-toggle="tooltip" title="Edit">
                                        <button class="btn btn-primary btn-xs" data-target="#edit" data-title="Edit" data-toggle="modal">
                                            <span class="fa fa-pencil">
                                            </span>
                                        </button>
                                    </p>
                                </td>
                                <td class="table-icon">
                                    <p data-placement="top" data-toggle="tooltip" title="Delete">
                                        <button class="btn btn-danger btn-xs" data-target="#delete" data-title="Delete" data-toggle="modal">
                                            <span class="fa fa-trash">
                                            </span>
                                        </button>
                                    </p>
                                </td>
                            </tr>
                            <tr class="checkbox-inner">
                                <td>
                                    <input class="checkthis" type="checkbox"/>
                                </td>
                                <td>
                                    8
                                </td>
                                <td>
                                    Irshad
                                </td>
                                <td>
                                    CB 106/107 Street # 11 Wah Cantt Islamabad Pakistan
                                </td>
                                <td>
                                    isometric.mohsin@gmail.com
                                </td>
                                <td>
                                    +923335586757
                                </td>
                                <td class="table-icon">
                                    <p data-placement="top" data-toggle="tooltip" title="Edit">
                                        <button class="btn btn-primary btn-xs" data-target="#edit" data-title="Edit" data-toggle="modal">
                                            <span class="fa fa-pencil">
                                            </span>
                                        </button>
                                    </p>
                                </td>
                                <td class="table-icon">
                                    <p data-placement="top" data-toggle="tooltip" title="Delete">
                                        <button class="btn btn-danger btn-xs" data-target="#delete" data-title="Delete" data-toggle="modal">
                                            <span class="fa fa-trash">
                                            </span>
                                        </button>
                                    </p>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
<script src="js/jquery.min.js">
</script>
<script src="js/bootstrap.js">
</script>
<script src="js/slick.js">
</script>
<script src="js/morris.min.js">
</script>
<script src="js/raphael-min.js">
</script>
<script src="js/scrolling-nav.js">
</script>
<script type="text/javascript">
    $(function(){
                $(".dropdown").hover(
                    function() {
                        $('.dropdown-menu', this).stop( true, true ).fadeIn("fast");
                        $(this).toggleClass('open');
                        $('b', this).toggleClass("caret caret-up");
                    },
                    function() {
                        $('.dropdown-menu', this).stop( true, true ).fadeOut("fast");
                        $(this).toggleClass('open');
                        $('b', this).toggleClass("caret caret-up");
                    });
            });
</script>
<script>
    Morris.Donut({
                element: 'donut-example',
                data: [
                    {label: "Android", value: 30},
                    {label: "Firefox", value: 10},
                    {label: "Safari", value: 20},
                    {label: "iPhone", value: 20},
                    {label: "Chrome", value: 20}
                ]
            });

            Morris.Donut({
                element: 'donut-example1',
                data: [
                    {label: "Android", value: 30},
                    {label: "Firefox", value: 10},
                    {label: "Safari", value: 20},
                    {label: "iPhone", value: 20},
                    {label: "Chrome", value: 20}
                ]
            });

            Morris.Donut({
                element: 'donut-example2',
                data: [
                    {label: "Android", value: 30},
                    {label: "Firefox", value: 10},
                    {label: "Safari", value: 20},
                    {label: "iPhone", value: 20},
                    {label: "Chrome", value: 20}
                ]
            });

            Morris.Donut({
                element: 'donut-example3',
                data: [
                    {label: "Android", value: 30},
                    {label: "Firefox", value: 10},
                    {label: "Safari", value: 20},
                    {label: "iPhone", value: 20},
                    {label: "Chrome", value: 20}
                ]
            });
</script>
<script>
    Morris.Area({
                element: 'area-example',
                data: [
                    { y: '1.1.', a: 100, b: 90 },
                    { y: '2.1.', a: 75,  b: 65 },
                    { y: '3.1.', a: 50,  b: 40 },
                    { y: '4.1.', a: 75,  b: 65 },
                    { y: '5.1.', a: 50,  b: 40 },
                    { y: '6.1.', a: 75,  b: 65 },
                    { y: '7.1.', a: 100, b: 90 }
                ],
                xkey: 'y',
                ykeys: ['a', 'b'],
                labels: ['Series A', 'Series B']
            });

            $(document).ready(function(){
                $("#mytable #checkall").click(function () {
                    if ($("#mytable #checkall").is(':checked')) {
                        $("#mytable input[type=checkbox]").each(function () {
                            $(this).prop("checked", true);
                        });

                    } else {
                        $("#mytable input[type=checkbox]").each(function () {
                            $(this).prop("checked", false);
                        });
                    }
                });

                $("[data-toggle=tooltip]").tooltip();
            });
</script>
<div class="clear">
</div>
@stop
