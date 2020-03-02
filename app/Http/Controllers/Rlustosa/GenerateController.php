<?php

namespace App\Http\Controllers\Rlustosa;

use App\Http\Controllers\Controller;
use DB;
use Exception;
use File;
use Illuminate\Filesystem\Filesystem;
use Illuminate\Support\Str;
use Request;
use Schema;

class GenerateController extends Controller
{

    protected $driver;
    protected $database;
    protected $username;
    protected $password;
    protected $table;
    protected $classe;
    protected $fileTypes;
    protected $camposIndex;
    private $filesystem;
    private $files;
    private $schemas;
    private $fieldsText = ['content', 'notes', 'body'];
    private $fieldsNumber = ['tinyint', 'smallint', 'mediumint', 'int', 'bigint'];

    public function __construct()
    {

        $this->files = new Filesystem();
        $this->filesystem = new Filesystem();
        $this->driver = DB::connection()->getConfig('driver');
        $this->database = DB::connection()->getConfig('database');
        $this->username = DB::connection()->getConfig('username');
        $this->password = DB::connection()->getConfig('password');
        DB::getDoctrineSchemaManager()->getDatabasePlatform()->registerDoctrineTypeMapping('enum', 'string');
        $this->classe = request('classe') ? request('classe') : null;

        $schemas = null;
        $databaseMapping = __DIR__ . "/databaseMapping.php";

        $this->gerardatabaseMapping($databaseMapping);

        require_once __DIR__ . "/databaseMapping.php";

        $this->schemas = $schemas;

        $this->fileTypes = [
            [
                'namespace' => 'Models',
                'caminho' => app_path() . DIRECTORY_SEPARATOR . 'Models' . DIRECTORY_SEPARATOR . '__CLASSE__.php',
            ],
            [
                'namespace' => 'Http/Controllers/Panel',
                'caminho' => app_path() . DIRECTORY_SEPARATOR . 'Http/Controllers/Panel' . DIRECTORY_SEPARATOR . '__CLASSE__Controller.php',
            ],
            [
                'namespace' => 'Http/Controllers/Api',
                'caminho' => app_path() . DIRECTORY_SEPARATOR . 'Http/Controllers/Api' . DIRECTORY_SEPARATOR . '__CLASSE__Controller.php',
            ],
            [
                'namespace' => 'Seeder',
                'caminho' => app_path() . DIRECTORY_SEPARATOR . '..' . DIRECTORY_SEPARATOR . 'database' . DIRECTORY_SEPARATOR . 'seeds' . DIRECTORY_SEPARATOR . '__CLASSE__Seeder.php',
            ],
            [
                'namespace' => 'Services',
                'caminho' => app_path() . DIRECTORY_SEPARATOR . 'Services' . DIRECTORY_SEPARATOR . '__CLASSE__Service.php',
            ],
            [
                'namespace' => 'Policies',
                'caminho' => app_path() . DIRECTORY_SEPARATOR . 'Policies' . DIRECTORY_SEPARATOR . '__CLASSE__Policy.php',
            ],
            [
                'namespace' => 'Observers',
                'caminho' => app_path() . DIRECTORY_SEPARATOR . 'Observers' . DIRECTORY_SEPARATOR . '__CLASSE__Observer.php',
            ],
            [
                'namespace' => 'Rules',
                'caminho' => app_path() . DIRECTORY_SEPARATOR . 'Rules' . DIRECTORY_SEPARATOR . '__CLASSE__Rule.php',
            ],
            [
                'namespace' => 'Requests',
                'caminho' => app_path() . DIRECTORY_SEPARATOR . 'Http/Requests' . DIRECTORY_SEPARATOR . '__CLASSE__StoreRequest.php',
            ]
        ];
    }

    public function gerardatabaseMapping($arquivo)
    {
        $stub = '
<?php
/**
 * Criado por RLustosa
 * Rupert Brasil Lustosa rupertlustosa@gmail.com
 * Date: {{date}}
 * Time: {{time}}
 */

$schemas = [

{{arrayMapeamento}}

];
		';

        $allTables = DB::getDoctrineSchemaManager()->listTables();

        $tabelas = [];

        foreach ($allTables as $table) {
            $tabelas[] = "
    [
        'table' => '" . $table->getName() . "',
        'classe' => '" . Str::studly(Str::singular($table->getName())) . "',
    ],";

        }

        $arrayMapeamento = implode("\r\n", $tabelas);

        $stub = str_replace('{{class}}', session('classe'), $stub);
        $stub = str_replace('{{classVar}}', lcfirst(session('classe')), $stub);
        $stub = str_replace('{{table}}', session('tabela'), $stub);
        $stub = str_replace('{{arrayMapeamento}}', $arrayMapeamento, $stub);
        $stub = str_replace('{{date}}', date('d/m/Y'), $stub);
        $stub = str_replace('{{time}}', date('H:i:s'), $stub);

        if ($this->files->put($arquivo, $stub)) {
            //chmod($arquivo, 0777);
            return true;
        } else {

            return false;
        }
    }

    public function inicio()
    {

        foreach ($this->fileTypes as $tipo) {

            $caminho = explode('__CLASSE__', $tipo['caminho'])[0];

            if (!file_exists($caminho)) {

                mkdir($caminho, 0777, true);
            }
        }

        $allTables = DB::getDoctrineSchemaManager()->listTables();
        $mapeamento = [];

        foreach ($this->schemas as $schema) {

            $tabela = $schema['table'];
            $classeLaravel = $schema['classe'];

            $mapeamento[$tabela] = [
                'tabela' => $tabela,
                'classe' => $classeLaravel,
            ];
        }

        session(['mapeamento' => $mapeamento]);

        return view('rlustosa.inicio', compact('allTables', 'mapeamento'));
    }

    public function mapa()
    {
        $fileTypes = [];
        session(['tabela' => request('tabela')]);
        session(['classe' => request('classe')]);

        $camposListagem = [
            'id',
            'created_at',
        ];
        $camposCadastro = [];
        $camposOcultos = [
            'deleted_at',
            'updated_at',
            'user_creator_id',
            'user_updater_id',
            'user_eraser_id',
            'lat',
            'lng',
        ];

        $chaves = [];

        if (empty(session('tabela'))) {

            throw new Exception('Falta session(\'tabela\')');
        }

        $tableColumns = DB::getDoctrineSchemaManager()->listTableColumns(session('tabela'));
        $camposDaTabela = $this->describe(session('tabela'));

        foreach ($camposDaTabela as $campo) {

            if ($campo['tipo'] === 'text') {

                $camposCadastro[] = $campo['campo_original'];
            }
        }

        $foreignKeys = DB::getDoctrineSchemaManager()->listTableForeignKeys(session('tabela'));

        foreach ($foreignKeys as $foreignKey) {

            foreach ($foreignKey->getLocalColumns() as $estrangeira) {

                $chaves[$estrangeira] = $chaves[$estrangeira] = session('mapeamento')[$foreignKey->getForeignTableName()];
            }
        }

        session(['chaves' => $chaves]);

        $conversaoDeLabels = [];

        $conversaoDeLabels['active'] = 'Ativo?';
        $conversaoDeLabels['address'] = 'Endereço';
        $conversaoDeLabels['birth'] = 'Data de nascimento';
        $conversaoDeLabels['category_id'] = 'Categoria';
        $conversaoDeLabels['city_id'] = 'Cidade';
        $conversaoDeLabels['complement'] = 'Complemento';
        $conversaoDeLabels['content'] = 'Conteúdo';
        $conversaoDeLabels['created_at'] = 'Criado em';
        $conversaoDeLabels['date'] = 'Data';
        $conversaoDeLabels['deleted_at'] = 'Removido em';
        $conversaoDeLabels['description'] = 'Descrição';
        $conversaoDeLabels['email'] = 'E-mail';
        $conversaoDeLabels['end_date'] = 'Data final';
        $conversaoDeLabels['gender'] = 'Gênero';
        $conversaoDeLabels['id'] = 'ID';
        $conversaoDeLabels['image'] = 'Imagem';
        $conversaoDeLabels['initials'] = 'Sigla';
        $conversaoDeLabels['keywords'] = 'Palavras-chave';
        $conversaoDeLabels['main'] = 'Principal';
        $conversaoDeLabels['name'] = 'Nome';
        $conversaoDeLabels['neighborhood'] = 'Bairro';
        $conversaoDeLabels['notes'] = 'Observações';
        $conversaoDeLabels['number'] = 'Número';
        $conversaoDeLabels['order'] = 'Ordem';
        $conversaoDeLabels['phone1'] = 'Telefone 1';
        $conversaoDeLabels['phone2'] = 'Telefone 2';
        $conversaoDeLabels['price'] = 'Preço';
        $conversaoDeLabels['postal_code'] = 'CEP';
        $conversaoDeLabels['size'] = 'Tamanho';
        $conversaoDeLabels['start_date'] = 'Data final';
        $conversaoDeLabels['state_id'] = 'Estado';
        $conversaoDeLabels['summary'] = 'Resumo';
        $conversaoDeLabels['tags'] = 'Palavras-chave';
        $conversaoDeLabels['title'] = 'Título';
        $conversaoDeLabels['type'] = 'Tipo';
        $conversaoDeLabels['type_id'] = 'Tipo';
        $conversaoDeLabels['updated_at'] = 'Atualizado em';
        $conversaoDeLabels['user_id'] = 'Usuário';
        $conversaoDeLabels['user_creator_id'] = 'Criado por:';
        $conversaoDeLabels['user_eraser_id'] = 'Quem removeu:';
        $conversaoDeLabels['user_updater_id'] = 'Atualizado por:';
        $conversaoDeLabels['views'] = 'Visualizações';
        $conversaoDeLabels['value'] = 'Valor';

        foreach ($this->fileTypes as $tipo) {

            $destino = str_replace('__CLASSE__', $this->classe, $tipo['caminho']);
            $fileTypes[] = [
                'tipo' => $tipo['namespace'],
                'destino' => $destino,
                'existe' => file_exists($destino) ? true : false,
            ];
        }

        $classe = session('classe');

        return view('rlustosa.mapa', compact('tableColumns', 'fileTypes', 'conversaoDeLabels', 'classe', 'camposListagem', 'camposOcultos', 'camposCadastro'));
    }

    public function describe($tabela)
    {

        $camposDaTabela = [];

        $describe = DB::select('describe `' . $tabela . '`;');
        foreach ($describe as $registro) {

            $tipo = preg_replace("/^(.+)\(.+$/", "$1", $registro->Type);

            $camposDaTabela[] = array('campo' => Str::camel($registro->Field), 'campo_original' => trim($registro->Field),
                'tipo' => $tipo,
                'key' => ($registro->Key === 'PRI' ? 'primaria' : ($registro->Key === 'MUL' ? 'estrangeira' : null)),
                'aceita_nulo' => ($registro->Null === 'NO' ? 'N' : ($registro->Null === 'YES' ? 'S' : null)),
                'entreParentesis' => $this->conteudoEntreParentesis($registro->Type)
            );
        }
        return $camposDaTabela;
    }

    public function conteudoEntreParentesis($Type)
    {

        $conteudo = null;
        $partes = explode('(', $Type);
        if (count($partes) > 1) {

            $partes = explode(')', $partes[1]);
            $conteudo = str_replace("'", "", $partes[0]);
        }
        return $conteudo;
    }

    public function organizaVisual()
    {

        $labels = Request::get('label');
        $classe = Request::get('classe');
        $arquivos = Request::get('arquivos');

        session(['label' => $labels]);
        session(['arquivos' => $arquivos]);

        $campos_disponiveis = $campos_disponiveis_listagem = [];

        foreach ($labels as $nome_campo => $valor) {

            if ($valor['exibir'] === 'c_l' or $valor['exibir'] === 'c') {

                $campos_disponiveis[] = [
                    'nome_campo' => $nome_campo,
                    'label_campo' => $valor['nome'],
                ];
            }

            if ($valor['exibir'] === 'c_l' or $valor['exibir'] === 'l') {

                $campos_disponiveis_listagem[] = [
                    'nome_campo' => $nome_campo,
                    'label_campo' => $valor['nome'],
                ];
            }
        }

        $this->camposIndex = $campos_disponiveis_listagem;

        return view('rlustosa.organiza-visual', compact('campos_disponiveis', 'classe', 'arquivos'));
    }

    public function gera()
    {

        $arquivos = session('arquivos');
        $campos = Request::get('campo');

        $html = $this->iniciaHtml();
        foreach ($campos as $campo => $largura) {

            $largura = round($largura);
            if (substr($campo, 0, 9) === 'divisor__') {

                $html .= $this->divisorHtml();
            } else {

                $html .= $this->retornaHtml($campo, round($largura));
            }
        }
        $html .= '
                            </div>
                            ';

        $projectFolder = str_replace('/public', null, public_path());

        foreach ($arquivos as $arquivo) {

            $dados = json_decode($arquivo);
            if ($dados->tipo === 'Http/Controllers/Panel') {

                $this->gerarController($dados->destino);
            } elseif ($dados->tipo === 'Http/Controllers/Api/V1') {

                $this->gerarControllerApi($dados->destino);
            } elseif ($dados->tipo === 'Observers') {

                $this->criaObservers(session('classe'));
            } elseif ($dados->tipo === 'Models') {

                $this->gerarModel($dados->destino);
            } elseif ($dados->tipo === 'Services') {

                $this->gerarService($dados->destino);
            } elseif ($dados->tipo === 'Policies') {

                $this->gerarPolicy($dados->destino);
            } elseif ($dados->tipo === 'Rules') {

                $this->gerarRule($dados->destino);
            } elseif ($dados->tipo === 'Requests') {

                $this->gerarRequest($dados->destino);
            } elseif ($dados->tipo === 'Seeder') {

                $this->gerarSeeder($dados->destino);
            }

            $this->criaPastaView();
            $this->gerarBarra();
            $this->gerarIndex();
            //$this->gerarDelete();
            $this->atualizarRoutes();
            $this->gerarForm($html);
        }
        echo "<br />'App\Models\\" . session('classe') . "' => 'App\Policies\\" . session('classe') . "Policy',";
        echo '<br /><a href="' . url('rlustosa') . '">rlustosa</a>';
        echo '<br />sudo find ' . $projectFolder . ' -type f -exec chmod 664 {} \; && sudo find ' . $projectFolder . ' -type d -exec chmod 775 {} \; && sudo chmod 777 -R ' . $projectFolder . '/storage';
    }

    private function iniciaHtml()
    {

        return '
                            <div class="form-row">';
    }

    private function divisorHtml()
    {

        return '                            </div>

                            <div class="form-row">';
    }

    private function retornaHtml($campo, $largura)
    {

        $labels = session('label');
        $label = $labels[$campo]['nome'];

        if ($campo === 'active' || $campo === 'status') {

            return '
                                <div class="form-group col-md-' . $largura . ' @if ($errors->has(\'' . $campo . '\')) has-error @endif">
                                    <label for="' . $campo . '">' . $label . '</label>
                                    <select name="' . $campo . '" id="' . $campo . '" class="form-control">
                                        @foreach(config(\'enums.boolean\') as $i => $v)
                                            <option value="{{ $i }}" {{ old(\'' . $campo . '\', (isset($item) ? $item->' . $campo . ' : \'1\')) == $i ? \'selected\' : \'\' }}>{{ $v }} </option>
                                        @endforeach
                                    </select>
                                    {!! $errors->first(\'' . $campo . '\',\'<span class="help-block m-b-none">:message</span>\') !!}
                                </div>';
        } elseif (substr($campo, 0, 5) === 'image' || substr($campo, 0, 4) === 'file') {

            return '
                                <div class="form-group col-md-' . $largura . ' @if ($errors->has(\'' . $campo . '\')) has-error @endif">
                                    <label for="' . $campo . '">' . $label . '</label>
                                    <input type="file" name="' . $campo . '" id="' . $campo . '" class="form-control">
                                    {!! $errors->first(\'' . $campo . '\',\'<span class="help-block m-b-none">:message</span>\') !!}
                                    @if(isset($item) && $item->' . $campo . ')
                                        <br/>
                                        <label> <input type="checkbox" value="1" name="delete_' . $campo . '">
                                            Remover ' . $campo . '?
                                        </label>
                                    @endif
                                </div>
';
        } elseif (in_array($campo, $this->fieldsText)) {

            return '
                                <div class="form-group col-md-' . $largura . ' @if ($errors->has(\'' . $campo . '\')) has-error @endif">
                                    <label for="' . $campo . '">' . $label . '</label>
                                    <textarea  rows="14" cols="50" name="' . $campo . '" id="' . $campo . '" class="form-control">{{ old(\'' . $campo . '\', (isset($item) ? $item->' . $campo . ' : \'\')) }}</textarea>
                                    {!! $errors->first(\'' . $campo . '\',\'<span class="help-block m-b-none">:message</span>\') !!}
                                </div>
';
        } elseif (substr($campo, 0, 4) === 'date' || substr(strrev($campo), 0, 4) === 'etad') {

            return '
                                <div class="form-group col-md-' . $largura . ' @if ($errors->has(\'' . $campo . '\')) has-error @endif">
                                    <label for="' . $campo . '">' . $label . '</label>
                                    <div class="input-group date_calendar">
                                        <div class="input-group date">
                                            <span class="input-group-addon"><i class="fa fa-calendar"></i></span>
                                            <input type="text" class="form-control mask_date" name="' . $campo . '"
                                                   id="' . $campo . '"
                                                   value="{{ old(\'' . $campo . '\', (isset($item) ? $item->' . $campo . ' : \'\')) }}">
                                        </div>
                                    </div>
                                    {!! $errors->first(\'' . $campo . '\',\'<span class="help-block m-b-none">:message</span>\') !!}
                                </div>
';
        }

        $camposDaTabela = $this->describe(session('tabela'));

        $type = 'text';

        foreach ($camposDaTabela as $value) {

            if ($value['campo_original'] !== $campo) {

                continue;
            }

            if (in_array($campo, $this->fieldsNumber)) {

                $type = 'number';
            }
        }

        return '
                                <div class="form-group col-md-' . $largura . ' @if ($errors->has(\'' . $campo . '\')) has-error @endif">
                                    <label for="' . $campo . '">' . $label . '</label>
                                    <input type="' . $type . '" name="' . $campo . '" id="' . $campo . '" class="form-control"
                                    		value="{{ old(\'' . $campo . '\', (isset($item) ? $item->' . $campo . ' : \'\')) }}">
                                    {!! $errors->first(\'' . $campo . '\',\'<span class="help-block m-b-none">:message</span>\') !!}
                                </div>
';
    }

    public function gerarController($arquivoController)
    {
        $stub = $this->files->get($this->getStubPath() . '/Controller.stub');

        $stub = str_replace('{{class}}', session('classe'), $stub);
        $stub = str_replace('{{classVar}}', lcfirst(session('classe')), $stub);
        $stub = str_replace('{{table}}', session('tabela'), $stub);
        $stub = str_replace('{{date}}', date('d/m/Y'), $stub);
        $stub = str_replace('{{time}}', date('H:i:s'), $stub);

        if ($this->files->put($arquivoController, $stub)) {

            //chmod($arquivoController, 0664);
            return true;
        } else {

            return false;
        }
    }

    /**
     * Get the path to the stub file.
     *
     * @return string
     */
    public function getStubPath()
    {
        return __DIR__ . '/Stubs';
    }

    public function gerarControllerApi($arquivoController)
    {
        /*$stub = $this->files->get($this->getStubPath() . '/ApiController.stub');

        $stub = str_replace('{{class}}', session('classe'), $stub);
        $stub = str_replace('{{classVar}}', lcfirst(session('classe')), $stub);
        $stub = str_replace('{{table}}', session('tabela'), $stub);
        $stub = str_replace('{{date}}', date('d/m/Y'), $stub);
        $stub = str_replace('{{time}}', date('H:i:s'), $stub);

        if ($this->files->put($arquivoController, $stub)) {

            return true;
        } else {

            return false;
        }*/
        $this->criaApi(session('tabela'));
    }

    public function criaApi($tabela)
    {

        $models = [];

        foreach ($this->schemas as $item) {

            $models[$item['table']] = $item['classe'];
        }

        if (!array_key_exists($tabela, $models)) {

            dd($tabela . ' inválida');
        }
        $model = $models[$tabela];

        $stub = $this->files->get($this->getStubPath() . '/ApiController.stub');

        $stub = str_replace('{{class}}', $model, $stub);
        $stub = str_replace('{{classVar}}', lcfirst($model), $stub);
        $stub = str_replace('{{table}}', $tabela, $stub);
        $stub = str_replace('{{date}}', date('d/m/Y'), $stub);
        $stub = str_replace('{{time}}', date('H:i:s'), $stub);

        $dest = app_path() . '/Http/Controllers/Api/Api' . $model . 'Controller.php';
        //dd($dest, $stub);

        if (!file_exists($dest)) {

            if ($this->files->put($dest, $stub)) {

            } else {

                dd('ERRO', $model);
            }
        }

        ////////////////////////////////////////////////////////////////////////////////////////////////////////////////
        $camposParaResource = [];
        $camposDaTabela = $this->describe($tabela);

        foreach ($camposDaTabela as $campo) {

            $original = $campo['campo_original'];
            if (!in_array($original, ['id', 'created_at', 'updated_at', 'deleted_at', 'user_creator_id', 'user_updater_id', 'user_eraser_id'])) {

                $camposParaResource[] = '            \'' . $original . '\' => $this->' . $original . ',';
            }
        }

        $camposParaResource = implode("\r\n", $camposParaResource);

        $stub = $this->files->get($this->getStubPath() . '/Resource.stub');

        $stub = str_replace('{{class}}', $model, $stub);
        $stub = str_replace('{{classVar}}', lcfirst($model), $stub);
        $stub = str_replace('{{table}}', $tabela, $stub);
        $stub = str_replace('{{date}}', date('d/m/Y'), $stub);
        $stub = str_replace('{{time}}', date('H:i:s'), $stub);
        $stub = str_replace('{{camposParaResource}}', $camposParaResource, $stub);

        $dest = app_path() . '/Http/Resources/' . $model . 'Resource.php';
        //dd($dest, $stub);
        if (!file_exists($dest)) {

            if ($this->files->put($dest, $stub)) {

            } else {

                dd('ERRO', $model);
            }
        }

        ////////////////////////////////////////////////////////////////////////////////////////////////////////////////
        $stub = $this->files->get($this->getStubPath() . '/Collection.stub');

        $stub = str_replace('{{class}}', $model, $stub);
        $stub = str_replace('{{classVar}}', lcfirst($model), $stub);
        $stub = str_replace('{{table}}', $tabela, $stub);
        $stub = str_replace('{{date}}', date('d/m/Y'), $stub);
        $stub = str_replace('{{time}}', date('H:i:s'), $stub);

        $dest = app_path() . '/Http/Resources/' . $model . 'Collection.php';
        //dd($dest, $stub);
        if (!file_exists($dest)) {

            if ($this->files->put($dest, $stub)) {

            } else {

                dd('ERRO', $model);
            }
        }

        $projectFolder = str_replace('/public', null, public_path());
        //echo '<br />sudo find ' . $projectFolder . ' -type f -exec chmod 664 {} \; && sudo find ' . $projectFolder . ' -type d -exec chmod 775 {} \; && sudo chmod 777 -R ' . $projectFolder . '/storage';
    }

    public function criaObservers($class)
    {

        $models = [
            $class
        ];

        $boot = [];
        foreach ($models as $model) {

            $stub = $this->files->get($this->getStubPath() . '/Observer.stub');

            $stub = str_replace('{{class}}', $model, $stub);
            $stub = str_replace('{{classVar}}', lcfirst($model), $stub);
            $stub = str_replace('{{date}}', date('d/m/Y'), $stub);
            $stub = str_replace('{{time}}', date('H:i:s'), $stub);

            $dest = app_path() . '/Observers/' . $model . 'Observer.php';
            //dd($dest);

            if ($this->files->put($dest, $stub)) {

                $boot[] = $model . '::observe(' . $model . 'Observer::class);';

            } else {

                dd('ERRO', $model);
            }
        }

        foreach ($boot as $b) {

            echo "<br>" . $b;
        }

        $projectFolder = str_replace('/public', null, public_path());
        //echo '<br />sudo find ' . $projectFolder . ' -type f -exec chmod 664 {} \; && sudo find ' . $projectFolder . ' -type d -exec chmod 775 {} \; && sudo chmod 777 -R ' . $projectFolder . '/storage';
    }

    public function gerarModel($arquivoModel)
    {
        $camposParaSalvar = [];
        $stub = $this->files->get($this->getStubPath() . '/Model.stub');

        $camposDaTabela = $this->describe(session('tabela'));

        $fillable = $dates = [];

        $hasDeletedAt = false;

        foreach ($camposDaTabela as $campo) {

            if ($campo['campo_original'] === 'deleted_at') {

                $hasDeletedAt = true;
            }

            if (!in_array($campo['campo_original'], ['id', 'created_at', 'updated_at', 'deleted_at'])) {

                $camposParaSalvar[] = $this->gerarCamposParaSalvarNoRepositorio($campo['campo_original']);

                if (in_array($campo['tipo'], ['datetime', 'date', 'timestamp'])) {

                    $dates[] = $campo['campo_original'];
                } else {

                    $fillable[] = "        '" . $campo['campo_original'] . "',";
                }
            }
        }

        $useIlluminateSoftDeletes = $useSoftDeletes = null;

        if ($hasDeletedAt === true) {

            $useIlluminateSoftDeletes = 'use Illuminate\Database\Eloquent\SoftDeletes;';
            $useSoftDeletes = '    use SoftDeletes;';
        }

        $fillable = implode("\r\n", $fillable);

        $stub = str_replace('{{class}}', session('classe'), $stub);
        $stub = str_replace('{{classVar}}', lcfirst(session('classe')), $stub);
        $stub = str_replace('{{table}}', session('tabela'), $stub);
        $stub = str_replace('{{fillable}}', $fillable, $stub);
        $stub = str_replace('{{useIlluminateSoftDeletes}}', "\r\n" . $useIlluminateSoftDeletes, $stub);
        $stub = str_replace('{{useSoftDeletes}}', "\r\n" . $useSoftDeletes, $stub);
        $stub = str_replace('{{date}}', date('d/m/Y'), $stub);
        $stub = str_replace('{{time}}', date('H:i:s'), $stub);

        if ($this->files->put($arquivoModel, $stub)) {
            //chmod($arquivoModel, 0664);
            return true;
        } else {

            return false;
        }
    }

    private function gerarCamposParaSalvarNoRepositorio($campo)
    {
        return '		if(array_key_exists(\'' . $campo . '\', $attributes))
			$model->' . $campo . ' = trim($attributes[\'' . $campo . '\']);';
    }

    public function gerarService($arquivoService)
    {
        $stub = $this->files->get($this->getStubPath() . '/Service.stub');

        $stub = str_replace('{{class}}', session('classe'), $stub);
        $stub = str_replace('{{classVar}}', lcfirst(session('classe')), $stub);
        $stub = str_replace('{{table}}', session('tabela'), $stub);
        $stub = str_replace('{{date}}', date('d/m/Y'), $stub);
        $stub = str_replace('{{time}}', date('H:i:s'), $stub);

        if ($this->files->put($arquivoService, $stub)) {
            //chmod($arquivoService, 0664);
            return true;
        } else {

            return false;
        }
    }

    public function gerarPolicy($arquivoPolicy)
    {
        $stub = $this->files->get($this->getStubPath() . '/Policy.stub');

        $stub = str_replace('{{class}}', session('classe'), $stub);
        $stub = str_replace('{{classVar}}', lcfirst(session('classe')), $stub);
        $stub = str_replace('{{date}}', date('d/m/Y'), $stub);
        $stub = str_replace('{{time}}', date('H:i:s'), $stub);

        if ($this->files->put($arquivoPolicy, $stub)) {
            //chmod($arquivoPolicy, 0664);
            return true;
        } else {

            return false;
        }
    }

    public function gerarRule($arquivoRequest)
    {
        $stub = $this->files->get($this->getStubPath() . '/Rule.stub');

        $camposDaTabela = $this->describe(session('tabela'));

        $regrasValidacao = $this->gerarRules($camposDaTabela, session('tabela'), session('classe'));
        $regras = implode("\r\n" . '        ', $regrasValidacao['regras']);
        $thisRegras = implode("\r\n" . '            ', $regrasValidacao['rules']);

        $stub = str_replace('{{class}}', session('classe'), $stub);
        $stub = str_replace('{{classVar}}', lcfirst(session('classe')), $stub);
        $stub = str_replace('{{table}}', session('tabela'), $stub);
        $stub = str_replace('{{thisRegras}}', $thisRegras, $stub);
        $stub = str_replace('{{regras}}', $regras, $stub);
        $stub = str_replace('{{date}}', date('d/m/Y'), $stub);
        $stub = str_replace('{{time}}', date('H:i:s'), $stub);

        if ($this->files->put($arquivoRequest, $stub)) {
            //chmod($arquivoRequest, 0664);
            return true;
        } else {

            return false;
        }
    }

    public function gerarRules($camposDaTabela, $tabela, $nomeDaClasse)
    {

        $mapeamento = [];
        foreach ($this->schemas as $schema) {

            if (is_array($schema) and count($schema)) {

                $tabela = $schema['table'];
                $classeLaravel = $schema['classe'];

                $mapeamento[$classeLaravel]['classe'] = $classeLaravel;

                $mapeamento[$classeLaravel]['tabela'] = $tabela;
            }
        }

        $regras = $rules = [];
        foreach ($camposDaTabela as $campo) {

            if ($campo['campo_original'] === 'created_at' or $campo['campo_original'] === 'updated_at' or $campo['campo_original'] === 'deleted_at') {

                continue;
            }

            if ($campo['tipo'] === 'varchar') {

                if (substr($campo['campo_original'], 0, 5) === 'image') {

                    $regras[] = "'" . $campo['campo_original'] . "' => 'required|image|mimes:jpeg,png,jpg,gif,svg|max:3072',";
                    $regras[] = "'" . $campo['campo_original'] . "Update' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:3072',";
                } elseif ($campo['campo_original'] === 'email') {

                    if ($campo['aceita_nulo'] === 'N') {

                        $regras[] = "'" . $campo['campo_original'] . "' => 'required|email',";
                    } elseif ($campo['aceita_nulo'] === 'S') {

                        $regras[] = "'" . $campo['campo_original'] . "' => 'nullable|email',";
                    }
                } elseif ($campo['campo_original'] === 'url' || $campo['campo_original'] === 'link') {

                    if ($campo['aceita_nulo'] === 'N') {

                        $regras[] = "'" . $campo['campo_original'] . "' => 'required|url',";
                    } elseif ($campo['aceita_nulo'] === 'S') {

                        $regras[] = "'" . $campo['campo_original'] . "' => 'nullable|url',";
                    }
                } else {

                    if ($campo['aceita_nulo'] === 'N') {

                        $regras[] = "'" . $campo['campo_original'] . "' => 'required|min:2|max:" . $campo['entreParentesis'] . "',";
                    } elseif ($campo['aceita_nulo'] === 'S') {

                        $regras[] = "'" . $campo['campo_original'] . "' => 'nullable|min:2|max:" . $campo['entreParentesis'] . "',";
                    }
                }
            } elseif ($campo['tipo'] === 'int') {

                if ($campo['key'] === 'primaria') {

                    $regras[] = "'id' => 'required|integer|exists:" . $mapeamento[$nomeDaClasse]['tabela'] . ",id,deleted_at,NULL',";
                } elseif (substr($campo['campo_original'], 0, 6) === 'active') {

                    $regras[] = "'" . $campo['campo_original'] . "' => 'required|in:0,1,";

                } elseif ($campo['key'] === 'estrangeira') {

                    $dadosForeignKey = session('chaves');

                    $existe = isset($dadosForeignKey[$campo['campo_original']]['tabela']);

                    if ($existe) {

                        $regras[] = "'" . $campo['campo_original'] . "' => 'required|exists:" . $dadosForeignKey[$campo['campo_original']]['tabela'] . ",id,deleted_at,NULL',";
                    } else {
                        print_r($dadosForeignKey);
                        $regras[] = "'" . $campo['campo_original'] . "' => 'required|exists:VERIFICAR_ESSA_REGRA,id,deleted_at,NULL',";
                        //dd('Erro na rules na linha ' . __LINE__);
                    }
                } else {

                    if ($campo['aceita_nulo'] === 'N') {

                        $regras[] = "'" . $campo['campo_original'] . "' => 'required|integer',";
                    } elseif ($campo['aceita_nulo'] === 'S') {

                        $regras[] = "'" . $campo['campo_original'] . "' => 'nullable|integer',";
                    }
                }
            } elseif ($campo['tipo'] === 'date') {

                if ($campo['aceita_nulo'] === 'N') {

                    $regras[] = "'" . $campo['campo_original'] . "' => 'required|date_format:d/m/Y',";
                } elseif ($campo['aceita_nulo'] === 'S') {

                    $regras[] = "'" . $campo['campo_original'] . "' => 'nullable|date_format:d/m/Y',";
                }
            } elseif ($campo['tipo'] === 'datetime' or $campo['tipo'] === 'timestamp') {

                if ($campo['aceita_nulo'] === 'N') {

                    $regras[] = "'" . $campo['campo_original'] . "' => 'required|date_format:d/m/Y H:i',";
                } elseif ($campo['aceita_nulo'] === 'S') {

                    $regras[] = "'" . $campo['campo_original'] . "' => 'nullable|date_format:d/m/Y H:i',";
                }
            } elseif ($campo['tipo'] === 'enum') {

                if ($campo['aceita_nulo'] === 'N') {

                    $regras[] = "'" . $campo['campo_original'] . "' => 'required|in:" . $campo['entreParentesis'] . "',";
                } elseif ($campo['aceita_nulo'] === 'S') {

                    $regras[] = "'" . $campo['campo_original'] . "' => 'nullable|in:" . $campo['entreParentesis'] . "',";
                }
            } else {

                if ($campo['aceita_nulo'] === 'N') {

                    $regras[] = "'" . $campo['campo_original'] . "' => 'required',";
                } elseif ($campo['aceita_nulo'] === 'S') {

                    $regras[] = "'" . $campo['campo_original'] . "' => 'nullable',";
                } else {

                    $regras[] = "'" . $campo['campo_original'] . "' => '???????????????',";
                }
            }

            if ($campo['campo_original'] !== 'id') {

                $rules[] = '\'' . $campo['campo_original'] . '\' => self::$rules[\'' . $campo['campo_original'] . '\'],';
            }
        }

        return ['regras' => $regras, 'rules' => $rules];
    }

    public function gerarRequest($arquivoStoreRequest)
    {
        $stub = $this->files->get($this->getStubPath() . '/StoreRequest.stub');

        $stub = str_replace('{{class}}', session('classe'), $stub);
        $stub = str_replace('{{classVar}}', lcfirst(session('classe')), $stub);
        $stub = str_replace('{{table}}', session('tabela'), $stub);
        $stub = str_replace('{{date}}', date('d/m/Y'), $stub);
        $stub = str_replace('{{time}}', date('H:i:s'), $stub);

        $stubUpdate = $this->files->get($this->getStubPath() . '/UpdateRequest.stub');

        $stubUpdate = str_replace('{{class}}', session('classe'), $stubUpdate);
        $stubUpdate = str_replace('{{classVar}}', lcfirst(session('classe')), $stubUpdate);
        $stubUpdate = str_replace('{{table}}', session('tabela'), $stubUpdate);
        $stubUpdate = str_replace('{{date}}', date('d/m/Y'), $stubUpdate);
        $stubUpdate = str_replace('{{time}}', date('H:i:s'), $stubUpdate);

        $arquivoUpdateRequest = str_replace('Store', 'Update', $arquivoStoreRequest);

        if ($this->files->put($arquivoStoreRequest, $stub) && $this->files->put($arquivoUpdateRequest, $stubUpdate)) {
            //chmod($arquivoStoreRequest, 0664);
            return true;
        } else {

            return false;
        }
    }

    public function gerarSeeder($arquivo)
    {
        $stub = '<?php
/**
 * @package    Seeder
 * @author     Rupert Brasil Lustosa <rupertlustosa@gmail.com>
 * @date       {{date}} {{time}}
 */

use Illuminate\Database\Seeder;

class {{class}}Seeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {

        $itens = [];

        foreach ($itens as $item) {

            \App\Models\{{class}}::create($item);
        }
    }
}
		';

        $stub = str_replace('{{class}}', session('classe'), $stub);
        $stub = str_replace('{{classVar}}', lcfirst(session('classe')), $stub);
        $stub = str_replace('{{table}}', session('tabela'), $stub);
        $stub = str_replace('{{date}}', date('d/m/Y'), $stub);
        $stub = str_replace('{{time}}', date('H:i:s'), $stub);

        if ($this->files->put($arquivo, $stub)) {
            //chmod($arquivo, 0664);
            return true;
        } else {

            return false;
        }
    }

    public function criaPastaView()
    {
        $path = $this->getViewsPath();
        if (!file_exists($path . DIRECTORY_SEPARATOR . session('tabela'))) {

            if (mkdir($path . DIRECTORY_SEPARATOR . session('tabela'), 0777, true)) {

                echo '<br />Pasta Criada: ' . $path . DIRECTORY_SEPARATOR . session('tabela');
            } else {

                echo '<br />Erro ao criar pasta: ' . $path . DIRECTORY_SEPARATOR . session('tabela');
            }
        }
    }

    public function getViewsPath()
    {
        $path = __DIR__ . '/../../../../resources/views/panel';
        if (!file_exists($path)) {
            mkdir($path, 0777, true);
        }
        $path = realpath($path);
        return $path;
    }

    public function gerarBarra()
    {
        $stub = $this->files->get($this->getStubPath() . '/nav.blade.php');

        $path = $this->getViewsPath();

        $arquivo = $path . DIRECTORY_SEPARATOR . session('tabela') . DIRECTORY_SEPARATOR;

        $stub = str_replace('{{class}}', session('classe'), $stub);
        $stub = str_replace('{{classVar}}', lcfirst(session('classe')), $stub);
        $stub = str_replace('{{table}}', session('tabela'), $stub);
        $stub = str_replace('{{date}}', date('d/m/Y'), $stub);
        $stub = str_replace('{{time}}', date('H:i:s'), $stub);

        if ($this->files->put($arquivo . 'nav.blade.php', $stub)) {
            //chmod($arquivo, 0664);
            return true;
        } else {

            return false;
        }
    }

    public function gerarIndex()
    {

        $labels = session('label');
        $campos = [];

        $path = $this->getViewsPath();

        $arquivo = $path . DIRECTORY_SEPARATOR . session('tabela') . DIRECTORY_SEPARATOR;

        foreach ($labels as $nome_banco => $label) {

            if ($label['exibir'] === 'l' or $label['exibir'] === 'c_l') {

                $thead = '                                        <th>' . $label['nome'] . '</th>';
                if ($nome_banco === 'active') {

                    $tbody = '                                                <td>{{ $item->' . $nome_banco . ' == 1 ? \'Sim\' : \'Não\' }}</td>';
                } else {

                    $tbody = '                                                <td>{{ $item->' . $nome_banco . ' }}</td>';
                }

                if ($nome_banco === 'created_at' or $nome_banco === 'updated_at') {

                    $thead = '                                        <th class="hidden-xs hidden-sm" style="width: 150px;">' . $label['nome'] . '</th>';
                    $tbody = '                                                <td class="hidden-xs hidden-sm">{{ $item->' . $nome_banco . '->format(\'d/m/Y H:i\') }}</td>';
                } elseif (substr($nome_banco, 0, 5) === 'image') {

                    /*if (!empty(substr($nome_banco, 6, 1))){

                        $orderDaFoto = substr($nome_banco, 6, 1);
                    }
                    else {

                        $orderDaFoto = 'null';
                    }*/

                    $thead = '                                        <th>' . $label['nome'] . '</th>';
                    $tbody = '
                                                <td class="text-center">
                                                    @if($item->' . $nome_banco . ')
                                                        <a href="{{ asset(\'images/\'.$item->image) }}" target="_blank">
                                                            <img src="{{ asset(\'images/100/\'.$item->' . $nome_banco . ') }}">
                                                        </a>
                                                        <br/>
                                                        <a href="{{ route(\'' . session('tabela') . '.imageCrop\', [$item->id]) }}">
                                                            <i class="fa fa-crop"></i>
                                                            Recortar
                                                        </a>
                                                    @endif
                                                </td>
            ';
                } elseif ($nome_banco === 'created_at' or $nome_banco === 'updated_at' or $nome_banco === 'id') {

                    $thead = '';
                    $tbody = '';
                }

                $campos[] = ['thead' => $thead, 'tbody' => $tbody];
            }
        }

        $stub = $this->files->get($this->getStubPath() . '/index.blade.stub');

        $camposList = null;
        foreach ($campos as $campo) {

            $camposList['thead'][] = $campo['thead'];
            $camposList['tbody'][] = $campo['tbody'];
        }

        $theadString = implode("\r\n", $camposList['thead']);
        $tbodyString = implode("\r\n", $camposList['tbody']);

        $stub = str_replace('{{camposTḧ}}', $theadString, $stub);
        $stub = str_replace('{{camposTR}}', $tbodyString, $stub);
        $stub = str_replace('{{class}}', session('classe'), $stub);
        $stub = str_replace('{{classVar}}', lcfirst(session('classe')), $stub);
        $stub = str_replace('{{table}}', session('tabela'), $stub);
        $stub = str_replace('{{date}}', date('d/m/Y'), $stub);
        $stub = str_replace('{{time}}', date('H:i:s'), $stub);

        if ($this->files->put($arquivo . 'index.blade.php', $stub)) {
            //chmod($arquivo, 0664);
            return true;
        } else {

            return false;
        }
    }

    public function atualizarRoutes()
    {

        $nomeDaClasse = session('classe');

        $needGenesisCode = '        # rotas para panel';

        $needTableRoute = '/* panel/' . session('tabela') . ' */';

        $conteudoBaseDasRotas = '    ' . $needTableRoute . '
        $panel->resource(\'' . session('tabela') . '\', ' . $nomeDaClasse . 'Controller::class);';

        $routePath = realpath(__DIR__ . '/../../../../routes') . '/panel.php';

        $content = File::get($routePath);
        if (strpos($content, $needGenesisCode) !== false) {

            if (strpos($content, $needTableRoute) === false) {

                $content = str_replace($needGenesisCode, $conteudoBaseDasRotas . "\n\n" . $needGenesisCode, $content);

                if ($this->files->put($routePath, $content)) {

                    return true;
                } else {

                    return false;
                }
            }
        }

        return true;
    }

    public function gerarForm($campos)
    {

        $stub = $this->files->get($this->getStubPath() . '/form.blade.php');

        $path = $this->getViewsPath();
        $arquivo = $path . DIRECTORY_SEPARATOR . session('tabela') . DIRECTORY_SEPARATOR;

        $stub = str_replace('{{class}}', session('classe'), $stub);
        $stub = str_replace('{{classVar}}', lcfirst(session('classe')), $stub);
        $stub = str_replace('{{table}}', session('tabela'), $stub);
        $stub = str_replace('{{campos}}', $campos, $stub);
        $stub = str_replace('{{date}}', date('d/m/Y'), $stub);
        $stub = str_replace('{{time}}', date('H:i:s'), $stub);

        if ($this->files->put($arquivo . 'form.blade.php', $stub)) {
            //chmod($arquivo, 0664);
            return true;
        } else {

            return false;
        }
    }

    public function gerarDelete()
    {

        $labels = session('label');

        $path = $this->getViewsPath();
        $arquivo = $path . DIRECTORY_SEPARATOR . session('tabela') . DIRECTORY_SEPARATOR;

        $stub = $this->files->get($this->getStubPath() . '/delete.blade.php');
        $camposList = null;

        foreach ($labels as $nome_banco => $label) {

            if ($label['exibir'] === 'l' or $label['exibir'] === 'c_l') {

                $camposList .= '
                                                <div class="form-row">
                                                    <label class="col-sm-2 control-label">' . $label['nome'] . '</label>';

                if ($nome_banco === 'active') {
                    $camposList .= '
                                                    <div class="col-sm-10">
                                                        <p class="form-control-static">{{ $item->' . $nome_banco . ' == 1 ? \'Sim\' : \'Não\' }}</p>
                                                    </div>';
                } elseif ($nome_banco === 'created_at' || $nome_banco === 'updated_at') {
                    $camposList .= '
                                                    <div class="col-sm-10">
                                                        <p class="form-control-static">{{ $item->' . $nome_banco . '->format(\'d/m/Y H:i\') }}</p>
                                                    </div>';
                } else {
                    $camposList .= '
                                                    <div class="col-sm-10">
                                                        <p class="form-control-static">{{ $item->' . $nome_banco . ' }}</p>
                                                    </div>';
                }

                $camposList .= '
                                                </div>
                                                <div class="hr-line-dashed"></div>
';
            }
        }

        $stub = str_replace('{{camposList}}', $camposList, $stub);
        $stub = str_replace('{{class}}', session('classe'), $stub);
        $stub = str_replace('{{classVar}}', lcfirst(session('classe')), $stub);
        $stub = str_replace('{{table}}', session('tabela'), $stub);
        $stub = str_replace('{{date}}', date('d/m/Y'), $stub);
        $stub = str_replace('{{time}}', date('H:i:s'), $stub);

        if ($this->files->put($arquivo . 'delete.blade.php', $stub)) {
            //chmod($arquivo, 0664);
            return true;
        } else {

            return false;
        }
    }

    public function escreveFillable($tabela)
    {

        $camposDaTabela = $this->describe($tabela);

        $fillable = [];

        foreach ($camposDaTabela as $campo) {

            if ($campo['campo_original'] !== 'id' and $campo['campo_original'] !== 'created_at' and $campo['campo_original'] !== 'updated_at' and $campo['campo_original'] !== 'deleted_at') {

                $fillable[] = "        '" . $campo['campo_original'] . "',";
            }
        }

        $fillable = implode("\r\n", $fillable);

        echo '<pre>';
        print_r($fillable);
        exit;
    }

    /**
     * Checks if a database table exists
     *
     * @param string $table
     * @return bool
     */
    public function hasTable($table)
    {
        return Schema::connection($this->connection)->hasTable($table);
    }
}
