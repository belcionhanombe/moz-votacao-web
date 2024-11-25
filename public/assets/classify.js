let model;
const upload = document.getElementById('upload');
const result = document.getElementById('result');

async function loadModel() {
    try {
        // Caminho relativo ao local do script.js
        const modelUrl = 'model.json';
        // Use o caminho correto relativo à raiz pública
        model = await tf.loadGraphModel(modelUrl);
        console.log("Modelo carregado");
    } catch (error) {
        console.error("Erro ao carregar o modelo:", error);
        result.innerText = "Erro ao carregar o modelo. Verifique o console para mais detalhes.";
    }
}

async function classifyImage() {
    if (!model) {
        alert("Modelo não carregado");
        return;
    }
    const file = upload.files[0];
    if (!file) {
        alert("Por favor, selecione uma imagem");
        return;
    }

    const image = document.createElement('img');
    image.src = URL.createObjectURL(file);
    image.onload = async () => {
        // Certifique-se de que a imagem é carregada corretamente
        const tensor = tf.browser.fromPixels(image).toFloat().expandDims(0).resizeBilinear([224, 224]); // Ajuste o tamanho conforme necessário
        const predictions = await model.predict(tensor).data();
        displayResult(predictions);
    };
}

function displayResult(predictions) {
    const labels = ["Partido A", "Partido B", "Partido C"]; // Substitua com os rótulos reais do seu treinamento
    const maxIndex = predictions.indexOf(Math.max(...predictions));
    result.innerText = `Partido reconhecido: ${labels[maxIndex]}`;
}

loadModel();
