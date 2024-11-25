


$(document).ready(function () {
    $('#provincia-select').change(function() {
        var provinciaId = $(this).val();

        console.log("Província selecionada: " + provinciaId); // Log para verificar se a seleção está funcionando

        if (provinciaId) {
            $.ajax({
                url: '/get-districts/' + provinciaId,
                type: 'GET',
                dataType: 'json',
                success: function(data) {
                    console.log("Dados recebidos: ", data); // Log para verificar os dados recebidos
                    $('#distrito-select').empty();
                    $('#distrito-select').append('<option disabled selected>Distrito</option>');
                    $.each(data, function(key, value) {
                        $('#distrito-select').append('<option value="' + value.idDistritos + '">' + value.nome + '</option>');
                    });
                },
                error: function(xhr, status, error) {
                    console.log('Erro ao buscar os distritos:', error); // Log para verificar erros
                }
            });
        } else {
            $('#distrito-select').empty();
            $('#distrito-select').append('<option disabled selected>Distrito</option>');
        }
    });
});

