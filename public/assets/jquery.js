

var paraMesas = document.getElementById("paraMesas").innerHTML;
    $(document).ready(function() {
        $('#local').on('change', function() {
            var localId = $(this).val();
            if(localId) {
                $.ajax({
                    url: '/get-mesas/' + localId,
                    type: 'GET',
                    dataType: 'json',
                    success: function(data) {
                        $('#mesa').empty();
                        $('#mesa').append('<option disabled selected>Selecione a Mesa </option>');
                        $.each(data, function(key, value) {
                            $('#mesa').append('<option value="' + value.id + '">' + value.nome + '</option>');
                        });
                    },
                    error: function(xhr, status, error) {
                        if (xhr.status === 404  && paraMesas==1) {
                            toast("O local selecionado não tem mesas", "error");
                        } else {
                            console.error("Erro na requisição AJAX: " + status + " - " + error);
                        }
                        $('#mesa').empty();
                        $('#mesa').append('<option disabled selected>Selecione a Mesa</option>');
                    }
                });
            } else {
                $('#mesa').empty();
                $('#mesa').append('<option disabled selected>Selecione a Mesa</option>');
            }
        });
    });


