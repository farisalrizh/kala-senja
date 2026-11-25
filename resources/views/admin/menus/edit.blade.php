@extends('admin.menus.form')

@section('title', 'Ubah Menu — Kala Senja')
@section('form-heading', 'Ubah menu.')
@section('form-action', route('admin.menus.update', $menu))
@section('form-method')
    @method('PUT')
@endsection
@section('submit-label', 'Simpan perubahan')
