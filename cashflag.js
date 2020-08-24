function inicio() {
    var lang = 0;
    // cargar parámetros del json: etiquetas y elementos de forma
    var xmlhttp = new XMLHttpRequest();
    xmlhttp.onreadystatechange = function () {
        if (this.readyState == 4 && this.status == 200) {
            respuesta = JSON.parse(this.responseText);
            localStorage.setItem('lang', lang);
            index = respuesta.index;
            localStorage.setItem('index', JSON.stringify(index));
            localStorage.setItem('suffixes', JSON.stringify(index.cuerpo));
            etiquetas(lang);
        }
    };     
    xmlhttp.open("GET", "./cash-flag-com.json", false);
    xmlhttp.send();
}

function etiquetas(lang) {
    aindex = localStorage.getItem('index');    
    index  = JSON.parse(aindex);
    console.log(index);
    document.title                                  = index.title[lang];
    document.getElementById("div_logo").innerHTML   = index.header.div_logo[lang];
    document.getElementById("a_ingreso").innerHTML  = index.header.a_ingreso[lang];
    document.getElementById("inicio").innerHTML     = index.menu.inicio[lang];
    document.getElementById("ventajas").innerHTML   = index.menu.ventajas[lang];
    document.getElementById("blockchain").innerHTML = index.menu.blockchain[lang];
    document.getElementById("productos").innerHTML  = index.menu.productos[lang];
    document.getElementById("beneficios").innerHTML = index.menu.beneficios[lang];
    document.getElementById("contacto").innerHTML   = index.menu.contacto[lang];
    // document.getElementById("ingreso").innerHTML    = index.menu.ingreso[lang];
    document.getElementById("chat").innerHTML       = index.pie.chat[lang];
}

function actualizaidioma(lang) {
    localStorage.setItem('lang', lang);
    etiquetas(lang);
    id     = localStorage.getItem('active');
    enlace = localStorage.getItem('enlace');
    refresca(id,enlace);
}

function refresca(id,enlace) {
    localStorage.setItem('active', id);
    localStorage.setItem('enlace', enlace);
    lang = localStorage.getItem('lang');
    suffixes = localStorage.getItem('suffixes');
    suffix  = JSON.parse(suffixes);
    document.getElementById("inicio").className = "";
    document.getElementById("ventajas").className = "";
    document.getElementById("blockchain").className = "";
    document.getElementById("productos").className = "";
    document.getElementById("beneficios").className = "";
    document.getElementById("contacto").className = "";

    id.className = "active";

    console.log("./html/"+enlace+suffix.language[lang]+".html")

    document.getElementById("marco").src = "./html/"+enlace+suffix.language[lang]+".html";
    // document.getElementById("marco").reload();
}
