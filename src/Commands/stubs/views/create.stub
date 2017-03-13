@extends ('backend.layouts.app')

@section ('title', isset($title) ? $title : 'Title')

@section('after-styles')

@stop

@section('page-header')
    <h1>
        {!! isset($title) ? $title : 'Title' !!}
        <small>{{ isset($subtitle) ? $subtitle : 'Subtitle' }}</small>
    </h1>
@endsection

@section('content')

        <div class="box box-success">
            <div class="box-header with-border">
                <h3 class="box-title">{{isset($title) ? $title : 'Title'}}</h3>

                <div class="box-tools pull-right">
                    <div class="pull-right mb-10 hidden-sm hidden-xs">
                        {{-- @foreach ($box_link as $element)
                            {{$element}}
                        @endforeach --}}
                    </div><!--pull right-->
                </div><!--box-tools pull-right-->
            </div><!-- /.box-header -->
            <div class="box-body">

                <div class="col-xs-12">
                {{Form::model($model, ['route' => [$storeRoute, $model->id],'class' => 'form-horizontal', 'role' => 'form', 'method' => 'POST', 'id' => '$LOWER_NAME$-edit'])}}

                @foreach ($fields as $element)
                {{ Form::label($element, $element, ['class' => 'form-label']) }}<br>
                {{ Form::text($element,$model->$element, ['class' => 'form-control']) }}<br>
                @endforeach

                {{Form::submit(__('Create'))}}
                {{Form::close()}}
                </div>

            </div><!-- /.box-body -->
        </div><!--box-->
@stop

@section('after-scripts')
    @parent
@stop
