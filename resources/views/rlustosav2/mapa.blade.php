@extends('rlustosa._master')

@section('content')
    <!-- NOTE: TB3 form width default is 100%, so they expan to your <div> -->
    <form method="post" action="{{ url('/rlustosa/organizaVisual') }}" class="form-horizontal">
        <div class="row">
            <div class="col-12">
                {{ csrf_field() }}
                <div class="text-uppercase font-weight-bolder mb-4 mt-4">
                    Configure os campos abaixo:
                </div>
            </div>
        </div>
        @foreach($tableColumns as $field)

            <div class="form-row">
                <div class="form-group col-md-2">
                    <label for=" label_{{ $field['name'] }}" class="col-md-2">{{ $field['name'] }}
                        <small style="font-weight: normal"><br/>{{ $field['type'] }}</small>
                    </label>
                </div>

                <div class="col-sm-3">
                    <input name="label[{{ $field['name']  }}][nome]" type="text"
                           class="form-control" id="label_{{ $field['name']  }}"
                           value="{{ array_key_exists($field['name'] , $conversaoDeLabels) ? $conversaoDeLabels[$field['name'] ] :ucfirst($field['name'] ) }}">
                </div>
                <div class="col-sm-2">
                    <label style="font-weight: normal">
                        <input type="radio" checked name="label[{{ $field['name']  }}][exibir]" value="c_l">
                        Cadastro e Listagem
                    </label>
                </div>
                <div class="col-sm-2">
                    <label style="font-weight: normal">
                        <input type="radio" @if(in_array($field['name'] , $camposCadastro)) checked
                               @endif name="label[{{ $field['name']  }}][exibir]" value="c">
                        Cadastro
                    </label>
                </div>
                <div class="col-sm-2">
                    <label style="font-weight: normal">
                        <input type="radio" @if(in_array($field['name'] , $camposListagem)) checked
                               @endif name="label[{{ $field['name']  }}][exibir]" value="l">
                        Listagem
                    </label>
                </div>
                <div class="col-sm-1">
                    <label style="font-weight: normal">
                        <input type="radio" @if(in_array($field['name'] , $camposOcultos)) checked
                               @endif name="label[{{ $field['name']  }}][exibir]" value="o">
                        Ocultar
                    </label>
                </div>
            </div>

        @endforeach


        <div class="col-12">
            <div class="text-uppercase font-weight-bolder mb-4 mt-4">
                Nome da Classe: {{ $className }}
            </div>

            @foreach($fileTypes as $file)
                <div class="form-group alert {{ $file['existe'] == true ? 'alert-danger' : 'alert-info'}}">
                    <label class="col-sm-3 control-label">
                        <input type="checkbox" @if($file['existe'] == false) checked @endif name="arquivos[]"
                               value="{{ json_encode($file) }}">
                        Gerar {{ $file['tipo'] }}
                    </label>
                    <div class="col-sm-8">
                        {{ $file['destino'] }}
                    </div>
                </div>
            @endforeach

            <input type="hidden" name="table" id="table" class="form-control" value="{{ $tableName }}">
            <button type="submit" class="btn btn-success" value="Submit">Avançar</button>
            <button type="reset" class="btn btn-warning" value="Submit">Resetar</button>
            <a href="{{ url('/rlustosa') }}" class="btn btn-info">Começar Novamente</a>
        </div>

    </form>
@endsection

@section('styles')

@endsection

@section('scripts')
    <script>
        $(function () {

        });
    </script>
@endsection
