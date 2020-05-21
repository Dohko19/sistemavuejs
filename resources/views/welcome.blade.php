@extends('layouts.sistema')
{{-- @section('head')
 <ol class="breadcrumb">
    <li class="breadcrumb-item">Home</li>
    <li class="breadcrumb-item"><a href="#">Admin</a></li>
    <li class="breadcrumb-item active">Dashboard</li>
</ol>

@endsection --}}
@section('content')
    <template v-if="menu==0">
        <h1>CONTENIDO  DEL MENU 0</h1>
    </template>

    <template v-if="menu==1">
        <categoria></categoria>
    </template>
    <template v-if="menu==2">
        <articulo></articulo>
    </template>
    <template v-if="menu==3">
        <h1>CONTENIDO  DEL MENU 3</h1>
    </template>
    <template v-if="menu==4">
        <h1>CONTENIDO  DEL MENU 4</h1>
    </template>
    <template v-if="menu==5">
        <h1>CONTENIDO  DEL MENU 5</h1>
    </template>
    <template v-if="menu==6">
        <cliente></cliente>
    </template>
    <template v-if="menu==7">
        <h1>CONTENIDO  DEL MENU 7</h1>
    </template>
    <template v-if="menu==8">
        <h1>CONTENIDO  DEL MENU 8</h1>
    </template>
    <template v-if="menu==9">
        <h1>CONTENIDO  DEL MENU 9</h1>
    </template>
    <template v-if="menu==10">
        <h1>CONTENIDO  DEL MENU 10</h1>
    </template>
    <template v-if="menu==11">
        <h1>CONTENIDO  DEL MENU 11</h1>
    </template>
    <template v-if="menu==12">
        <h1>CONTENIDO  DEL MENU 12</h1>
    </template>
@endsection
