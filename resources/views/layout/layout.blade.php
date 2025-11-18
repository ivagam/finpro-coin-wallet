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


<script>
function previewImage(inputId, previewId) {
    const input = document.getElementById(inputId);
    const preview = document.getElementById(previewId);

    input.addEventListener('change', function () {
        const file = input.files[0];
        if (file) {
            const reader = new FileReader();
            reader.onload = function (e) {
                preview.style.backgroundImage = `url(${e.target.result})`;
            };
            reader.readAsDataURL(file);
        }
    });
}

previewImage("aadharFrontUpload", "aadharFrontPreview");
previewImage("aadharBackUpload", "aadharBackPreview");
previewImage("panCardUpload", "panCardPreview");
</script>
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

<script>
document.addEventListener("DOMContentLoaded", function () {
    var activeTab = "{{ session('active_tab') }}";
    if (activeTab) {
        var tabBtn = document.querySelector('#pills-tab button[data-bs-target="#' + activeTab + '"]');
        if (tabBtn) {
            var tab = new bootstrap.Tab(tabBtn);
            tab.show();
        }
    }
});
</script>

</body>

</html>