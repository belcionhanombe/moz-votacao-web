
window.onload = function(){    
let erro1 = document.querySelector("#erro1") 
let erro2 = document.querySelector("#erro2") 


if(erro1 && erro1.value != ''){
    toast(erro1.value, "error");
}

if(erro2 && erro2.value != ''){
    toast(erro2.value, "error");
}
}

document.getElementById('btbt').addEventListener('click', function() {
    var selectElement = document.getElementById('distritosConfig');
    if (selectElement.style.display === 'none') {
        selectElement.style.display = 'block';
    } else {
        selectElement.style.display = 'none';
    }
});