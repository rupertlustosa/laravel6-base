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

    <!-- Placed at the end of the document so the pages load faster -->
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.12.4/jquery.min.js"></script>

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
            <form method="post" action="{{ url('/rlustosa') }}" autocomplete="off">
                <div class="row" style="margin-top: 20px"></div>

                {{ csrf_field() }}

                <label for="txtComment">Qual tabela você deseja gerar os arquivos?</label><br>
                @foreach($allTables as $table)
                    <label class="col-md-4" style="font-weight: normal">
                        <input type="radio"
                               classe="{{ (isset($mapeamento[$table->getName()]) ? $mapeamento[$table->getName()]['classe'] : ucfirst(camel_case($table->getName()))) }}"
                               class="radio-tabela"
                               name="tabela"
                               id="table_{{ $table->getName() }}"
                               value="{{ $table->getName() }}"
                               required> {{ $table->getName() }}
                    </label>
                @endforeach

                <div class="row" style="margin-top: 80px"></div>

                <label for="txtComment">Qual o nome das classes?</label>
                <input type="text"
                       name="classe"
                       id="classe"
                       class="form-control"
                       placeholder="Use no singular e em formato CamelCase Ex: Pessoa" required>
                <br><br>
                <button type="submit" class="btn btn-success" value="Submit">Avançar</button>

            </form>
            <hr>

        </div><!--.col -->
    </div><!--./row -->
</div><!--./container -->
<script>
    $(function () {
        $('.radio-tabela').on('click', function () {
            $('#classe').val($(this).attr('classe'));
        });
    });
</script>
</body>
</html>
