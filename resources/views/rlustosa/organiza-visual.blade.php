<?php
$width = 300;
?>
        <!DOCTYPE html>
<html>
<head>
    <title>Rlustosa - Início.</title>

    <!-- Latest compiled and minified CSS -->
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css"
          integrity="sha384-BVYiiSIFeK1dGmJRAkycuHAHRg32OmUcww7on3RYdg4Va+PmSTsz/K68vbdEjh4u" crossorigin="anonymous">

    <script src="https://code.jquery.com/jquery-1.12.4.js"></script>
    <script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>

    <style>
        body {
            background-color: #333;
            margin-top: 18px
        }

        .container {
            padding: 33px;
            background-color: #fff
        }

        .no-resizable {
            width: <?php echo $width * 4?>px;
            height: 20px;
            padding: 0;
            margin: 0;
            float: left;
            border: 1px dashed;
            /*background-color: #1e2e3d !important;*/
        }

        .resizable {
            width: <?php echo $width?>px;
            height: 50px;
            padding: 0;
            margin: 0;
            float: left
        }

        .resizable h3 {
            text-align: center;
            margin: 0;
            padding: 0;
            font-size: 15px
        }

        #sortable {
            list-style-type: none;
            margin: 0;
            padding: 0;
            /*width: 450px;*/
        }

        #sortable li {
            margin: 15px 0 15px 0;
            /*padding: 1px;
            float: left;
            width: 100px;
            height: 90px;
            font-size: 4em;*/
            text-align: center;
        }

        .campo {
            width: 80px;
        }

    </style>

    <link rel="stylesheet" href="//code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">

    <script>
        $(function () {

            $("#sortable").sortable();
            $("#sortable").disableSelection();

            $(".resizable").resizable({
                maxHeight: 50,
                minHeight: 50,
                maxWidth: 1200,
                minWidth: <?php echo $width?>-10,
                grid: [<?php echo $width / 3?>, 10],
                stop: function (event, ui) {
                    largura = $(this).width() / 100;
                    id = "#" + $(this).attr('id');
                    $(id + " :input").val(largura);
                    $(id).height(48);
                    $(id + "_lbl").html(largura);
                    console.log(id + " :input " + id + "_lbl = " + largura + " -- " + $(this).width());
                }
            });

            $('.minus').on('click', function () {
                idli = $(this).attr('idli');
                $('#' + idli).remove();
            });
            $('.plus').on('click', function () {
                randomString = randomString(10);
                $("#sortable").append('<li class="ui-state-default no-resizable ui-widget-content"' +
                        'id="li_' + randomString + '">' +
                        '<a href="javascript:;" class="minus" idli="li_' + randomString + '">' +
                        '<span class="glyphicon glyphicon-minus"></span>' +
                        '</a>' +
                        '<input type="hidden" class="form-control campo"' +
                        'name="campo[divisor__' + randomString + ']"' +
                        'value="12">' +
                        '</li>');
            });


            function randomString(length) {
                chars = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
                var result = '';
                for (var i = length; i > 0; --i) result += chars[Math.floor(Math.random() * chars.length)];
                return result;
            }

        });
    </script>
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
            <form method="post" action="{{ url('/rlustosa/gera') }}" class="form-horizontal">
                <div class="row" style="margin-top: 20px"></div>

                {{ csrf_field() }}

                <label for="txtComment">Posicione os campos abaixo:</label><br>
                <div style="width: 1225px; min-height: 500px; float: left; background-color: #fafafa">
                    <ul id="sortable">
                        @foreach($campos_disponiveis as $campo)
                            <li class="ui-state-default resizable ui-widget-content"
                                id="li_<?= $campo['nome_campo'] ?>">
                                <div class="form-group">
                                    <label class="col-sm-4 control-label"
                                           id="li_<?= $campo['nome_campo'] ?>_lbl"><?php echo $width / 100 ?><? $campo['label_campo'] ?></label>
                                    <div class="col-sm-8">
                                        <input type="hidden" class="form-control campo"
                                               id="campo_<?= $campo['nome_campo'] ?>"
                                               name="campo[<?= $campo['nome_campo'] ?>]"
                                               value="<?php echo $width / 100 ?>">
                                        <small>
                                            <?= $campo['nome_campo'] ?>
                                        </small>
                                    </div>
                                </div>
                            </li>
                        @endforeach

                        @for($x=1; $x<=10; $x++)
                            <li class="ui-state-default no-resizable ui-widget-content"
                                id="li_<?= $x ?>">
                                <a href="javascript:;" class="minus" idli="li_<?= $x ?>">
                                    <span class="glyphicon glyphicon-minus"></span>
                                </a>
                                <input type="hidden" class="form-control campo"
                                       name="campo[divisor__<?= $x ?>]"
                                       value="12">
                            </li>
                        @endfor


                        <a href="javascript:;" class="plus">
                            <span class="glyphicon glyphicon-plus"></span>
                        </a>

                    </ul>
                </div>

                <br/>
                <div class="row" style="margin-top: 80px"></div>

                <label for="txtComment">Nome da Classe</label>
                <input type="hidden" name="classe" id="classe" class="form-control" value="{{ $classe }}">
                {{ $classe }}
                <br><br>


                @foreach($arquivos as $arquivo)
                    @php
                    $arquivo = json_decode($arquivo);
                    @endphp
                    <div class="form-group @if($arquivo->existe == true) alert alert-danger @endif">
                        <label class="col-sm-3 control-label">
                            {{ $arquivo->tipo }}
                        </label>
                        <div class="col-sm-8">
                            {{ $arquivo->destino }}
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
