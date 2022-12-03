@extends('templates.admin.layout')

@section('content')
<div class="">
    <div class="clearfix"></div>
    <div class="row">
        <div class="col-md-12 col-sm-12 col-xs-12">
            <div class="x_panel">
                <div class="x_title">
                    <h2><a href="{{route('labelreferences.index')}}" class="btn btn-info btn-xs"><i class="fa fa-chevron-left"></i> @lang('general.nav.back') </a>@lang('reference.edit_item_references') </h2>
                    <div class="clearfix"></div>
                </div>
                <div class="x_content">
                    <br />
                    <form method="post" action="{{ route('labelreferences.update_item_references', ['id' => $refObj->id]) }}" data-parsley-validate class="form-horizontal form-label-left">
                        <div class="row">
                            <div class ="col-md-4">
                                <div class="form-group{{ $errors->has('name') ? ' has-error' : '' }}">
                                    <label class="control-label col-md-3 col-sm-3 col-xs-3" for="name">@lang('general.common.name') 
                                    </label>
                                    <div class="col-md-8 col-sm-8 col-xs-8">
                                        <input type="text" value="{{ $refObj->name }}" id="name" name="name" class="form-control col-md-8 col-xs-8" disabled>
                                    </div>
                                </div>  
                            </div>                           

                            <div class ="col-md-8">
                                <div class="form-group{{ $errors->has('description') ? ' has-error' : '' }}">
                                    <label class="control-label col-md-3 col-sm-3 col-xs-3" for="description">@lang('general.common.description')
                                    </label>
                                    <div class="col-md-8 col-sm-8 col-xs-8">
                                        <input type="text" value="{{$refObj->description}}" id="description" name="description" class="form-control col-md-7 col-xs-12" disabled>                                      
                                    </div>
                                </div>
                            </div>

                            
                        </div>    

                        <div class="row">
                            <div class="col-md-12"> 
                                <div class="x_panel">
                                     <div class="form-group{{ $errors->has('total_amount') ? ' has-error' : '' }}">
                                        <div class="col-md-12 col-sm-12 col-xs-12">
                                            <input type="text" style="font-size: 50px; padding: 12px 20px; margin: 8px 0; box-sizing: border-box; border: none; background-color: #3CBC8D; color: white;" value="" id="total_amount" name="total_amount" class="form-control text-right input-lg">                                            
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <table class="table table-bordered" id="table_bom" >
                                            <thead>
                                                <tr>
                                                    <th>Item</th>
                                                    <th>Unit</th>
                                                    <th>Price</th>
                                                    <th>Quantity</th>
                                                    <th>Value</th>
                                                    <th style="text-align: center;background: #eee"><button type="button" name="addRow" id="addRow" class="btn btn-success">+</button></th>                                                                     
                                                </tr>
                                            </thead>

                                            <tfoot>
                                                <tr>
                                                    <th style="border: none;"></th>
                                                    <th style="border: none;"></th>
                                                    <th style="border: none;"></th>
                                                    <th style="border: none;"></th>
                                                    <th style="border: none;"></th>
                                                    <th style="border: none;"></th>                                                                     
                                                </tr>
                                            </tfoot>
                                            <tbody>
                                                @if (count($refObj->itemLabelReferences))                                                   
                                                    @foreach($refObj->itemLabelReferences as $row)                                                        
                                                        <tr>                                       
                                                            <td>
                                                                <div>
                                                                    <input type="hidden" value="{{ $row->id }}" id="item_labelreferences_id" name="item_labelreferences_id[]" >
                                                                    <select class="js-example-basic-single form-control" id="item_id{{$loop->index}}" name="item_id[]" onchange="getItemDetails({{$loop->index}})" required>
                                                                        @if(count($items))
                                                                            @foreach($items as $crow)
                                                                                <option value="{{ $crow->id}}" {{$crow->id ==  $row->item_id ? 'selected="selected"' : ''}}">{{ $crow->name }}</option>
                                                                            @endforeach
                                                                        @endif
                                                                    </select>
                                                                </div>
                                                            </td> 
                                                            <td>
                                                                <input type="text" value="{{$row->items->units['name']}}" id="unit_id{{$loop->index}}" name="unit_id[]" class="form-control unit_id" disabled>
                                                            </td>                                                            
                                                            <td>
                                                                <input type="text" value="{{$row->unit_price}}" name="unit_price[]" class="form-control" id="unit_price{{$loop->index}}" onkeyup="getCalItemValue({{$loop->index}})" required>
                                                            </td>
                                                            <td>
                                                                <input type="text" value="{{$row->quantity}}" name="quantity[]" class="form-control" id="quantity{{$loop->index}}" onkeyup="getCalItemValue({{$loop->index}})" required >
                                                            </td>
                                                            <td>
                                                                <input type="text" value="{{$row->unit_value}}" name="item_value[]" class="form-control text-right item_value" id="item_value{{$loop->index}}" >                                                             
                                                            </td>

                                                            <td><button type="button" name="btn_remove" id="btn_remove" class="btn btn-danger btn_remove">X</button></td>
                                                        </tr>
                                                     @endforeach
                                                @else
                                                    <tr>
                                                        <td>
                                                            <div class="form-group{{ $errors->has('item_id')? 'has-error' : ''}}">
                                                                <input type="hidden" id="item_labelreferences_id" name="item_labelreferences_id[]" >
                                                                <select class="js-example-basic-single form-control" id="item_id0" name="item_id[]" onchange="getItemDetails(0)" required>
                                                                    @if(count($items))
                                                                        <option value=""></option>
                                                                        @foreach($items as $row)
                                                                            <option value="{{$row->id}}">{{$row->code}} - {{ $row->name }} </option>
                                                                        @endforeach
                                                                    @endif
                                                                </select>
                                                            </div>
                                                        </td>
                                                        <td>
                                                            <input type="text" name="unit_id[]" id="unit_id0" class="form-control unit_id" disabled>
                                                        </td>

                                                        <td>
                                                            <input type="text" name="unit_price[]" class="form-control" id="unit_price0" onkeyup="getCalItemValue(0)" required>
                                                        </td>
                                                        
                                                        <td>
                                                            <input type="text" name="quantity[]" class="form-control" id="quantity0" onkeyup="getCalItemValue(0)" required >
                                                        </td>
                                                        <td>
                                                            <input type="text" name="item_value[]" class="form-control text-right item_value" id="item_value0" >
                                                        </td> 
                                                        <td>
                                                            <button type="button" name="btn_remove" id="btn_remove" class="btn btn-danger btn_remove">X</button>
                                                        </td>
                                                    </tr>

                                                @endif
                                            </tbody>
                                        </table>
                                    </div> 

                                </div>
                            </div>
                        </div>
                       
                        <div class="ln_solid"></div>

                        <div class="form-group">
                            <div class="col-md-6 col-sm-6 col-xs-6">
                                <input type="hidden" name="_token" value="{{ Session::token() }}">                              
                                <button type="submit" id ="update_item_references"class="btn btn-success">@lang('general.form.save_changes')</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@stop
@section ('js')
<script> 
    function getItemDetails(i) {

        var item_id = document.getElementById("item_id"+i).value;
        
        $.get('{{ url('getItemDetails') }}/'+item_id, function(data){ 
           
            $unit_id=data[0]["unit_name"];
            $unit_price=0;

            //var stylesmv = document.getElementById("style_smv").value;
            var unit_id = document.getElementById("unit_id"+i);            
            var unit_price = document.getElementById("unit_price"+i); 
            var quantity = document.getElementById("quantity"+i);               
            var item_value = document.getElementById("item_value"+i);          
    
            var a = parseFloat($unit_price * 1);
            
            item_value.value = parseFloat(a).toFixed(2);
            unit_id.value = $unit_id;       
            unit_price.value = 0; 
            quantity.value = 0;            
           
            if(i==0){
                document.getElementById("total_amount").value=0;
            }else{
            var total_amount = document.getElementById("total_amount");
                var tAmount = parseFloat(document.getElementById("total_amount").value);
                var c  = (+tAmount) + (+a);            
                total_amount.value = parseFloat(c).toFixed(2); 
            }

        });

    }

    function getCalItemValue(j){
       
        var quantity = document.getElementById("quantity"+j).value;
        var unit_price = document.getElementById("unit_price"+j).value;            
        var item_value = document.getElementById("item_value"+j); 

        var a = parseFloat(unit_price * quantity);           
        item_value.value = parseFloat(a).toFixed(2);   
        
        var arr = document.getElementsByClassName('item_value');
        //alert(arr.length);
        var tot=0;
        for(var i=0;i<arr.length;i++){       
            if(parseFloat(arr[i].value))
                tot += parseFloat(arr[i].value);            
        }   
        var total_amount = document.getElementById("total_amount");
        total_amount.value = parseFloat(tot).toFixed(2);
        
    }

    $(document).ready(function() { 
        var i={{count($refObj->itemLabelReferences)}}; 
        var arr = document.getElementsByClassName('item_value');
        var tot=0;
        for(var i=0;i<arr.length;i++){       
            if(parseFloat(arr[i].value))
                tot += parseFloat(arr[i].value);            
        }   
        var total_amount = document.getElementById("total_amount");
        total_amount.value = parseFloat(tot).toFixed(2);

        $(".js-example-basic-single").select2();        
        
        $('#addRow').click(function(){            
            i++;                     
            $('#table_bom').append('<tr i="row'+i+'">'+
                                    '<td>'+
                                    '<div class="form-group{{ $errors->has('item_id.+i+')? 'has-error' : ''}}">'+
                                    '<select class="js-example-basic-single form-control" id="item_id'+i+'" name="item_id[]" onchange="getItemDetails('+i+')" required>'+
                                    '@if(count($items))'+
                                    '<option value=""></option>'+
                                    '@foreach($items as $row)'+
                                    '<option value="{{$row->id}}">{{$row->code}} / {{$row->name}}</option>'+
                                    '@endforeach'+
                                    '@endif'+
                                    '</select>'+
                                    '</div>'+
                                    '</td>'+                                    
                                    '<td>'+
                                    '<input type="text" name="unit_id[]" class="form-control" id="unit_id'+i+'" disabled>'+
                                    '@if ($errors->has('unit_id.'.'+i+'))'+
                                    '<span class="help-block">{{ $errors->first('unit_id.'.'+i+') }}</span>'+
                                    '@endif'+
                                    '</td>'+
                                    '<td>'+
                                    '<input type="text" name="unit_price[]" class="form-control" id="unit_price'+i+'" onkeyup="getCalItemValue('+i+')" required>'+
                                    '@if ($errors->has('unit_price'))'+
                                    '<span class="help-block">{{ $errors->first('unit_price') }}</span>'+
                                    '@endif'+
                                    '</td>'+
                                    '<td>'+
                                    '<input type="text" name="quantity[]" class="form-control" id="quantity'+i+'" onkeyup="getCalItemValue('+i+')" required >'+
                                    '@if ($errors->has('quantity.+i+'))'+
                                    '<span class="help-block">{{ $errors->first('quantity.+i+') }}</span>'+
                                    '@endif'+
                                    '</td>'+
                                    '<td>'+
                                    '<input type="text" name="item_value[]" class="form-control text-right item_value" id="item_value'+i+'" >'+
                                    '@if ($errors->has('item_value+i+'))'+
                                    '<span class="help-block">{{ $errors->first('item_value+i+') }}</span>'+
                                    '@endif'+
                                    '</td>'+
                                    '<td>'+
                                    '<button type="button" name="btn_remove" id="btn_remove" class="btn btn-danger btn_remove">X</button>'+
                                    '</td>'+
                                    '</tr>');  

                $(".js-example-basic-single").select2();                  
               
        });
        
        $(document).on('click','.btn_remove',function(){
            $(this).parent().parent().remove();
           
            var arr = document.getElementsByClassName('item_value');
            var tot=0;
            for(var i=0;i<arr.length;i++){       
                if(parseFloat(arr[i].value))
                    tot += parseFloat(arr[i].value);            
            }   
            var total_amount = document.getElementById("total_amount");
            total_amount.value = parseFloat(tot).toFixed(2);
        });
    }); 
</script>
@stop