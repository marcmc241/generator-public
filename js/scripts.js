var numof=0;

window.onload = function() {
  if (principal==1) {
    getofferlist();
    getprogramadas()
    chartoffersbyday();
    if (init==1) {
        $('header').addClass("animated slideInDown");
        $('#lista').addClass("animated fadeIn");
        $('#ofertas').addClass("animated fadeIn");
        $('#enviar').addClass("animated fadeIn");
    }
  }
};

window.addEventListener("beforeunload", function (event) {
    var mainloader="<div id='mainloader' class='loader'></div>";
  showalertoferta("<p>Procesando...</p>"+mainloader);
  $('#mainloader').show();
});

function copiar(){
	var textBox = document.getElementById("result");
        textBox.select();
    try {
    var successful = document.execCommand('copy');
    var msg = successful ? 'successful' : 'unsuccessful';
    //console.log('Copying text command was ' + msg);
    if (msg=='unsuccessful') {alert('Oops, no se puede copiar en este navegador');
	}else if (msg=='successful') {copiado();};
  } catch (err) {
    alert('Oops, no se puede copiar en este navegador');
  }
}

function copiado(){
	document.getElementById("copiar").style.backgroundColor = "#AFA";
	setTimeout(function(){document.getElementById("copiar").style.backgroundColor = "#FEFEFE";},2000);
}


//MENSAJES SISTEMA
setTimeout(function(){$("#mensaje").removeClass("open");},3500);

function searcher(){
        // Declare variables
        var input, filter, ul, li, a, i;
        input = document.getElementById('search');
        filter = input.value.toUpperCase();
        filter = filter.replace(/\s+/g, '');
        ul = document.getElementById("ul");
        li = ul.getElementsByClassName('producto');

        
        var hd = document.getElementsByClassName('header2');
        if (filter!="" && filter.length>=3) {//si hay texto y es mayor de 3 caracteres escondemos los nombres categorias
            
            for (i = 0; i < hd.length; i++){
                if (hd[i].style.display != 'none') {//si estan visibles
                    $(hd[i]).slideUp("fast");//ocultamos
                }
            }
            // Loop through all list items, and hide those who don't match the search query
            for (i = 0; i < li.length; i++) {
                a = li[i].getElementsByTagName("a")[0];
                
                if (a.innerHTML.toUpperCase().replace(/\s/g, '').indexOf(filter) > -1 || a.getAttribute('data-tag').toUpperCase().replace(/\s/g, '').indexOf(filter) > -1) {//if contains the string
                    if (li[i].style.display == 'none') {//si NO estan visibles
                        $(li[i]).slideDown("fast");//mostrar
                    }
                } else {//si no contiene la busqueda
                    if (li[i].style.display != 'none') {//si estan visibles
                        $(li[i]).slideUp("fast");//ocultar
                    }
                }
            }
        }else{//si no hay texto o es inferior a 3 caracteres
            for (i = 0; i < li.length; i++) {//escondemos productos
                if (li[i].style.display != 'none') {//si estan visibles
                    li[i].style.display = 'none';//ocultamos
                }
            }
            for (i = 0; i < hd.length; i++){//mostramos nombre categoria
                
                   $(hd[i]).slideDown();//mostramos
                
            }

        }
    }

function tgle(cat){//toggle categories from main product selector
        var x = document.getElementsByClassName('c'+cat);
        for (i = 0; i < x.length; i++){
                
                    $(x[i]).slideToggle();
                
            }
    }


function chartoffersbyday(){
        $.ajax({//show chart
                url:   'data/chartoffersbyday.php',
                type:  'get',
                beforeSend: function () {
                
                },
                success:  function (response) {
                        var resposta=JSON.parse(response);
                        var fechas = [];
                        var nofertas = [];
                        for (var i = 0; i < resposta.length; i++) {
                            fechas.push(resposta[i].day);
                            nofertas.push(parseInt(resposta[i].nofertas));
                        }
                        //fechas=fechas.reverse();
                        //nofertas=nofertas.reverse();
                        var myChart = Highcharts.chart('chartoffersbyday', {
                        chart: {
                            backgroundColor: 'rgba(0,0,0,0)',
                            type: 'line'

                        },
                        title: {
                            text: 'Ofertas creadas por día'
                        },
                        xAxis: {
                            categories: fechas
                        },
                        yAxis: {
                            title: {
                                text: 'nº ofertas'
                            }
                        },
                         plotOptions: {
                        line: {
                            dataLabels: {
                                enabled: true
                            },
                        }
                        },
                        series: [{
                        name: 'número de ofertas',
                        color: '#f6ca48',
                        data: nofertas
                    }]
                    });
                },
                error: function(e) {
                    //called when there is an error
                    console.log(e.message);
                    //TO DO: show error message
                }
                
        });
}

var listelement;
var nom;
var desc;
function crearoferta(productid) {
    detalleProducto(productid);
    var ul, li, a, i;
        ul = document.getElementById("ul");
        li = ul.getElementsByClassName('producto');
        var hd = document.getElementsByClassName('header2');
            for (i = 0; i < hd.length; i++){
                if (hd[i].style.display != 'none') {//si estan visibles
                    $(hd[i]).slideUp("fast");//ocultamos
                }
            }
            // Loop through all list items, and hide those who don't match the search query
            for (i = 0; i < li.length; i++) {
                a = li[i].getElementsByTagName("a")[0];
                if ($(a).data("id") == productid) {//if dataid is the same as productid. Es el producto que queremos crear oferta
                    if (li[i].style.display == 'none') {//si NO esta visible
                        $(li[i]).slideDown("fast");//mostrar
                        
                    }
                    listelement = li[i];
                } else {//si no contiene el dataid
                    if (li[i].style.display != 'none') {//si estan visibles
                        $(li[i]).slideUp("fast");//ocultar
                    }
                }
            }
    $("#searchform").slideUp("fast");//ocultar
    $("#utilidades").slideUp("fast");
    $(listelement).attr('id', 'ofertaheader');
    $("#idpform").val(productid);
    
    $("#ultimasofertas").hide();
    $("#editoffer").hide();
    $("#closebtn").delay( 300 ).show();
    $(".main").addClass("layout2");
    $("#enviar").addClass("layout2");
    $("#submitbtns").delay( 1100 ).slideDown();
    $("#ofertapreview").delay( 1000 ).slideDown();
    $("#construiroferta").delay( 100 ).slideDown();
}

function closeoferta(){
    $(".main").removeClass("layout2");
    $("#enviar").removeClass("layout2");
    $("#closebtn").hide();//treure btn
    $("#searchform").delay( 300 ).slideDown("fast");//mostrar
    $(listelement).attr('id', '');//treure id
    $(listelement).css("background-image","url('')");
    $("#previewimg").attr("src","");
    $("#chart").slideUp();
    $("#construiroferta").slideUp("fast");
    $("#submitbtns").hide();
    $("#ofertapreview").hide();
    $("#ultimasofertas").delay( 300 ).slideDown();
    $("#utilidades").slideDown("fast");
    searcher();

    //reset all form elements
    $('#color').val( '' );
    $('#comentario').val( '' );
    $('#link').val( '' );
    $('#preciohabitual').val( '' );
    $('#preciooferta').val( '' );
    $('#tipooferta').val( '' );
    $('#cupon').val( '' );
    $('#envio').val( '' );
    $('#garantia').val( '' );
    $("#checkbox").prop("checked", true);
    $("#checkbox2").prop("checked", false);
    $("#tienda").text("");
    checktienda();
    opencupon();
    desc="";
    nom="";
    $("#previewcontent").html("");

    //reset program date form just in case user clicked it
    /*$('#dataprogram').slideUp();
    $('#closeprogram').hide();
    $("#dataprogram").removeAttr('required');
    $('#programaroferta').removeAttr("onsubmit");
    $('#programaroferta').attr("onclick","programbtn();");
    $('#moveroferta').slideDown();
    $('#publicaroferta').slideDown();*/
    if ($("#editproddiv").css("display") != 'none') {
        $("#editproddiv").slideUp();
    }
    
}

function detalleProducto(id){
        var parametros = {
            "producto" : id
        };
        $.ajax({//product ID
                data:  parametros,
                url:   'data/detalleproducto.php',
                type:  'get',
                beforeSend: function () {
                        //TO DO: SHOW LOADER
                },
                success:  function (response) {
                        var resposta=JSON.parse(response);
                        $(listelement).css("background-image","url('https://oxmf.club/images_s/"+resposta.producto[0].foto+"')");//image of product
                        $("#previewimg").attr("src","https://oxmf.club/images_s/"+resposta.producto[0].foto);
                        desc=resposta.producto[0].descripcion;
                        nom=resposta.producto[0].nombrep;
                        buildpreview();
                        /*Fill edit product form*/
                        $("#editprodcategoria").val(resposta.producto[0].categoria);
                        $("#editprodnombre").val(resposta.producto[0].nombrep);
                        $("#editproddescripcion").val(resposta.producto[0].descripcion);
                        $("#editprodid").val(resposta.producto[0].Idp);
                        $("#editprodtags").val(resposta.producto[0].tags);
                },
                error: function(e) {
                    //called when there is an error
                    console.log(e.message);
                    //TO DO: show error message
                }
                
        });

        var parametros = {
            "producto" : id
        };
        $.ajax({//show chart
                data:  parametros,
                url:   'data/chartdata.php',
                type:  'get',
                beforeSend: function () {
                       $("#chartloader").fadeIn();//show chart loader
                },
                success:  function (response) {
                    $("#chartloader").fadeOut();//hide chart loader
                    $("#chart").delay( 300 ).slideDown();
                    
                        var resposta=JSON.parse(response);
                        var fechas = [];
                        var esphs = [];
                        var espos = [];
                        for (var i = 0; i < resposta.length; i++) {
                            fechas.push(resposta[i].fecha);
                            esphs.push(parseFloat(resposta[i].esph));
                            espos.push(parseFloat(resposta[i].espo));
                        }
                        fechas=fechas.reverse();
                        esphs=esphs.reverse();
                        espos=espos.reverse();
                        var myChart = Highcharts.chart('chart', {
                        chart: {
                            backgroundColor: 'rgba(0,0,0,0)',
                            type: 'line'
                        },
                        title: {
                            text: 'Evolución de Precios'
                        },
                        subtitle: {
                            text: '(últimas '+resposta.length+' ofertas)'
                        },
                        xAxis: {
                            categories: fechas
                        },
                        yAxis: {
                            title: {
                                text: 'Precio (€)'
                            }
                        },
                         plotOptions: {
                        line: {
                            dataLabels: {
                                enabled: true
                            } //,enableMouseTracking: false
                        }
                        },
                        series: [{
                        name: 'Precio Habitual',
                        color: '#f6ca48',
                        data: esphs
                    }, {
                        name: 'Precio Oferta',
                        color: '#5059d2',
                        data: espos
                    }]
                    });
                        //myChart.redraw()
                },
                error: function(e) {
                    //called when there is an error
                    console.log(e.message);
                    //TO DO: show error message
                }
                
        });
}

function checktienda() {
    var link = $("#link").val();
    if (link=="") {
        $("#tiendacheck").slideUp("fast");
        buildpreview();
    }else{
        var parametros = {
            "link" : link
        };
        $.ajax({
                data:  parametros,
                url:   'data/checktienda.php',
                type:  'get',
                beforeSend: function () {
                    $("#tiendacheck").html("<i class='small material-icons checktiendaicon'>cached</i><p>Identificando tienda...</p>");
                    $("#tiendacheck").slideDown("fast");
                },
                success:  function (response) {
                    var resposta;
                    try {
                       resposta=JSON.parse(response);
                       $("#tiendacheck").html("<i class='small material-icons good checktiendaicon'>check</i><p id='tienda'>"+resposta.tienda[0].nombre+"</p>");
                       buildpreview();
                    }
                    catch (err) {
                      $("#tiendacheck").html("<i class='small material-icons error checktiendaicon'>error</i><p>&nbsp;"+response+"</p>");
                    }
                },
                error: function(jqXHR,estado,error) {
                    //called when there is an error
                    $("#tiendacheck").html("<i class='small material-icons error checktiendaicon'>error</i><p> "+e.message+"</p>");
                    console.log(estado.message);
                    console.log(error.message);
                    //TO DO: show error message
                }
                
        });
    }
}

function echecktienda() {
    console.log("triggered");
    var link = $("#elink").val();
    if (link=="") {
        $("#etiendacheck").slideUp("fast");
    }else{
        var parametros = {
            "link" : link
        };
        $.ajax({
                data:  parametros,
                url:   'data/checktienda.php',
                type:  'get',
                beforeSend: function () {
                    $("#etiendacheck").html("<i class='small material-icons checktiendaicon'>cached</i><p>Identificando tienda...</p>");
                    $("#etiendacheck").slideDown("fast");
                },
                success:  function (response) {
                    var resposta;
                    try {
                       resposta=JSON.parse(response);
                       $("#etiendacheck").html("<i class='small material-icons good checktiendaicon'>check</i><p id='tienda'>"+resposta.tienda[0].nombre+"</p>");
                    }
                    catch (err) {
                      $("#etiendacheck").html("<i class='small material-icons error checktiendaicon'>error</i><p>&nbsp;"+response+"</p>");
                    }
                },
                error: function(jqXHR,estado,error) {
                    $("#etiendacheck").html("<i class='small material-icons error checktiendaicon'>error</i><p> "+e.message+"</p>");
                    console.log(estado.message);
                    console.log(error.message);
                }
                
        });
    }
}

function opencupon(){
    var opcup = $("#tipooferta option:selected").data("cupon");
    if (opcup==1) {
        if ( $("#cupongroup").css('display') == 'none') {
            $("#cupongroup").slideDown();
            $("#cupon").attr('required', 'true');
        }
    }else{
        if ( $("#cupongroup").css('display') != 'none') {
            $("#cupongroup").slideUp();
            $("#cupon").removeAttr('required');
        }
    }
}

function eopencupon(){
    var opcup = $("#etipooferta option:selected").data("cupon");
    if (opcup==1) {
        if ( $("#ecupongroup").css('display') == 'none') {
            $("#ecupongroup").slideDown();
            $("#ecupon").attr('required', 'true');
        }
    }else{
        if ( $("#ecupongroup").css('display') != 'none') {
            $("#ecupongroup").slideUp();
            $("#ecupon").removeAttr('required');
        }
    }
}

function buildpreview() {
    var preview = original.replace(/(?:\\)/g, '');//delete \\ bars
    preview = preview.replace('$nombre', nom);
    preview = preview.replace('$color', $( "#color option:selected" ).text());
    if ($("#comentario").val()!=0 || $("#comentario").val()!="") {
        preview = preview.replace('$comentario', "<br><br>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;"+$( "#comentario option:selected" ).text());
    }else{
        preview = preview.replace('$comentario', "");
    }
    preview = preview.replace('$descripcion', desc);
    preview = preview.replace('$tienda', $("#tienda").text());
    preview = preview.replace('$precioh', $("#preciohabitual").val());
    preview = preview.replace('$precioo', $("#preciooferta").val());
    if ($("#tipooferta").val()!=0 && $( "#tipooferta option:selected" ).text()!="") {
        preview = preview.replace('$tipooferta', "<br>&#128203; "+$( "#tipooferta option:selected" ).text());
    }else{
        preview = preview.replace('$tipooferta', "");
    }
    if ($("#cupon").prop('required')==true) {
        preview = preview.replace('$cupon', $("#cupon").val());
    }else{
        preview = preview.replace('$cupon', "");
    }
    if ($("#envio").val()!=0 || $("#envio").val()!="") {
        preview = preview.replace('$envio', "<br>"+$( "#envio option:selected" ).data( "flag" )+" Envío desde "+$( "#envio option:selected" ).text());
    }else{
        preview = preview.replace('$envio', "");
    }

    if ($("#garantia").val()!=0 || $("#garantia").val()!="") {
        preview = preview.replace('$garantia', "<br>"+$( "#garantia option:selected" ).data( "flag" )+" Garantía "+$( "#garantia option:selected" ).text());
    }else{
        preview = preview.replace('$garantia', "");
    }

    if ($("#comentario2").val()!=0 || $("#comentario2").val()!="") {
        preview = preview.replace('$com2', "<br>&#128196; "+ $("#comentario2").val());
    }else{
        preview = preview.replace('$com2', "");
    }
    preview = preview.replace('$bitlink', "[se genera posteriormente]");
    preview = preview.replace('$oxmflink', "/#/????");
    $("#previewcontent").html(preview);

    if (parseFloat($("#preciohabitual").val())<=parseFloat($("#preciooferta").val())) {
        $("#previewmensaje").slideDown();
    }else{
        $("#previewmensaje").slideUp();
    }
}

function editprod() {
    $("#construiroferta").slideUp();
    $("#editproddiv").slideDown();
}

function nextoffers() {
    numof=numof+25;
    getofferlist();
}

function previousoffers() {
    if (numof>=25) {
        numof=numof-25;
        getofferlist();
    }
}

function getofferlist() {
    $.ajax({
            
            url:   'data/getofferlist.php?numof='+numof,
            type:  'get',
            beforeSend: function () {
                $("#offerlist").append("<div class='loader'></div>");
                $("#offerlist li").remove();
            },
            success:  function (response) {
                var resposta;
                try {
                    $("#offerlist .loader").remove();
                   resposta=JSON.parse(response);
                   $("#offerlist li").remove();
                   for(var i in resposta.oferta) {
                    var clicks = "";
                    if (resposta.oferta[i].estado=="Programada"&&resposta.oferta[i].fprogram!=null) {fprogram=": "+resposta.oferta[i].fprogram}else{fprogram=""}
                    if (resposta.oferta[i].clicks!=null) {clicks=` - &#128433;&#65039; `+resposta.oferta[i].clicks;}
                    var oli = `<li><a class='offerlistA' href='#' onclick='viewoferta(`+resposta.oferta[i].Ido+`, this, event);' data-id='`+resposta.oferta[i].Ido+`'>
                    <p class='ofertapublicadatitle'>`+resposta.oferta[i].nombrep+`</p>
                    <p class='subtitle'><b>`+resposta.oferta[i].precioO+`€</b> - `+resposta.oferta[i].tienda+` - 
                    <time datetime='`+resposta.oferta[i].time+`' title='`+resposta.oferta[i].time_title+`'>`+resposta.oferta[i].time_ago+`</time> - `+resposta.oferta[i].estado+fprogram+clicks+`</p>
                    <i class='small material-icons'>arrow_drop_down</i></a></li>`;
                    $("#offerlist").append(oli);
                    }
                    var range=resposta.range;
                    $("#range").html(range);
                }
                catch (err) {
                    //alert("error");
                    $("#offerlist .loader").remove();
                    $("#offerlist").append("<i class='small material-icons error checktiendaicon'>error</i><p>&nbsp;"+response+"</p>");
                }
            },
            error: function(jqXHR,estado,error) {
                //$("#offerlist .loader").remove();
                //called when there is an error
                alert("err");
                $("#offerlist").append("<p class='error'><i class='small material-icons error checktiendaicon'>error</i> "+error.message+" error, recarga la página (probablemente se te haya ido la conexión)</p>");
                console.log(estado.message);
                console.log(error.message);
            }
            
    });
}

function getprogramadas() {
    var parametros = {
            "n" : "25"
        };
    $.ajax({
            data:  parametros,
            url:   'data/getprogramadas.php',
            type:  'get',
            beforeSend: function () {
                $("#programadas").append("<div class='loader'></div>");
                $("#programadas li").remove();
                $("#programadas .subtitle").remove();
            },
            success:  function (response) {
                var resposta;
                try {
                    $("#programadas .loader").remove();
                   resposta=JSON.parse(response);
                   var ind=0;
                   for(var i in resposta.oferta) {
                    if (resposta.oferta[i].estado=="Programada"&&resposta.oferta[i].fprogram!=null) {fprogram=resposta.oferta[i].fprogram}else{fprogram=""}
                    var oli = `<li>
                    <p class='subtitle'>`+resposta.oferta[i].nombrep+`</p>
                    <p class='subtitle'>`+resposta.oferta[i].precioO+`€ - `+resposta.oferta[i].tienda+` - <b>`+fprogram+`</b></p>
                    </li>`;
                    $("#programadas").append(oli);
                    ind++;
                    }
                    if (ind==0) {
                        var oli = `<p class='subtitle'>No hay ofertas programadas</p>`;
                        $("#programadas").append(oli);
                    }
                }
                catch (err) {
                    //alert("error");
                    $("#programadas .loader").remove();
                    $("#programadas").append("<i class='small material-icons error checktiendaicon'>error</i><p>&nbsp;"+response+"</p>");
                }
            },
            error: function(jqXHR,estado,error) {
                //$("#programadas .loader").remove();
                //called when there is an error
                alert("err");
                $("#programadas").append("<p class='error'><i class='small material-icons error checktiendaicon'>error</i> "+error.message+" error, recarga la página (probablemente se te haya ido la conexión)</p>");
                console.log(estado.message);
                console.log(error.message);
            }
            
    });
}

function viewoferta(ido,element,event){
    event.preventDefault();
    elementp=$(element).parent();
    var parametros = {
            "ido" : ido
        };
        $.ajax({
                data:  parametros,
                url:   'data/getoferta.php',
                type:  'get',
                beforeSend: function () {
                    $(element).append("<div class='loader'></div>")
                },
                success:  function (response) {
                    var resposta;
                    try {
                        $("#offerlist .loader").remove();
                       resposta=JSON.parse(response);
                       var template = resposta.oferta[0].template;
                       
                       template = template.replace(/(?:\\)/g, '');//delete \\ bars
                       //template = template.replace(/<(?:.|\n)*?>/gm, '');
                       template = template.replace(/(?:\r\n|\r|\n)/g, '<br />');//convert \n to <br />
                       var optionbtns1 = "";
                       var optionbtns2 = "";
                       var optionbtns3 = "";
                       var optionbtns4 = "<button class='ofertalistbtn tooltip' data-a='Editar' onclick='editaofertalist("+ido+");'><i class='small material-icons'>edit</i></button>";
                       if (resposta.oferta[0].estado == null) {
                       }else if(resposta.oferta[0].estado == 1){//pendiente
                        if (a==1) {
                            var optionbtns1 = "<button class='ofertalistbtn tooltip' data-a='Publicar' onclick='publicaofertalist("+ido+");'><i class='small material-icons'>present_to_all</i></button>";
                            var optionbtns2 = "<button class='ofertalistbtn tooltip' data-a='Programar' onclick='programaofertalist("+ido+");'><i class='small material-icons'>add_alarm</i></button>";
                        }
                        var optionbtns3 = "<button class='ofertalistbtn tooltip' data-a='Eliminar' onclick='eliminaofertalist("+ido+");'><i class='small material-icons'>delete</i></button>";
                       }else if(resposta.oferta[0].estado == 2){//publicada
                        if (a==1) {
                            var optionbtns2 = "<button class='ofertalistbtn tooltip' data-a='Responder' onclick='replyofertalist("+ido+");'><i class='small material-icons'>reply</i></button>";
                        }
                        var optionbtns3 = "<button class='ofertalistbtn tooltip' data-a='Eliminar' onclick='eliminaofertalist("+ido+");'><i class='small material-icons'>delete</i></button>";
                        var optionbtns1 = "<button class='ofertalistbtn tooltip' data-a='Agotado' onclick='agotadoofertalist("+ido+");'><i class='small material-icons'>remove_shopping_cart</i></button>";
                       }else if(resposta.oferta[0].estado == 3){//programada
                        if (a==1) {
                        var optionbtns1 = "<button class='ofertalistbtn tooltip' data-a='Eliminar programación' onclick='eliminaprogramacion("+ido+");'><i class='small material-icons'>alarm_off</i></button>";
                        }
                        var optionbtns2 = "<button class='ofertalistbtn tooltip' data-a='Eliminar' onclick='eliminaofertalist("+ido+");'><i class='small material-icons'>delete</i></button>";
                       }else if(resposta.oferta[0].estado == 4){//agotada
                        if (a==1) {
                            var optionbtns2 = "<button class='ofertalistbtn tooltip' data-a='Responder' onclick='replyofertalist("+ido+");'><i class='small material-icons'>reply</i></button>";
                        }
                        var optionbtns1 = "<button class='ofertalistbtn tooltip' data-a='NO Agotado' onclick='noagotadoofertalist("+ido+");'><i class='small material-icons'>shopping_cart</i></button>";
                        var optionbtns3 = "<button class='ofertalistbtn tooltip' data-a='Eliminar' onclick='eliminaofertalist("+ido+");'><i class='small material-icons'>delete</i></button>";
                       }else if(resposta.oferta[0].estado == 5){//eliminada
                        var optionbtns1 = "";
                        var optionbtns2 = "";
                        var optionbtns3 = "";
                        var optionbtns4 = "";
                       }
                       
                       $(elementp).append(/*closeofertabtn+*/"<div class='offerlistoffer' data-id='"+ido+"'>"+template+"</div><div class='offerlistbtndiv'  data-id='"+ido+"'>"+optionbtns1+optionbtns2+optionbtns3+optionbtns4+"</div>");
                       $(".offerlistoffer").slideDown();
                       $(".offerlistbtndiv").slideDown();
                       $(".offerlistA[data-id='"+ido+"'] i").html("arrow_drop_up");
                       $(".offerlistA[data-id='"+ido+"']").removeAttr("onclick");
                       $(".offerlistA[data-id='"+ido+"']").attr("onclick","hideoferta("+ido+",event);");
                    }
                    catch (err) {
                        $("#offerlist .loader").remove();
                        $(elementp).append("<i class='small material-icons error checktiendaicon'>error</i><p>&nbsp;"+response+"</p>")
                    }
                },
                error: function(jqXHR,estado,error) {
                    $("#offerlist .loader").remove();
                    //called when there is an error
                    $(elementp).html("<p class='error'><i class='small material-icons error checktiendaicon'>error</i> "+error.message+" error, recarga la página (probablemente se te haya ido la conexión)</p>");
                    console.log(estado.message);
                    console.log(error.message);
                }
                
        });
}

function hideoferta(ido,event) {//hide offer form offer list
    event.preventDefault();
    $(".offerlistA[data-id='"+ido+"'] i").html("arrow_drop_down");
    $(".offerlistA[data-id='"+ido+"']").removeAttr("onclick");
    $(".offerlistA[data-id='"+ido+"']").attr("onclick","viewoferta("+ido+", this, event);");
    $(".offerlistoffer[data-id='"+ido+"']").slideUp("fast", function() { $(this).remove(); });
    $(".offerlistbtndiv[data-id='"+ido+"']").slideUp("fast", function() { $(this).remove(); });
}
//<i class='small material-icons'>cancel</i>
var btncancelar = "<button class='cancelbtn' onclick='cancelaroferta();'>Cancelar</button>";

function publicaofertalist(ido) {
    var form = `<form id='publicaofertalist' method='post' action='actionform.php?w=4'>
            <input id="checkboxpublicaofertalist" class="checkbox publicar" name="checkboxpublicaofertalist" type="checkbox">
            <label for="checkboxpublicaofertalist" id="checkbox-label" class="checkbox-label publicar">Silenciar</label>
            <input type='hidden' name='ido' value='`+ido+`'>
            <input type='submit' name='all' value='Publicar'>
            <input type='submit' name='web' value='Publicar solo en la web'>
            </form>`;
    showalertoferta("<p>Estás seguro de publicar esta oferta?</p>"+form+btncancelar);
}

function programaofertalist(ido){
    var title = "<p>Programar oferta</p>";
    var form = `<form id='programaofertalist' method='post' action='actionform.php?w=2'>
        <input id='dataprogram2' type='datetime-local' name='dataprogram' min='' max='' value='' step='300' pattern='[0-9]{4}-[0-9]{2}-[0-9]{2}T[0-9]{2}:[0-9]{2}' required>
        <input id="checkboxprooflist" class="checkbox publicar" name="checkboxprooflist" type="checkbox">
        <label for="checkboxprooflist" id="checkbox-label" class="checkbox-label publicar">Silenciar</label>
        <input type='hidden' name='ido' value='`+ido+`'>
        <input type='submit' value='Programar'></form>`;
    //var btnprogramar = ""
    var now = Date.now() + 15E4,//round date to next 5 minutes
    cont = now % 3E5;
    rounded = new Date(15E4>=cont?now-cont:now+3E5-cont);

    showalertoferta(title+form+btncancelar);
    $('#dataprogram2')[0].valueAsNumber = rounded.setSeconds(0,0)+3600000/*+1h*/;//set date
    $('#dataprogram2').attr("min",$('#dataprogram2').val());

}

function eliminaprogramacion(ido) {
    var form = `<form id='desprogramaofertalist' method='post' action='actionform.php?w=3'>
                            <input type='hidden' name='ido' value='`+ido+`'>
                            <input type='submit' value='Desprogramar'></form>`;
    showalertoferta("<p>Eliminar programación?</p>"+btncancelar+form);
}

function agotadoofertalist(ido) {
    var form = `<form id='agotadoofertalist' method='post' action='actionform.php?w=5'>
        <input type='hidden' name='ido' value='`+ido+`'>
        <input type='submit' value='Agotado'></form>`;
    showalertoferta("<p>Marcar como agotado?</p>"+form+btncancelar);
}

function noagotadoofertalist(ido) {
    var form = `<form id='noagotadoofertalist' method='post' action='actionform.php?w=6'>
        <input type='hidden' name='ido' value='`+ido+`'>
        <input type='submit' value='No agotado'></form>`;
    showalertoferta("<p>Marcar como no agotado?</p>"+form+btncancelar);
}

function replyofertalist(ido) {
    var form = `<form id='replyofertalist' method='post' action='actionform.php?w=8'>
        <textarea form='replyofertalist' name='msg'></textarea>
        <input type='hidden' name='ido' value='`+ido+`'>
        <input type='submit' value='Responder'></form>`;
    showalertoferta("<p>Responder a la oferta</p>"+form+btncancelar);
}

function eliminaofertalist(ido) {
    var form = `<form id='eliminarofertalist' method='post' action='actionform.php?w=7'>
        <input type='hidden' name='ido' value='`+ido+`'>
        <input type='submit' value='Eliminar'></form>`;
    showalertoferta(`<p>Eliminar la oferta?</p>
        <p class='smaller'>Esto la eliminará del sistema, del canal de telegram y de twitter si está publicada.</p>`+form+btncancelar);
}

function editaofertalist(ido) {
    var parametros = {
            "ido" : ido
        };
        $.ajax({
                data:  parametros,
                url:   'data/getoferta.php',
                type:  'get',
                beforeSend: function () {
                    $('#editoffer').prepend("<div class='loader'></div>");
                    $('#eofertaform').hide();
                    
                },
                success:  function (response) {
                    var resposta;
                    try {
                        $("#editoffer .loader").remove();
                       resposta=JSON.parse(response);
                       
                       var optionbtns1 = "";
                       var optionbtns2 = "";
                       var optionbtns3 = "";
                       $("#edittitle").html(resposta.oferta[0].nombrep);
                       $("#eido").val(ido);
                       $("#ecolor").val(resposta.oferta[0].color).change();
                       $("#ecomentario").val(resposta.oferta[0].comentario).change();
                       $("#elink").val(resposta.oferta[0].enlace);
                       $("#epreciohabitual").val(resposta.oferta[0].precioH);
                       $("#epreciooferta").val(resposta.oferta[0].precioO);
                       $("#etipooferta").val(resposta.oferta[0].tipooferta).change();
                       $("#ecupon").val(resposta.oferta[0].cupon);
                       $("#eenvio").val(resposta.oferta[0].envio).change();
                       $("#egarantia").val(resposta.oferta[0].garantia).change();
                       $("#ecomentario2").val(resposta.oferta[0].com2);
                       if (resposta.oferta[0].estadistica==1) {
                        $("#echeckbox").prop('checked', true);
                       }
                       $('#eofertaform').slideDown();
                       echecktienda();
                    }
                    catch (err) {
                        $("#editoffer .loader").remove();
                        $('#editoffer').prepend("<i class='small material-icons error checktiendaicon'>error</i><p>&nbsp;"+response+"</p>")
                    }
                },
                error: function(jqXHR,estado,error) {
                    $("#editoffer .loader").remove();
                    //called when there is an error
                    $("#editoffer").html("<p class='error'><i class='small material-icons error checktiendaicon'>error</i> "+error.message+" error, recarga la página (probablemente se te haya ido la conexión)</p>");
                    console.log(estado.message);
                    console.log(error.message);
                }
                
        });
    $("#ultimasofertas").slideUp();
    $('#editoffer').delay( 300 ).slideDown();
}

function closediteoferta() {
    $('#editoffer').slideUp();
    $("#ultimasofertas").delay( 300 ).slideDown();
    //reset all
    $("#edittitle").html("");
    $("#eido").val("");
    $("#ecolor").val("").change();
    $("#ecomentario").val("").change();
    $("#elink").val("");
    $("#epreciohabitual").val("");
    $("#epreciooferta").val("");
    $("#etipooferta").val("").change();
    $("#ecupon").val("");
    $("#eenvio").val("").change();
    $("#egarantia").val("").change();
    $("#ecom2").val("");
    $("#ecom2").prop('checked', false);
    eopencupon();
}

function showalertoferta(html){
    $('#alertoferta').html(html);
    $('#alertbackground').fadeIn();
    $('#alertoferta').fadeIn();
}

function cancelaroferta() {
    $('#alertoferta').fadeOut();
    $('#alertbackground').fadeOut();
}

function publicarmensaje() {
    var btnenviar = `<button onclick="$(\'#publimsgform\').submit();"><i class='small material-icons'>check_circle</i></button>`;
    var textarea = "<form id='publimsgform' action='actionform.php?w=1' method='post'><textarea name='msg' form='publimsgform'></textarea></form>";
    showalertoferta("<p>Publicar mensaje al canal:</p>"+textarea+btncancelar+btnenviar);
}

function publicaroferta2(event) {
    event.preventDefault();
    var btnpublicarweb = "<input type='submit' form='ofertaform' name='publicarweb' value='Publicar solo en la web'>";
    var btnpublicar = "<input type='submit' form='ofertaform' name='publicarall' value='Publicar'>";
    var checkboxpublicar = `<input id="checkbox3" class="checkbox publicar" form='ofertaform' name="checkbox3" type="checkbox">
            <label for="checkbox3" id="checkbox-label" class="checkbox-label publicar">Silenciar</label>`;
    showalertoferta("<p>Estás seguro de publicar esta oferta?</p>"+checkboxpublicar+btnpublicar+btnpublicarweb+btncancelar);
    return false;
}

function programaoferta(event){
    event.preventDefault();
    var title = "<p>Programar oferta</p>";
    var form = `<input id='dataprogram' form='ofertaform' type='datetime-local' name='dataprogram' min='' max='' value='' step='300' pattern='[0-9]{4}-[0-9]{2}-[0-9]{2}T[0-9]{2}:[0-9]{2}' required>
        <input id="checkbox3" class="checkbox publicar" name="checkbox3" type="checkbox">
        <label for="checkbox3" id="checkbox-label" class="checkbox-label publicar">Silenciar</label>`;
    var btnprogramar = "<input type='submit' form='ofertaform' name='programar' value='Programar'>";
    var now = Date.now() + 15E4,//round date to next 5 minutes
    cont = now % 3E5;
    rounded = new Date(15E4>=cont?now-cont:now+3E5-cont);

    showalertoferta(title+form+btncancelar+btnprogramar);
    $('#dataprogram')[0].valueAsNumber = rounded.setSeconds(0,0)+3600000/*+1h*/;
    $('#dataprogram').attr("min",$('#dataprogram').val());

}

function anadirproducto() {
    $('#btnpublicarmensaje').hide();
    $('#btnanadirproducto').hide();
    $('#chartoffersbyday').slideUp();
    $('#programadas').slideUp();
    $('#addproddiv').delay( 300 ).slideDown();
    
}

function closeaddprod() {
    $('#btnpublicarmensaje').delay( 300 ).show();
    $('#btnanadirproducto').delay( 400 ).show();
    $('#chartoffersbyday').delay( 500 ).slideDown();
    $('#programadas').delay( 700 ).slideDown();
    $('#addproddiv').slideUp();
}

function comadd(ctag) {
    var cont = $('#comentario2').val();
    if (ctag=='a') {
        $('#comentario2').val('<a href="">'+cont+'</a>');
    }else if (ctag=='b') {
        $('#comentario2').val('<b>'+cont+'</b>');
    }else if (ctag=='gar') {
        $('#comentario2').val(cont+'Quita la garantía de envío para conseguir el precio');
    }else if (ctag=='global') {
        $('#comentario2').val(cont+'Global Version');
    }else if (ctag=='color') {
        $('#comentario2').val(cont+'Disponible en X colores');
    }
    buildpreview();
    return false;
}
/*

[🎉](URL de la imagen) *Nombre del producto*
 
🔰descripción descripcióndescripció ndescripcióndescripción descripcióndescripcióndes cripcióndes cripcióndescripc ióndescripc ióndescripcióndescripción descripción descripción descripción 

🤖 Tienda: NombreReseller
💵 Precio habitual: +XXX€

✅ Precio OFERTA:  *XXX.XX€*
📋 Cupón: XXXX
🎯 Enlace:  https://goo.gl/XXXXXX


👀 Visto en @ofertasxiaomifansclub

Copyright 2016-17, Marc Masip




[🎉](http://oxmf.club/images/$imagen) $nombre $color $comentario

🔰 $descripcion

🤖 Tienda: $tienda
💵 Precio habitual: +$precioh€

✅ Precio OFERTA:  $precioo€
📋 $tipooferta `$cupon` $envio $garantia
🎯 Enlace:  $bitlink

👀 Visto en @ofertasxiaomifansclub
✨ Más en oxmf.club


<\\a href=\\"http://oxmf.club/images/$imagen\\"\\>&#127881;</a\\> <\\b>$nombre<\\/b> $color <\\b>$comentario<\\/b>

&#128304; $descripcion

&#129302; Tienda: $tienda
&#128181; Precio habitual: +$precioh€

✅ Precio OFERTA:  <\\b>$precioo€<\\/b>
&#128203; $tipooferta <\\code>$cupon<\\/code> $envio $garantia $com2
&#127919; Enlace:  $bitlink

&#128064; Visto en @ofertasxiaomifansclub
✨ Más en oxmf.club
*/