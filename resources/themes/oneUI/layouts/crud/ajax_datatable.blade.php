<div class="row">
    <div class="col-md-12">
        @if(!empty($dataTable->filters()))
            <div class="pull-right float-right">
                {!! CoralsForm::link('#'.$dataTable->getTableAttributes()['id'].'_filtersCollapse','<i class="fa fa-filter"></i>',['class'=>'btn btn-info','data'=>['toggle'=>"collapse"]]) !!}
            </div>
            <div id="{{ $dataTable->getTableAttributes()['id'] }}_filtersCollapse"
                 class="filtersCollapse collapse">
                <br/>
                {!! $dataTable->filters() !!}
            </div>
            <div class="clearfix"></div>
        @endif
    </div>
</div>
<div class="row">
    <div class="col-md-12">
        <div class="table-responsive"
             style="min-height: 200px;padding-bottom: 100px;margin-top: 10px;">
            {!! $dataTable->table(['class' => 'table table-hover table-striped table-condensed dataTableBuilder','style'=>'width:100%;']) !!}
        </div>
    </div>
</div>




{!! $dataTable->assets() !!}
{!! $dataTable->scripts() !!}
