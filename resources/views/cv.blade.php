
@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
            <div class="card">
                <div class="card-header">{{ __('CV Saya') }}</div>

                <div class="card-body">
                    <embed src="{{asset('cv_.pdf')}}" type="application/pdf" width="100%" height="800px" />
                    {{-- <object data="cv_.pdf#page=2" type="application/pdf" width="100%" height="100%"> --}}
                </div>
            </div>
    </div>
</div>
@endsection
