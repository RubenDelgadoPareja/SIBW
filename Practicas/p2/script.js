function Mostrar_Comentarios(){
    var caja_com = document.getElementById("formato");
    var caja = document.getElementById("caja_de_comentarios");

    if(caja.className=="caja_de_comentarios "){
        caja.classList.add("oculto");
        caja_com.classList.add("oculto");

    }
    else
        caja.className=caja.className.replace("oculto","");
}

function Mostrar_Caja(){
    var caja_com = document.getElementById("formato");

    if(caja_com.className=="formato "){
        caja_com.classList.add("oculto");
    }
    else
        caja_com.className=caja_com.className.replace("oculto","");
}

function validarEmail(valor) {
    if (/^([a-zA-Z0-9_\.\-])+\@(([a-zA-Z0-9\-])+\.)+([a-zA-Z0-9]{2,4})+$/.test(valor)){
        return true;
    }
    else {
     alert("La dirección de email es incorrecta.");
     return false;
    }
  }

function Aceptar_Comentario(){
    var opcion = true;


    var nombre=document.getElementById("nombre");
    if(nombre.value == null || nombre.value == "") opcion=false;

    var correo=document.getElementById("email");
    if(correo.value == null || correo.value == "") opcion=false;

    var texto=document.getElementById("texto_comentario");
    if(texto.value == null || texto.value == "") opcion=false;

    if ( opcion == false ){
        alert("Rellene los datos");
        return false;
    }
    if(validarEmail(correo.value))
        Aniadir_Comentario(nombre,texto);
}

function Aniadir_Comentario(nombre,texto){
    Div = document.createElement("div");

    Div.className = "comentarios";

    Autor = document.createElement("p");
    Autor.className = "nombre";
    Nombre = document.createTextNode(nombre.value);
    Autor.appendChild(Nombre);

    Comentario = document.createElement("p");
    Comentario.className = "texto_comentario";
    Texto = document.createTextNode(texto.value);
    Comentario.appendChild(Texto);

    Fecha = document.createElement("p");

    var date = new Date();
    var mes = date.getMonth()+1;
    var hora = date.getHours();
    var minutos = date.getMinutes();

    Fecha = document.createTextNode(date.getDate()+"/"+mes+"/"+date.getFullYear()+"  "+hora+":"+minutos)

    Div.appendChild(Autor);
    Div.appendChild(Comentario);
    Div.appendChild(Fecha);

    document.getElementById("lista_com").appendChild(Div);

    
}
function censura() {
    const PROHIBIR = '*'
    const INSULTOS = ['Almeida', 'carapolla', 'tonto', 'chulito']
    var inicial = document.getElementById("texto_comentario").value
    var frase = document.getElementById("texto_comentario").value.match(/[a-z'\-]+/gi)
    var separadas = []
    var final = inicial

    for(i = 0; i < frase.length; i++){
        separadas.push(String(frase[i]))
    }

    for(j = 0; j < INSULTOS.length; j++){
        var encontrada = separadas.indexOf(String(INSULTOS[j]))

        if(encontrada != -1){
            final = inicial.replace(separadas[encontrada], PROHIBIR.repeat(separadas[encontrada].length))
        }
    }

    document.getElementById("texto_comentario").value = final
}