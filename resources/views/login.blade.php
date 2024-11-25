@extends('layout.layout')
@section("titulo","Login")


@section('conteudo')

    <style>


    </style>
    <div class="page">
            <form class="formLogin" action="/adminsLogin" method="post">
            <input type="hidden" name="_token" value="{{csrf_token()}}">
                <h1>Login</h1>

                @if($errors->any())
                    <input type="hidden" name="" value="{{ $errors->all()[0]}}" id="erro1">
                @endif
                @if(session("erro"))
                    <input type="hidden" name="" value='{{ session("erro")}}' id="erro2">
                @endif
                <hr>
                <p>Digite os dados do acesso</p>
                <label for="contacto"></label>
                <input type="text" name="email" value="{{ old('email')}}" placeholder="Digite email" autofocus="true" required/>
                <label for="password"></label>
                <input type="password" name="password" placeholder="Digite a Senha" required />

                <input type="submit" value="Acessar" class="btn" />
            </form>
        </div>
@endsection


@section("js")

<script src="{{'assets/adminLogin.js'}}"></script>
<link rel="stylesheet" href="{{ asset('assets/login.css')}}">
<script src="{{ asset('assets/adminLogin.js')}}"></script>
@endsection





