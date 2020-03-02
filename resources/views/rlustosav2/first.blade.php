@extends('rlustosa._master')

@section('content')
    <form method="post" action="{{ url('/rlustosa') }}" autocomplete="off">
        <div class="row">
            <div class="col-12">
                {{ csrf_field() }}
                <div class="text-uppercase font-weight-bolder mb-4 mt-4">
                    Qual tabela você deseja gerar os arquivos?
                </div>
            </div>

            @foreach($mapping as $table)

                <div class="col-6">
                    <label>
                        <input type="radio"
                               name="table"
                               id="table_{{ $table['table'] }}"
                               value="{{ $table['table'] }}"
                               required> {{ $table['table'] }}
                    </label>
                </div>
            @endforeach
            <div class="col-12">
                <button type="submit" class="btn btn-success" value="Submit">Avançar</button>
            </div>
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
