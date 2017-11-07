@extends('layouts.master')
@section('content')
<section>
  <div class="col-sm-9 col-sm-offset-3 col-lg-10 col-lg-offset-2 main">
    <div class="row">
      <ol class="breadcrumb">
         <i class="pe-7s-mail-open pe-lg"></i> {{ trans('menus.contact_us') }}
      </ol>
    </div><!--/.row-->
    <div class="row">
      <div class="col-xs-12 col-md-12 col-lg-12">
        <div class="panel panel-default">
          <div class="panel-heading"><center><b>{{ trans('menus.contact_us') }} </b></center> </div>
            <br/>
             
            	 <div class="row"> 
                <div class="col-md-2 col-lg-2"></div>
                <div class="col-md-8 col-lg-8">
           
                <ul>

				@if(session('message'))
				<div class="alert alert-success ">
				<a href="#" class="close" data-dismiss="alert" aria-label="close" &time;></a>
				{{ session('message') }}
				</div>
				@endif
				
				@foreach($errors->all() as $error)
				        <li>{{ $error }}</li>
				    @endforeach

				</ul>
				
				{!! Form::open(array('class' => 'form','method'=>'post','action' =>'ContactController@postContact')) !!}
				
				{{ csrf_field() }}

				<div class="form-group">
				    {!! Form::label('Your Name') !!}
				    {!! Form::text('name', null, 
				        array('required', 
				              'class'=>'form-control', 
				              'placeholder'=>'Your name')) !!}
				</div>

				<div class="form-group">
				    {!! Form::label('Your E-mail Address') !!}
				    {!! Form::text('email', null, 
				        array('required', 
				              'class'=>'form-control', 
				              'placeholder'=>'Your e-mail address')) !!}
				</div>

				<div class="form-group">
				    {!! Form::label('Your Message') !!}
				    {!! Form::textarea('message', null, 
				        array('required', 
				              'class'=>'form-control', 
				              'placeholder'=>'Your message')) !!}
				</div>

				<div class="form-group">
				    {!! Form::submit('Contact Us!', 
				      array('class'=>'btn btn-primary')) !!}
				</div>
			{!! Form::close() !!}

				</div>
				<div class="col-md-2 col-lg-2" ></div>
				</div> <!--  close div content form -->
				</div>
            </div><!-- \ panel panel-body -->
        </div><!-- \ panel panel-default -->
   
    </div><!--/.row-->
  </div>	<!--/.main-->
</section>

@endsection
