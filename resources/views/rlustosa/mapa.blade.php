<!DOCTYPE html>
<html>
<head>
    <title>Rlustosa - Início.</title>

    <!-- Latest compiled and minified CSS -->
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css"
          integrity="sha384-BVYiiSIFeK1dGmJRAkycuHAHRg32OmUcww7on3RYdg4Va+PmSTsz/K68vbdEjh4u" crossorigin="anonymous">

    <!-- Optional theme -->
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap-theme.min.css"
          integrity="sha384-rHyoN1iRsVXV4nD0JutlnGaslCJuC7uwjduW9SVrLvRYooPp2bWYgmgJQIXwl/Sp" crossorigin="anonymous">

    <!-- Latest compiled and minified JavaScript -->
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"
            integrity="sha384-Tc5IQib027qvyjSMfHjOMaLkfuWVxZxUPnCJA7l2mCWNIpG9mGCD8wGNIcPD7Txa"
            crossorigin="anonymous"></script>

    <style>
        body {
            background-color: #333;
            margin-top: 18px
        }

        .container {
            padding: 33px;
            background-color: #fff
        }

    </style>
</head>
<body>
<!-- Submitted March 7 @ 11:05pm  -->
<div class="container">
    <div class="row">
        <div class="col-md-12">
            <h3>
                Gerador de Arquivos - Rlustosa
            </h3>


            <!-- NOTE: TB3 form width default is 100%, so they expan to your <div> -->
            <form method="post" action="{{ url('/rlustosa/organizaVisual') }}" class="form-horizontal">
                <div class="row" style="margin-top: 20px"></div>

                {{ csrf_field() }}

                <label for="txtComment">Configure os campos abaixo:</label><br>
                @foreach($tableColumns as $field)

                    <div class="form-group">
                        <label for="label_{{ $field->getName() }}"
                               class="col-md-2">{{ $field->getName() }}
                            <small style="font-weight: normal"><br/>{{ $field->getType() }}</small>
                        </label>

                        <div class="col-sm-3">
                            <input name="label[{{ $field->getName() }}][nome]" type="text"
                                   class="form-control" id="label_{{ $field->getName() }}"
                                   value="{{ array_key_exists($field->getName(), $conversaoDeLabels) ? $conversaoDeLabels[$field->getName()] :ucfirst($field->getName()) }}">
                        </div>
                        <div class="col-sm-2">
                            <label style="font-weight: normal">
                                <input type="radio" checked name="label[{{ $field->getName() }}][exibir]" value="c_l">
                                Cadastro e Listagem
                            </label>
                        </div>
                        <div class="col-sm-2">
                            <label style="font-weight: normal">
                                <input type="radio" @if(in_array($field->getName(), $camposCadastro)) checked @endif name="label[{{ $field->getName() }}][exibir]" value="c">
                                Cadastro
                            </label>
                        </div>
                        <div class="col-sm-2">
                            <label style="font-weight: normal">
                                <input type="radio" @if(in_array($field->getName(), $camposListagem)) checked @endif name="label[{{ $field->getName() }}][exibir]" value="l">
                                Listagem
                            </label>
                        </div>
                        <div class="col-sm-1">
                            <label style="font-weight: normal">
                                <input type="radio" @if(in_array($field->getName(), $camposOcultos)) checked @endif name="label[{{ $field->getName() }}][exibir]" value="o">
                                Ocultar
                            </label>
                        </div>
                    </div>

                @endforeach

                <div class="row" style="margin-top: 80px"></div>

                <label for="txtComment">Nome da Classe</label>
                <input type="hidden" name="classe" id="classe" class="form-control" value="{{ $classe }}">
                {{ $classe }}
                <br><br>


                @foreach($fileTypes as $arquivo)
                    <div class="form-group @if($arquivo['existe'] == true) alert alert-danger @endif">
                        <label class="col-sm-3 control-label">
                            <input type="checkbox" @if($arquivo['existe'] == false) checked @endif name="arquivos[]"
                                   value="{{ json_encode($arquivo) }}" >
                            Gerar {{ $arquivo['tipo'] }}
                        </label>
                        <div class="col-sm-8">
                            {{ $arquivo['destino'] }}
                        </div>
                    </div>
                @endforeach


                <button type="submit" class="btn btn-success" value="Submit">Avançar</button>
                <button type="reset" class="btn btn-warning" value="Submit">Resetar</button>
                <a href="{{ url('/rlustosa') }}" class="btn btn-info">Começar Novamente</a>

            </form>
            <hr>

        </div><!--.col -->
    </div><!--./row -->
</div><!--./container -->
</body>
</html>
