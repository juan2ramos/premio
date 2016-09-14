$('input[type="file"]').on('change', function(){

    var element = document.getElementById($(this).attr('id'));
    var data = element.files;
    var file = new FormData();

    if(data[0].type == 'application/pdf'){
        file.append('file', data[0]);
        file.append('_token', $('#token').val());

        $.ajax({
            url: $('#url').val(),
            type: 'POST',
            contentType: false,
            data: file,
            processData: false,
            cache: false,
            beforeSend: function () {
                $('.preload').removeClass("hidden");
            },
            success: function (data) {
                console.log('Se sube archivo de manera exitosa.');
                element.previousElementSibling.innerText = data.route;
                element.nextElementSibling.value = data.route;
                setTimeout(function(){
                    $('.preload').addClass("hidden");
                }, 1000);
            },
            error: function (error) {
                console.log(error);
                alert('Error al cargar los archivos. Por favor vuelva a intentarlo.');
            }
        });
    }
    else{
        alert('Tipo de archivo no permitido. Debe subir un PDF');
    }
});