
    $(document).ready(function() {
      $('#distritosConfig').select2({
        placeholder: "Distritos",
        allowClear: false,
        width: '100%'

      });
    });


    document.addEventListener('DOMContentLoaded', function() {
    var tipoEleicoesSelect = document.querySelector('select[name="tipoEleicoes"]');
    var distritosSelect = document.getElementById('distritosConfig');
    var distritosOptions = Array.from(distritosSelect.options); // Converte NodeList em array

    var originalOptions = distritosOptions.map(function(option) {
        return {
            value: option.value,
            text: option.textContent,
            municipio: parseInt(option.getAttribute('data-municipio'))
        };
    });

    tipoEleicoesSelect.addEventListener('change', function() {
        var tipoEleicaoSelecionado = parseInt(tipoEleicoesSelect.value); // Converter para inteiro
        // Remover todas as opções, exceto a primeira (placeholder)
        distritosSelect.innerHTML = '<option disabled selected>Alcance</option>';
        // Adicionar as opções apropriadas com base no tipo de eleição selecionado
        originalOptions.forEach(function(option) {
            if (tipoEleicaoSelecionado === 0 || (tipoEleicaoSelecionado === 1 && option.municipio === 1)) {
                var newOption = document.createElement('option');
                newOption.value = option.value;
                newOption.textContent = option.text;
                newOption.setAttribute('data-municipio', option.municipio);
                distritosSelect.appendChild(newOption);
            }
        });
    });

    // Disparar o evento change inicialmente para configurar as opções baseadas no valor inicial de tipoEleicoes
    tipoEleicoesSelect.dispatchEvent(new Event('change'));
});


 // JavaScript para formatar e exibir datas
 var elementosDataInicio = document.querySelectorAll('.dataInicio');
 var elementosDataFim = document.querySelectorAll('.dataFim');

 function formatarData(elemento, dataHora) {
   var partes = dataHora.split(' : ');
   var data = partes[0];
   var hora = partes[1];

 var horas = hora.split(':');
 var h = horas[0];
  var m = horas[1];


   var partesData = data.split("-");
   var ano = partesData[0];
   var mes = partesData[1];
   var dia = partesData[2];

   // Formatando no padrão "dia/mês/ano : hora"
   elemento.textContent = `${dia}/${mes}/${ano} - ${h}:${m}`;
 }

 // Iterando sobre todos os elementos de início e fim e formatando as datas
 elementosDataInicio.forEach(function(elemento) {
   formatarData(elemento, elemento.textContent);
 });
 elementosDataFim.forEach(function(elemento) {
   formatarData(elemento, elemento.textContent);
 });

//  Para tela de partidos

function updateFileName() {
    const fileInput = document.getElementById('fileInput');
    const fileName = fileInput.files[0].name;
    const button = document.getElementById('emblema');
    button.innerHTML = fileName + ' <i id="uploadIcon" style="margin-bottom: 1% !important; display: none;" class="bi bi-upload"></i>';

}

