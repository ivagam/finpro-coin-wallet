<!-- meta tags and other links -->
<!DOCTYPE html>
<html lang="en" data-theme="light">

<x-head />

<body>

    <!-- ..::  header area start ::.. -->
    <x-sidebar />
    <!-- ..::  header area end ::.. -->

    <main class="dashboard-main">

        <!-- ..::  navbar start ::.. -->
        <x-navbar />
        <!-- ..::  navbar end ::.. -->
        <div class="dashboard-main-body">
            
            <!-- ..::  breadcrumb  start ::.. -->
            <x-breadcrumb title='{{ isset($title) ? $title : "" }}' subTitle='{{ isset($subTitle) ? $subTitle : "" }}' />
            <!-- ..::  header area end ::.. -->

            @yield('content')
        
        </div>
        <!-- ..::  footer  start ::.. -->
        <x-footer />
        <!-- ..::  footer area end ::.. -->

    </main>

    <!-- ..::  scripts  start ::.. -->
    <x-script  script='{!! isset($script) ? $script : "" !!}' />
    <!-- ..::  scripts  end ::.. -->



<style>
#aadharFrontPreview {
    background-image: url(../assets/images/no-image.jpg);
    background-size: cover;
    background-repeat: no-repeat;
    background-position: center;
}
#aadharBackPreview {
    background-image: url(../assets/images/no-image.jpg);
    background-size: cover;
    background-repeat: no-repeat;
    background-position: center;
}
#panCardPreview {
    background-image: url(../assets/images/no-image.jpg);
    background-size: cover;
    background-repeat: no-repeat;
    background-position: center;
}
</style>


 @if(session('error'))
    var myModal = new bootstrap.Modal(document.getElementById('depositModel'));
    myModal.show();
@endif
</script>



</body>

</html>