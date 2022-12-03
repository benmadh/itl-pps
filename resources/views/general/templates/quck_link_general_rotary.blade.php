<div class="row">
    <div class="col-md-12 col-sm-12 col-xs-12">
        <div class="x_panel">
            <div class="x_title">
                <h2>Quick Links - General </h2>
                <div class="clearfix"></div>
            </div>
            <div class="form-group">
                <div class="row">
                    <div class="col-md-12 col-sm-12 col-xs-12">
                        <div class="col-lg-3 col-sm-4 col-xs-6">
                            <div class="small-box bg-olive">
                                <div class="inner">
                                    <h3>Tracking Details</h3>
                                    <p>S e a r c h</p>
                                </div>
                                <div class="icon" style="margin-top:10px">
                                    <i class="glyphicon glyphicon-search"></i>
                                </div>
                                <a href="{{route('ordertracking.index')}}" class="small-box-footer btn">
                                    Go <i class="fa fa-arrow-circle-right"></i>
                                </a>
                            </div>
                        </div>
                        
                        <div class="col-lg-3 col-sm-3 col-xs-3">
                            <div class="small-box bg-yellow">
                                <div class="inner">
                                    <h3>Same as WO</h3>
                                    <p>A t o m a t i o n</p>
                                </div>
                                <div class="icon" style="margin-top:10px">
                                    <i class="glyphicon glyphicon-th-list"></i>
                                </div>
                                <a href="{{route('sameasauto.index')}}"
                                    class="small-box-footer btn {{ Auth::user()->can('sameas_auto_access_allow') ? 'active' : 'disabled' }}">
                                    Go <i class="fa fa-arrow-circle-right"></i>
                                </a>

                            </div>
                        </div>
                        
                        
                        <div class="col-lg-2 col-sm-4 col-xs-6">
                            <div class="small-box bg-gray">
                                <div class="inner">
                                    <h3>Planning </h3>
                                    <p>S c a n</p>
                                </div>
                                <div class="icon" style="margin-top:10px">
                                    <i class="glyphicon glyphicon-barcode"></i>
                                </div>
                                <a href="{{route('ordtrcpln.index')}}"
                                    class="small-box-footer btn {{ Auth::user()->can('order_tracking_plan_access_allow') ? 'active' : 'disabled' }}">
                                    Go <i class="fa fa-arrow-circle-right"></i>
                                </a>
                            </div>
                        </div>
                        
                        
                        <div class="col-lg-2 col-sm-4 col-xs-6">
                            <div class="small-box bg-teal">
                                <div class="inner">
                                    <h3>Designing </h3>
                                    <p>S c a n</p>
                                </div>
                                <div class="icon" style="margin-top:10px">
                                    <i class="glyphicon glyphicon-barcode"></i>
                                </div>
                                <a href="{{route('ordtrcpre.index')}}"
                                    class="small-box-footer btn {{ Auth::user()->can('order_tracking_designing_access_allow') ? 'active' : 'disabled' }}">
                                    Go <i class="fa fa-arrow-circle-right"></i>
                                </a>
                            </div>
                        </div>
                        

                        <div class="col-lg-3 col-sm-4 col-xs-6">
                            <div class="small-box bg-gray">
                                <div class="inner">
                                    <h3>Plate Room </h3>
                                    <p>S c a n</p>
                                </div>
                                <div class="icon" style="margin-top:10px">
                                    <i class="glyphicon glyphicon-barcode"></i>
                                </div>
                                <a href="{{route('ordtrcplt.index')}}"
                                    class="small-box-footer btn {{ Auth::user()->can('order_tracking_plate_room_access_allow') ? 'active' : 'disabled' }}">
                                    Go <i class="fa fa-arrow-circle-right"></i>
                                </a>
                            </div>
                        </div>
        
                    </div>
                </div>
                <div class="clearfix"></div>
            </div>
        </div>
    </div>
</div>