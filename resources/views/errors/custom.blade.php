@extends('errors.minimal')

@section('title', $title ?? "Error")
@section('code', $code ?? "404")
@section('message', $message ?? "Not Found")
