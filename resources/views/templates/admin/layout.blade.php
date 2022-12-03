<!DOCTYPE html>
<html lang="en">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
    <!-- Meta, title, CSS, favicons, etc. -->
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>{{$title or "Production Planning System"}}</title>

    <link rel="shortcut icon" type="image/png" href="{{asset('admin/images/itlicon.ico')}}"/>
    <!-- Bootstrap -->
    <link href="{{asset('admin/css/bootstrap.min.css')}}" rel="stylesheet">
    <!-- Font Awesome -->
    <link href="{{asset('admin/css/font-awesome.min.css')}}" rel="stylesheet">
    <!-- NProgress -->
    <link href="{{asset('admin/css/nprogress.css')}}" rel="stylesheet">
    <!-- iCheck -->
    <link href="{{asset('admin/css/green.css')}}" rel="stylesheet">
    <!-- bootstrap-progressbar -->
    <link href="{{asset('admin/css/bootstrap-progressbar-3.3.4.min.css')}}" rel="stylesheet">
    <!-- JQVMap -->
    <link href="{{asset('admin/css/jqvmap.min.css')}}" rel="stylesheet"/>
    <!-- Custom Theme Style -->
    <link href="{{asset('admin/css/custom.min.css')}}" rel="stylesheet">
    <!-- Datatables -->
    <link href="{{asset('admin/css/dataTables.bootstrap.min.css')}}" rel="stylesheet">
    <link href="{{asset('admin/css/buttons.bootstrap.min.css')}}" rel="stylesheet">
    <link href="{{asset('admin/css/fixedHeader.bootstrap.min.css')}}" rel="stylesheet">
    <link href="{{asset('admin/css/responsive.bootstrap.min.css')}}" rel="stylesheet">
    <link href="{{asset('admin/css/scroller.bootstrap.min.css')}}" rel="stylesheet">
    <!-- Add by Dimuth 27-07-2017 -->
    <link href="{{asset('admin/css/AdminLTE.min.css')}}" rel="stylesheet">
    <link href="{{asset('admin/css/skins/_all-skins.min.css')}}" rel="stylesheet">
    <link href="{{asset('admin/css/skins/skin-blue.min.css')}}" rel="stylesheet">
    <link href="{{asset('admin/css/skins/skin-blue.css')}}" rel="stylesheet">

    <link href="{{asset('admin/css/select2/select2.min.css')}}" rel="stylesheet">
    <link href="{{asset('admin/css/pretty-checkbox.min.css')}}" rel="stylesheet">
    <!-- Add by Dimuth date time picker 26-03-2018 -->
    <link href="{{asset('admin/css/bootstrap-datetimepicker.css')}}" rel="stylesheet">
    <link href="{{asset('admin/css/bootstrap-datetimepicker.min.css')}}" rel="stylesheet"> 
    <link href="{{asset('admin/css/jquery.minicolors.css')}}" rel="stylesheet">
    <link href="{{asset('admin/css/web.css')}}" rel="stylesheet">
    <link href="{{asset('admin/css/bootstrap-toggle.min.css')}}" rel="stylesheet">
    <!-- CSFR token for ajax call -->
    <meta name="_token" content="{{ csrf_token() }}"/>
    <!-- icheck checkboxes -->
    <link rel="stylesheet" href="{{ asset('admin/icheck/square/yellow.css') }}">
    <!-- {{-- <link rel="stylesheet" href="https://raw.githubusercontent.com/fronteed/icheck/1.x/skins/square/yellow.css"> --}}    -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-select@1.13.14/dist/css/bootstrap-select.min.css">
</head>

<body class="nav-md">
    <div class="container body">
        <div class="main_container">
            <div class="col-md-3 left_col">
                <div class="left_col scroll-view">
                    <div class="navbar nav_title" style="border: 0;">
                        <a href="#" class="site_title"><span>{{Auth::user()->locations->companies->name}}</span></a>
                    </div>

                    <div class="clearfix"></div>

                    <!-- menu profile quick info -->
                    <div class="profile">
                        <div class="profile_pic">
                            <img src="{{asset('admin/images/profile-pictures.png')}}" alt="..." class="img-circle profile_img">
                        </div>
                        <div class="profile_info">
                            <span>@lang('general.app.welcome'),</span>
                            <h2>{{Auth::user()->name}}</h2>
                        </div>
                    </div>
                    <!-- /menu profile quick info -->

                    <br />

                    <!-- sidebar menu -->
                    <div id="sidebar-menu" class="main_menu_side hidden-print main_menu">
                        <div class="menu_section">
                            <h3>Version 1.0</h3>
                            <ul class="nav side-menu">
                                <li><a><i class="fa fa-home"></i> @lang('general.nav.home') <span class="fa fa-chevron-down"></span></a>
                                    <ul class="nav child_menu">
                                        <li><a href="{{route('dashboard.index')}}">Dashboard</a></li>
                                    </ul> 
                                </li>  

                                <li><a><i class="fa fa-linux"></i>@lang('general.app.general') <span class="fa fa-chevron-down"></span></a>
                                    <ul class="nav child_menu">                                                          
                                        <li><a href="{{route('ordertracking.index')}}">Order Tracking Details</a></li>
                                        <li><a href="{{route('sameasauto.index')}}">Same as WO</a></li>
                                        <li><a>Order Tracking<span class="fa fa-chevron-down"></span></a>
                                             <ul class="nav child_menu">
                                                <li><a href="{{route('ordtrcpln.index')}}">Planning</a></li>
                                                <li><a href="{{route('ordtrcpre.index')}}">Designing</a></li>
                                                <li><a href="{{route('ordtrcplt.index')}}">Plate Room</a></li>                             
                                             </ul>   
                                        </li> 
                                        <li><a href="{{route('holdmachines.index')}}">Hold Plan Date & Shift</a></li>
                                    </ul>
                                </li>

                                <li><a><i class="fa fa-print"></i> @lang('general.nav.focus') <span class="fa fa-chevron-down"></span></a>
                                    <ul class="nav child_menu"> 
                                        <li><a>Order Tracking<span class="fa fa-chevron-down"></span></a>
                                             <ul class="nav child_menu">  
                                                <li><a href="{{route('ordtrcprdrot.index')}}">Production</a></li>                                            
                                                <li><a href="{{route('ordtrcprintstr.index')}}">Printing Start</a></li>
                                                <li><a href="{{route('ordtrccutstr.index')}}">Cutting Start</a></li>
                                             </ul>   
                                        </li>  
                                        <li><a href="{{route('pflratefile.index')}}">@lang('general.nav_general.pflratefile')</a></li>
                                        <li><a href="{{route('mrnRotary.index')}}">@lang('general.nav_general.mrnRotary')</a></li>
                                        <li><a>Update Production<span class="fa fa-chevron-down"></span></a>
                                            <ul class="nav child_menu">
                                                <li><a>Printing<span class="fa fa-chevron-down"></span></a>
                                                    <ul class="nav child_menu">
                                                        <li><a href="{{route('printingRotary.index')}}">Complete </a></li>
                                                        <li><a href="{{route('partPrintingRotary.index')}}">Part </a></li>
                                                    </ul>
                                                </li>

                                                <li><a>Cutting<span class="fa fa-chevron-down"></span></a>
                                                    <ul class="nav child_menu">
                                                        <li><a href="{{route('cuttingRotary.index')}}">Complete </a></li>
                                                        <li><a href="{{route('partCuttingRotary.index')}}">Part </a></li>
                                                    </ul>
                                                </li>

                                                <li><a>Packing<span class="fa fa-chevron-down"></span></a>
                                                    <ul class="nav child_menu">
                                                        <li><a href="{{route('packingRotary.index')}}">Complete </a></li>                                                    
                                                    </ul>
                                                </li>

                                                <li><a>AQL<span class="fa fa-chevron-down"></span></a>
                                                    <ul class="nav child_menu">
                                                        <li><a href="{{route('aqlRotary.index')}}">Complete </a></li>                                                    
                                                    </ul>
                                                </li>
                                               
                                            </ul>                                                
                                        </li> 
                                        <li><a href="{{route('processRotary.index')}}">Planning Process</a></li>
                                        <li><a href="{{route('plnBoard.index')}}">Planning Board</a></li>
                                        <li><a>Reports<span class="fa fa-chevron-down"></span></a>
                                            <ul class="nav child_menu"> 
                                                <li><a href="{{route('prdPlnRotary.index')}}">Production Plan</a></li>                                              
                                            </ul>
                                        </li>
                                    </ul>
                                </li>
                                      
                                <li><a><i class="fa fa-print"></i> @lang('general.nav.screen') <span class="fa fa-chevron-down"></span></a>
                                    <ul class="nav child_menu">  
                                        <li><a>Order Tracking<span class="fa fa-chevron-down"></span></a>
                                             <ul class="nav child_menu"> 
                                                <li><a href="{{route('ordtrcprdrot.index')}}">Production</a></li>                                          
                                                <li><a href="{{route('ordtrcprintstr.index')}}">Printing Start</a></li>
                                                <li><a href="{{route('ordtrccutstr.index')}}">Cutting Start</a></li>
                                             </ul>   
                                        </li>                                                            
                                        <li><a href="{{route('scrratefile.index')}}">@lang('general.nav_general.pflratefile')</a></li>
                                        <li><a href="{{route('mrnScreen.index')}}">@lang('general.nav_general.mrnRotary')</a></li>

                                        <li><a>Update Production<span class="fa fa-chevron-down"></span></a>
                                            <ul class="nav child_menu">
                                                <li><a>Printing<span class="fa fa-chevron-down"></span></a>
                                                    <ul class="nav child_menu">
                                                        <li><a href="{{route('printingScreen.index')}}">Complete </a></li>
                                                        <li><a href="{{route('partPrintingScreen.index')}}">Part </a></li>                                                     
                                                    </ul>
                                                </li>
                                                <li><a>Cutting<span class="fa fa-chevron-down"></span></a>
                                                    <ul class="nav child_menu">
                                                        <li><a href="{{route('cuttingScreen.index')}}">Complete </a></li>
                                                        <li><a href="{{route('partCuttingScreen.index')}}">Part </a></li>
                                                       
                                                    </ul>
                                                </li>
                                                <li><a>Packing<span class="fa fa-chevron-down"></span></a>
                                                    <ul class="nav child_menu">
                                                        <li><a href="{{route('packingScreen.index')}}">Complete </a></li>
                                                    </ul>
                                                </li>
                                                <li><a>AQL<span class="fa fa-chevron-down"></span></a>
                                                    <ul class="nav child_menu">
                                                        <li><a href="{{route('aqlScreen.index')}}">Complete </a></li>                                                   
                                                    </ul>
                                                </li>
                                            </ul>                                                
                                        </li> 
                                    </ul>
                                </li>

                
                                <li><a><i class="fa fa-gears"></i> @lang('general.nav.admin_function')<span class="fa fa-chevron-down"></span></a>
                                    <ul class="nav child_menu">
                                        <li><a href="{{route('chains.index')}}">@lang('general.nav_general.chains')</a></li>                                        
                                        <li><a href="{{route('customers.index')}}">@lang('general.nav_general.customers')</a></li>
                                        <li><a href="{{route('items.index')}}">@lang('general.nav_general.items')</a></li>
                                        <li><a href="{{route('machines.index')}}">@lang('general.nav_general.machines')</a></li>
                                        <li><a href="{{route('labelreferences.index')}}">@lang('general.nav_general.labelreferences')</a></li>
                                        <li><a href="{{route('customergroups.index')}}">@lang('general.nav_general.customergroups')</a></li>
                                        <li><a href="{{route('itemgroups.index')}}">@lang('general.nav_general.itemgroups')</a></li>
                                        <li><a href="{{route('substratecategories.index')}}">@lang('general.nav_general.substrate_category')</a></li>
                                        <li><a href="{{route('machinetypes.index')}}">@lang('general.nav_general.machine_types')</a></li>
                                        <li><a href="{{route('labeltypes.index')}}">@lang('general.nav_general.labeltypes')</a></li>
                                        <li><a href="{{route('daytypes.index')}}">@lang('general.nav_general.daytypes')</a></li>
                                        <li><a href="{{route('mcholdreasons.index')}}">Machine Hold Reasons</a></li>
                                        <li><a href="{{route('holidays.index')}}">@lang('general.nav_general.holidays')</a></li>
                                        <li><a href="{{route('wheeltypes.index')}}">@lang('general.nav_general.wheel_types')</a></li>
                                        <li><a href="{{route('cylinders.index')}}">@lang('general.nav_general.cylinders')</a></li>
                                        <li><a href="{{route('units.index')}}">@lang('general.nav_general.units')</a></li>
                                        <li><a href="{{route('machinefeatures.index')}}">@lang('general.nav_general.machine_features')</a></li>
                                                                                                        
                                    </ul>
                                </li>
    
                                <li><a><i class="fa fa-themeisle"></i> @lang('general.nav.human_resource')<span class="fa fa-chevron-down"></span></a>
                                    <ul class="nav child_menu">
                                        <li><a href="{{route('levels.index')}}">@lang('general.nav_general.levels')</a></li>
                                        <li><a href="{{route('departments.index')}}">@lang('general.nav_general.departments')</a></li>
                                        <li><a href="{{route('designations.index')}}">@lang('general.nav_general.designations')</a></li>                                       
                                    </ul>
                                </li>
                                
                            
                                <li><a><i class="fa fa-users"></i> @lang('general.nav.user_management') <span class="fa fa-chevron-down"></span></a>
                                    <ul class="nav child_menu">                                        
                                        <li><a href="{{route('users.index')}}">@lang('general.nav.user_list')</a></li>
                                        <li><a href="{{route('permissions.index')}}">@lang('general.nav.permissions_list')</a></li>
                                        <li><a href="{{route('roles.index')}}">@lang('general.nav.roles_list')</a></li>
                                        <li><a href="{{route('companies.index')}}">@lang('general.nav.companies')</a></li>
                                        <li><a href="{{route('locations.index')}}">@lang('general.nav.locations')</a></li>                                       
                                    </ul>
                                </li>
                            
                                <li><a href="{{ route('auth.change_password') }}"><i class="fa fa-key"></i>Change password <span class="fa fa-chevron-down"></span></a></li>
                                
                            </ul>
                        </div>
                    </div>
                    <!-- /sidebar menu -->

                    <!-- /menu footer buttons -->
                    <div class="sidebar-footer hidden-small">
                        <a data-toggle="tooltip" data-placement="top" title="Settings">
                            <span class="glyphicon glyphicon-cog" aria-hidden="true"></span>
                        </a>
                        <a data-toggle="tooltip" data-placement="top" title="FullScreen">
                            <span class="glyphicon glyphicon-fullscreen" aria-hidden="true"></span>
                        </a>
                        <a data-toggle="tooltip" data-placement="top" title="Lock">
                            <span class="glyphicon glyphicon-eye-close" aria-hidden="true"></span>
                        </a>
                        <a data-toggle="tooltip" data-placement="top" title="Logout">
                            <span class="glyphicon glyphicon-off" aria-hidden="true"></span>
                        </a>
                    </div>
                    <!-- /menu footer buttons -->
                </div>
            </div>

            <!-- top navigation -->
            <div class="top_nav">
                <div class="nav_menu">
                    <nav>
                        <div class="nav toggle">
                            <a id="menu_toggle"><i class="fa fa-bars"></i></a>
                        </div>
                        <ul class="nav navbar-nav">
                            <li><a href="{{route('dashboard.index')}}">Dashboard</a></li>
                            <li><a href="{{route('dbRotary.index')}}">PFL-Rotary</a></li>
                            <li><a href="{{route('dbScreen.index')}}">Screen</a></li>
                        </ul>

                        <ul class="nav navbar-nav navbar-right">
                            <li class="">
                                <a href="javascript:;" class="user-profile dropdown-toggle" data-toggle="dropdown" aria-expanded="false">
                                    {{Auth::user()->name}}
                                    <span class=" fa fa-angle-down"></span>
                                </a>
                                <ul class="dropdown-menu dropdown-usermenu pull-right">
                                    <li>
                                        <a href="{{route('logout')}}"
                                            onclick="event.preventDefault();
                                            document.getElementById('logout-form').submit();">
                                            <i class="fa fa-sign-out pull-right"></i> @lang('general.logout.logout')
                                        </a>
                                        <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                                            {{ csrf_field() }}
                                        </form>
                                    </li>
                                </ul>
                            </li>
                        </ul>
                    </nav>
                </div>
            </div>
        </div>
            <!-- /top navigation -->

        <!-- page content -->
        <div class="right_col" role="main">
            @include('templates.admin.partials.alerts')
            @yield('content')
        </div>
        <!-- /page content -->

        <!-- footer content -->        
        <footer>
            <div class="pull-right" >
            Production Planning System by <a href="#">ITL - Sri Lanka</a>
            </div>
            <div class="clearfix"></div>
        </footer>
        <!-- /footer content -->
        
    </div>

<!-- jQuery -->
<script src="{{asset('admin/js/jquery.min.js')}}"></script>
<!-- Bootstrap -->
<script src="{{asset('admin/js/bootstrap.min.js')}}"></script>
<!-- FastClick -->
<script src="{{asset('admin/js/fastclick.js')}}"></script>
<!-- NProgress -->
<script src="{{asset('admin/js/nprogress.js')}}"></script>
<!-- iCheck -->
<script src="{{asset('admin/js/icheck.min.js')}}"></script>
<!-- Datatables -->
<script src="{{asset('admin/js/jquery.dataTables.min.js')}}"></script>
<script src="{{asset('admin/js/dataTables.bootstrap.min.js')}}"></script>
<script src="{{asset('admin/js/dataTables.buttons.min.js')}}"></script>
<script src="{{asset('admin/js/buttons.bootstrap.min.js')}}"></script>
<script src="{{asset('admin/js/buttons.flash.min.js')}}"></script>
<script src="{{asset('admin/js/buttons.html5.min.js')}}"></script>
<script src="{{asset('admin/js/buttons.print.min.js')}}"></script>
<script src="{{asset('admin/js/dataTables.fixedHeader.min.js')}}"></script>
<script src="{{asset('admin/js/dataTables.keyTable.min.js')}}"></script>
<script src="{{asset('admin/js/dataTables.responsive.min.js')}}"></script>
<script src="{{asset('admin/js/responsive.bootstrap.js')}}"></script>
<script src="{{asset('admin/js/datatables.scroller.min.js')}}"></script>
<script src="{{asset('admin/js/jszip.min.js')}}"></script>
<script src="{{asset('admin/js/pdfmake.min.js')}}"></script>
<script src="{{asset('admin/js/vfs_fonts.js')}}"></script>
<script src="{{asset('admin/css/select2/select2.full.min.js')}}"></script>
<script src="{{asset('admin/js/bootstrap-checkbox.min.js')}}"></script>
<!-- Custom Theme Scripts -->
<script src="{{asset('admin/js/custom.min.js')}}"></script>
<script src="{{asset('admin/js/bootstrap-toggle.min.js')}}"></script>
<!-- Add by dimuth 27-07-2017 bootstrap-datepicker -->
<!-- <script src="{{asset('admin/css/daterangepicker/daterangepicker.js')}}" type="text/javascript" ></script> -->
<script src="{{asset('admin/css/datepicker/bootstrap-datepicker.js')}}" type="text/javascript" ></script>
<script src="{{asset('admin/js/moment-2.4.0.js')}}"></script>
<script src="{{asset('admin/js/bootstrap-datetimepicker.js')}}" type="text/javascript" ></script>
<script src="{{asset('admin/js/bootstrap-datetimepicker.min.js')}}" type="text/javascript" ></script>


<script src="{{asset('admin/js/jquery.minicolors.js')}}" type="text/javascript" ></script>

<!-- icheck checkboxes -->
<script type="text/javascript" src="{{ asset('admin/icheck/icheck.min.js') }}"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap-select@1.13.14/dist/js/bootstrap-select.min.js"></script>


<!-- Datatables -->
<script>
    $(document).ready(function() {
        var handleDataTableButtons = function() {
            if ($("#datatable-buttons").length) {
                $("#datatable-buttons").DataTable({
                    dom: "Bfrtip",
                    buttons: [
                    {
                        extend: "copy",
                        className: "btn-sm"
                    },
                    {
                        extend: "csv",
                        className: "btn-sm"
                    },
                    {
                        extend: "excel",
                        className: "btn-sm"
                    },
                    {
                        extend: "pdfHtml5",
                        className: "btn-sm"
                    },
                    {
                        extend: "print",
                        className: "btn-sm"
                    },
                    ],
                    responsive: true
                });
            }
        };

        TableManageButtons = function() {
            "use strict";
            return {
                init: function() {
                    handleDataTableButtons();
                }
            };
        }();

        $('#datatable').dataTable();

        $('#datatable-keytable').DataTable({
            keys: true
        });

        $('#datatable-responsive').DataTable();

        $('#datatable-scroller').DataTable({
            ajax: "js/datatables/json/scroller-demo.json",
            deferRender: true,
            scrollY: 380,
            scrollCollapse: true,
            scroller: true
        });

        $('#datatable-fixed-header').DataTable({
            fixedHeader: true
        });

        var $datatable = $('#datatable-checkbox');

        $datatable.dataTable({
            'order': [[ 1, 'asc' ]],
            'columnDefs': [
            { orderable: false, targets: [0] }
            ]
        });
        $datatable.on('draw.dt', function() {
            $('input').iCheck({
                checkboxClass: 'icheckbox_flat-green'
            });
        });

        TableManageButtons.init();
    });
</script>
<!-- /Datatables -->
<script>
    $(document).ready(function() {
        
        //Date picker  

        $('#cratedate').datepicker({
            autoclose: true,
            sideBySide: true,
            debug:false,
            useCurrent: false,
            format: 'yyyy/mm/dd'
        });

        $('#date').datepicker({
            autoclose: true,
            sideBySide: true,
            debug:false,
            useCurrent: false,
            format: 'yyyy/mm/dd'
        });

        $('#from').datepicker({
            autoclose: true,
            sideBySide: true,
            debug:false,
            useCurrent: false,
            format: 'yyyy/mm/dd'
        });
        $('#to').datepicker({
            autoclose: true,
            sideBySide: true,
            debug:false,
            useCurrent: false,
            format: 'yyyy/mm/dd'
        });

        $('#from_date_time').datetimepicker({
            autoclose: true,    
            sideBySide: true,
            debug:false,
            useCurrent: false,        
            format: 'yyyy/mm/dd hh:ii'
        }); 

        $('#to_date_time').datetimepicker({
            autoclose: true,
            sideBySide: true,
            debug:false,
            useCurrent: false,
            format: 'yyyy/mm/dd hh:ii'
        });         

    });
</script>

@yield('js')
</body>
</html>