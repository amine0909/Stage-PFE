@extends('layouts.app')
@section('content')
<div class="container">
    <div class="col-md-6 col-md-offset-3">
        <div class="row">
          @forelse($errors->all() as $error)
           <div class="alert alert-danger">
               {{$error}}
           </div>
          @empty
          @endforelse
            {!!Form::open(["method"=>"POST","url"=>"/internships/store","class"=>"formcreateinternship"])!!}
              <div class="form-group">
               {{Form::label('start_date',"Date Début")}}
               {{Form::date('start_date','',['id'=>"start_date","class"=>"form-control"])}}
              </div>
              <div class="form-group">
                {{Form::label('end_date','Date Fin')}}
                {{Form::date('end_date','',['id'=>'end_date','class'=>"form-control"])}}
              </div>
              <div class="form-group">
                  {{Form::label('type','Type')}}
                  {{Form::select('type',(array)auth()->user()->LegalIntershipsTypes,null,['id'=>"type",'class'=>'form-control','placeholder'=>'type de stage'])}}
              </div>
              <div class="form-group">
                  {{Form::label('framer','Encadreur Etablissement')}}
                  @if(isset($teachers))
                  {{Form::select('framer',(array)$teachers,null,['id'=>'framer','class'=>'form-control'])}}
                  @endif
              </div>
            <div class="form-group">
                    @if(Session::has('cm'))
                    {{Form::hidden('cm',Session::get('cm'))}}
                    @endif
            </div>
            {{Form::submit('Enregistrer',['class'=>'btn btn-default'])}}
            {!!Form::close()!!}
        </div>
    </div>
</div>
@endsection