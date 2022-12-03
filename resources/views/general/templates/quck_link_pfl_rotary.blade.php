<div  class="row">
  <div class="col-md-12 col-sm-12 col-xs-12">
    <div class="x_panel">
        <div class="x_title">
            <h2>Quick Links - PFL-Rotary </h2>
            <div class="clearfix"></div>
        </div>
        <div class="form-group">
            <div  class="row">               
                <div class="col-md-12 col-sm-12 col-xs-12">
                    
                    @permission(('order_tracking_prd_rotary_access_allow'))
                        <div class="col-lg-2 col-sm-4 col-xs-4">
                            <div class="small-box bg-olive">
                                <div class="inner">
                                    <h3>Production </h3>
                                    <p>S c a n</p>
                                </div>
                                <div class="icon" style="margin-top:10px">
                                    <i class="glyphicon glyphicon-barcode"></i>
                                </div>                                
                                <a href="{{route('ordtrcprdrot.index')}}" class="small-box-footer btn {{ Auth::user()->can('order_tracking_prd_rotary_access_allow') ? 'active' : 'disabled' }}">
                                    Go <i class="fa fa-arrow-circle-right"></i>
                                </a>
                            </div>
                        </div>
                    @endpermission

                    @permission(('order_tracking_print_str_access_allow'))
                        <div class="col-lg-3 col-sm-4 col-xs-6">
                            <div class="small-box bg-gray">
                                <div class="inner">
                                    <h3>Printing Start </h3>
                                    <p>S c a n</p>
                                </div>
                                <div class="icon" style="margin-top:10px">
                                    <i class="glyphicon glyphicon-barcode"></i>
                                </div>                                
                                <a href="{{route('ordtrcprintstr.index')}}" class="small-box-footer btn {{ Auth::user()->can('order_tracking_print_str_access_allow') ? 'active' : 'disabled' }}">
                                    Go <i class="fa fa-arrow-circle-right"></i>
                                </a>
                            </div>
                        </div>
                    @endpermission
                     @permission(('printing_rotary_access_allow'))
                        <div class="col-lg-2 col-sm-4 col-xs-4">
                            <div class="small-box bg-teal">
                                <div class="inner">
                                    <h3>Printing</h3>
                                    <p>S c a n</p>
                                </div>
                                <div class="icon" style="margin-top:10px">
                                    <i class="glyphicon glyphicon-barcode"></i>
                                </div>                        
                                <a href="{{route('printingRotary.index')}}" class="small-box-footer btn {{ Auth::user()->can('printing_rotary_access_allow') ? 'active' : 'disabled' }}">
                                    Go <i class="fa fa-arrow-circle-right"></i>
                                </a>                        
                            </div>
                        </div>
                    @endpermission

                    @permission(('order_tracking_cut_str_access_allow'))
                        <div class="col-lg-3 col-sm-4 col-xs-4">
                            <div class="small-box bg-gray">
                                <div class="inner">
                                    <h3>Cutting Start</h3>
                                    <p>S c a n</p>
                                </div>
                                <div class="icon" style="margin-top:10px">
                                    <i class="glyphicon glyphicon-barcode"></i>
                                </div>                        
                                <a href="{{route('ordtrccutstr.index')}}" class="small-box-footer btn {{ Auth::user()->can('order_tracking_cut_str_access_allow') ? 'active' : 'disabled' }}">
                                    Go <i class="fa fa-arrow-circle-right"></i>
                                </a>                        
                            </div>
                        </div>
                    @endpermission

                    @permission(('cutting_rotary_access_allow'))
                        <div class="col-lg-2 col-sm-4 col-xs-6">
                            <div class="small-box bg-teal">
                                <div class="inner">
                                    <h3>Cutting</h3>
                                    <p>S c a n</p>
                                </div>
                                <div class="icon" style="margin-top:10px">
                                    <i class="glyphicon glyphicon-barcode"></i>
                                </div>                        
                                <a href="{{route('cuttingRotary.index')}}" class="small-box-footer btn {{ Auth::user()->can('cutting_rotary_access_allow') ? 'active' : 'disabled' }}">
                                    Go <i class="fa fa-arrow-circle-right"></i>
                                </a>                        
                            </div>
                        </div>
                    @endpermission

                </div>
            </div>
            <div  class="row"> 
            <div class="col-md-12 col-sm-12 col-xs-12">
                @permission(('packing_rotary_access_allow'))
                    <div class="col-lg-2 col-sm-4 col-xs-4">
                        <div class="small-box bg-gray">
                            <div class="inner">
                                <h3>Packing </h3>
                                <p>S c a n</p>
                            </div>
                            <div class="icon" style="margin-top:10px">
                                <i class="glyphicon glyphicon-barcode"></i>
                            </div>                                
                            <a href="{{route('packingRotary.index')}}" class="small-box-footer btn {{ Auth::user()->can('packing_rotary_access_allow') ? 'active' : 'disabled' }}">
                                Go <i class="fa fa-arrow-circle-right"></i>
                            </a>
                        </div>
                    </div>
                @endpermission 

                @permission(('aql_rotary_access_allow'))
                    <div class="col-lg-2 col-sm-4 col-xs-4">
                        <div class="small-box bg-teal">
                            <div class="inner">
                                <h3>AQL </h3>
                                <p>S c a n</p>
                            </div>
                            <div class="icon" style="margin-top:10px">
                                <i class="glyphicon glyphicon-barcode"></i>
                            </div>                                
                            <a href="{{route('aqlRotary.index')}}" class="small-box-footer btn {{ Auth::user()->can('aql_rotary_access_allow') ? 'active' : 'disabled' }}">
                                Go <i class="fa fa-arrow-circle-right"></i>
                            </a>
                        </div>
                    </div>
                @endpermission 

                @permission(('mrn_rotary_access_allow'))
                    <div class="col-lg-2 col-sm-4 col-xs-4">
                        <div class="small-box bg-olive">
                            <div class="inner">
                                <h3>MRN </h3>
                                <p>S c a n</p>
                            </div>
                            <div class="icon" style="margin-top:10px">
                                <i class="glyphicon glyphicon-print"></i>
                            </div>                                
                            <a href="{{route('mrnRotary.index')}}" class="small-box-footer btn {{ Auth::user()->can('mrn_rotary_access_allow') ? 'active' : 'disabled' }}">
                                Go <i class="fa fa-arrow-circle-right"></i>
                            </a>
                        </div>
                    </div>
                @endpermission 
            </div>
            <div class="clearfix"></div>
            <div  class="row">
                <div class="col-md-12 col-sm-12 col-xs-12">
                  
                </div>
            </div>           
        </div>      
    </div>    
  </div>      
</div>
<!-- 
<div class="row">
    <div class="center p1 white bg-black">.bg-black</div>
    <div class="center p1 bg-gray">.bg-gray</div>
    <div class="center p1 bg-silver">.bg-silver</div>
    <div class="center p1 bg-white">.bg-white</div>
    <div class="center p1 bg-aqua">.bg-aqua</div>
    <div class="center p1 bg-blue">.bg-blue</div>
    <div class="center p1 white bg-navy">.bg-navy</div>
    <div class="center p1 bg-teal">.bg-teal</div>
    <div class="center p1 bg-green">.bg-green</div>
    <div class="center p1 bg-olive">.bg-olive</div>
    <div class="center p1 bg-lime">.bg-lime</div>
    <div class="center p1 bg-yellow">.bg-yellow</div>
    <div class="center p1 bg-orange">.bg-orange</div>
    <div class="center p1 bg-red">.bg-red</div>
    <div class="center p1 bg-fuchsia">.bg-fuchsia</div>
    <div class="center p1 bg-purple">.bg-purple</div>
    <div class="center p1 white bg-maroon">.bg-maroon</div>
    <div class="center p1 bg-darken-1">.bg-darken-1</div>
    <div class="center p1 bg-darken-2">.bg-darken-2</div>
    <div class="center p1 bg-darken-3">.bg-darken-3</div>
    <div class="center p1 bg-darken-4">.bg-darken-4</div>
    <div class="bg-black">
      <div class="center p1 white bg-lighten-1">.bg-lighten-1</div>
      <div class="center p1 white bg-lighten-2">.bg-lighten-2</div>
      <div class="center p1 white bg-lighten-3">.bg-lighten-3</div>
      <div class="center p1 white bg-lighten-4">.bg-lighten-4</div>
    </div>
</div> -->