<!DOCTYPE html>
<html lang="en">

{{-- Include Head --}}
@include('common.fronthead')

<body id="page-top">

    

        @include('common.frontheader')

     
                @yield('content')
               
            @include('common.frontfooter')
           

    
    @include('common.frontfooterjs')
   
    @yield('scripts')
</body>

</html>