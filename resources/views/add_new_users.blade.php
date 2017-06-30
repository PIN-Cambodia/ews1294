@extends('layouts.master')
@section('content')
<section id="register">
  <div class="col-sm-9 col-sm-offset-3 col-lg-10 col-lg-offset-2 main">
    <div class="row">
      <ol class="breadcrumb">
        <li><a href="#"><svg class="glyph stroked home"><use xlink:href="#stroked-home"></use></svg></a></li>
        <li class="active"> {{ trans('auth.register') }} </li>
      </ol>
    </div><!--/.row-->
    <div class="row">
      <div class="col-xs-12 col-md-12 col-lg-12">
        <div class="panel panel-default">
            <div style="height:40%; overflow-y: scroll; overflow-x: hidden;">
            <div class="row">
                <div class="col-md-2 col-lg-3"></div>
                <div class="col-md-8 col-lg-5">
                  
                </div>
                <div class="col-md-2 col-lg-4"></div>
            </div>
          <div class="panel-body">
          
              <div  class="col-xs-2 col-md-2 col-lg-2">
             
                
              </div>
              <div class=" col-md-8 col-xs-8 col-lg-8">
                  <form class="form" method="POST" action="postPhoneNumber">
                  @if(session('message'))
                    <div class="alert alert-success ">
                    <a href="#" class="close" data-dismiss="alert" aria-label="close" &time;></a>
                    {{ session('message') }}
                    </div>
                    @endif
                  {{ csrf_field() }}
         
                      <div class="col-xs-2 col-md-2 col-lg-2">
                          {{ trans('auth.phone') }}
                      </div>
                      <div class="col-xs-8 col-md-8 col-lg-8">
                          <input id="phone" type="text" class="form-control" name="phone">
                          {{$errors->first('phone')}}
                      </div>
                <div class="row">
                                    <div class="col-xs-6 col-md-6 col-lg-6">
                                        <div class="row topspace">
                                            <div class="col-xs-4 col-md-4 col-lg-4">
                                                {{ trans('pages.province_:') }}
                                            </div>
                                            <div class="col-xs-8 col-md-8 col-lg-8">
                                                <select name="province" id="province">
                                                      <option value="AllProvinces"> {{ trans('pages.all_provinces') }} </option>
                                                   
                                                    @foreach ($provinces as $item)
                                                        @if (App::getLocale()=='km')
                                                            <option value="{{ $item->PROCODE }}" name"province">{{ $item->PROVINCE_KH }}</option>
                                                        @else
                                                            <option value="{{ $item->PROCODE }}" name"province">{{ $item->PROVINCE }}</option>
                                                        @endif
                                                    @endforeach
                                                    
                                                </select>
                                                {{$errors->first('province')}}
                                            </div>

                                      
                                      <div class="row topspace district">
                                       <dir class="row">
                                          <div class="col-xs-12 col-md-12 col-lg-12">
                                            <div  style="text-align: left">
                                            Districts :
                                            </div>
                                              <div>
                                          <select id="selectdistricts" name="district">
                                            <option>All District</option>
                                          </select>
                                        </div>
                                         </div><!-- /.row -->
                                      
                                        <div>
                                          <div class="col-xs-12 col-md-12 col-lg-12">
                                            <div  style="text-align: left">
                                              Communes :
                                            </div>
                                          <select id="selectcommune" name="percommune">
                                            <option>All Commune</option>
                                            
                                          </select>
                                        </div>
                                       
                                      </div>
                                    </div>
      
                          <button type="submit" class="btn btn-primary" name="save">
                              <i class="fa fa-btn fa-user"></i> {{ trans('auth.register') }}
                          </button>
              </form>
          </div><!--/.panel panel-body-->
      </div><!--/.panel panel-default-->
      </div>
    </div><!--/.row-->
    </div>
  </div>	<!--/.main-->

</section>
@endsection
