// © 2020 Udrescu Alexandru All Rights Reserved

var app = new Vue({
    el: '#app',
    data: {
        punere_nota:true,
        clase: [],
        elevi : [],
        note_elev:[],
        materii : [],
        absente_elev :[],
        selected:0,
        elev:-1,
        are_note:false,
        are_absente:false,
        vezi_absente:false,
        opened_meniu_clase:true,
        show_loader : false,
        pre_selector : -1
    },
    methods: {
        get_medie_materie(materie){
            var note = 0;
            var suma = 0;
            this.note_elev.forEach(element => {
                if(materie == element.materie){
                    suma+=Number(element.nota);
                    note++;
                }
            });
            return suma/note;
        },
        arata(msj){
            console.log(msj);
        },
        adder(thing){
            if(thing == "absenta"){
                var date = $("#form_pune_absenta").serializeArray();
                $.ajax({
                    type:"POST",
                    crossOrigin: true,
                    url:get_domeniu() + "/methods/posts.php",
                    data:{
                        "action" : "add_absenta_back",
                        'crsf':$('#crsf').val(),
                        'om_selectat' : app.elevi[app.elev].user_id,
                        'materie' : date[0].value,
                        'nume_materie':app.materii[date[0].value-1].nume_materie,
                        'data_primita' : date[1].value
                    },
                    success:function (data)
                    {
                        data = JSON.parse(data);
                        if(data.response == 1){
                            notif("succes",data.mesaj);
                        }else{
                            notif("error",data.mesaj);
                        }
                    }
                })
            }else if(thing == "nota"){
                var date = $("#form_pune_nota").serializeArray();
                $.ajax({
                    type:"POST",
                    crossOrigin: true,
                    url:get_domeniu() + "/methods/posts.php",
                    data:{
                        "action" : "add_nota_back",
                        'crsf':$('#crsf').val(),
                        'om_selectat' : app.elevi[app.elev].user_id,
                        'nota_pusa' : date[0].value,
                        'materie' : date[1].value,
                        'nume_materie':app.materii[date[1].value-1].nume_materie,
                        "data_primita" : date[2].value
                    },
                    success:function (data)
                    {
                        data = JSON.parse(data);
                        if(data.response == 1){
                            notif("succes",data.mesaj);
                        }else{
                            notif("error",data.mesaj);
                        }
                    }
                })
            }
        },
        updater(thing,date){
            if(thing == "elevi"){
                date.sort((a, b) => a.first_name.localeCompare(b.first_name));
                date.forEach(element => {
                    $.ajax({
                        type:"POST",
                        crossOrigin: true,
                        url:get_domeniu() + "/methods/gets.php",
                        data:{
                            "action" : "select_elev",
                            'crsf':$('#crsf').val(),
                            "id_elev" : element.user_id
                        },
                        success:function (data)
                        {
                            if(data){
                                data = JSON.parse(data);
                                var suma = 0;
                                var total_note = 0;
                                for(var i = 0;i<data.length;i++){
                                    suma = Number(suma+Number(data[i].nota));
                                    total_note++;
                                }
                                element.medie_elev = (Math.round((suma/total_note) * 100) / 100).toFixed(2);
                                console.log(element.medie_elev);
                                if(isNaN(element.medie_elev)){
                                    element.medie_elev = '-';
                                }
                            }
                        }
                    })
                });
                setTimeout(() => {
                    this.elevi = date;    
                    this.show_loader = false            
                }, 1500);
            }else if(thing == "materii"){
                this.materii = date;
            }else if(thing == "note"){
                this.note_elev = date;
            }else if(thing == "absente"){
                this.absente_elev = date;
            }else if(thing == "clase"){
                this.clase = date;
                for(var i in this.clase){
                    this.clase[i].nume = this.clase[i].nume.toUpperCase();
                }
            }
        },
        deleter(thing,index){
            if(thing == "scote_din_cls"){
                $.ajax({
                    type:"POST",
                    crossOrigin: true,
                    url:get_domeniu() + "/methods/posts_a.php",
                    data:{
                        "action" : "sterge_din_clasa",
                        'crsf':$('#crsf').val(),
                        "id_clasa" : this.selected,
                        "id_user" : this.elevi[index].user_id
                    },
                    success:function (data)
                    {
                        data = JSON.parse(data);
                        if(data.response == 1){
                            notif("succes",data.mesaj);
                            app.select_clasa(app.selected);
                        }else{
                            notif("error",data.mesaj);
                        }
                    }
                }) 
            }
        },
        select_clasa(selectedIndex) {
            this.selected = 0;
            this.punere_nota = false;
            this.elev = -1;
            this.selected = selectedIndex;
            this.show_loader = true;
            $.ajax({
                type:"POST",
                crossOrigin: true,
                url:get_domeniu() + "/methods/gets.php",
                data:{
                    "action" : "get_elevi",
                    'crsf':$('#crsf').val(),
                    "id_clasa" : selectedIndex
                },
                success:function (data)
                {
                    // console.log(data);
                    dat = JSON.parse(data);
                    app.updater('elevi',dat);
                    if(app.opened_meniu_clase && getWidth() <= 1000){
                        close_nav();
                    }
                }
            })         
        },
        deselect_cls(){
            if(app.opened_meniu_clase && getWidth() <= 1000){
                close_nav();
            }
        },
        nabs_avut(thing,materie){
            var tab = "";
            if(thing == "abs") tab = this.absente_elev;
            else if(thing == "note") tab = this.note_elev;

            var i = 0;
            while(i < 200){
                if(tab[i]){
                    if(tab[i].materie == materie){
                        if(thing == "abs") this.are_absente = true;
                        else if(thing == "note") this.are_note = true;
                        return true;
                    }
                }
                i++;
            }
            return false;
        },
        select_elev(selectedIndex){
            this.elev = selectedIndex;
            // console.log(this.elevi[selectedIndex].user_id);
            $.ajax({
                type:"POST",
                crossOrigin: true,
                url:get_domeniu() + "/methods/gets.php",
                data:{
                    "action" : "get_materii",
                    'crsf':$('#crsf').val()
                },
                success:function (data)
                {
                    app.updater('materii',JSON.parse(data));
                }
            })
            $.ajax({
                type:"POST",
                crossOrigin: true,
                url:get_domeniu() + "/methods/gets.php",
                data:{
                    "action" : "select_elev",
                    'crsf':$('#crsf').val(),
                    "id_elev" : this.elevi[selectedIndex].user_id
                },
                success:function (data)
                {
                    if(data){
                        app.updater('note',JSON.parse(data));
                        notif("succes","Ai selectat elevul !");
                    }
                }
            })
            $.ajax({
                type:"POST",
                crossOrigin: true,
                url:get_domeniu() + "/methods/gets.php",
                data:{
                    "action" : "get_absente",
                    'crsf':$('#crsf').val(),
                    "id_elev" : this.elevi[selectedIndex].user_id
                },
                success:function (data)
                {
                    if(data){
                        app.updater('absente',JSON.parse(data));
                    }
                }
            })
        },
        select_pune_nota(selectedIndex){
            this.elev = selectedIndex;
            $.ajax({
                type:"POST",
                crossOrigin: true,
                url:get_domeniu() + "/methods/gets.php",
                data:{
                    "action" : "get_materii",
                    'crsf':$('#crsf').val()
                },
                success:function (data)
                {
                    app.updater('materii',JSON.parse(data));
                }
            })
        },
        getWidth(){
            return $(window).width();
        },
        getHeight(){
            return $(window).height();
        },
        exista_clasa(indice){
            for(var i in this.clase){
                if(this.clase[i].nume.substr(0, 2) == indice || this.clase[i].nume.substr(0, 2) == indice + ' '){
                    return true;
                }
            }
            return false;
        }
    }
})

function load_clase(){
    notif("succes","Bun venit !")
    $.ajax({
        type:"POST",
        crossOrigin: true,
        url:get_domeniu() + "/methods/gets.php",
        data:{
            "action" : "get_classes",
            'crsf':$('#crsf').val()
        },
        success:function (data)
        {
            data = JSON.parse(data); 
            app.updater('clase',data); 
        }
    })
    $.ajax({
        type:"POST",
        crossOrigin: true,
        url:get_domeniu() + "/methods/gets.php",
        data:{
            "action" : "get_materii",
            'crsf':$('#crsf').val()
        },
        success:function (data)
        {
            app.updater('materii',JSON.parse(data));
        }
    })
}

load_clase();


function open_nav() {
    if(getWidth() > 1000){
        document.getElementById("main").style.marginLeft = "230px";
        document.getElementById("side_bar").style.width = "230px";
        document.getElementById("side_bar").style.display = "block";
        document.getElementById("openNav").style.display = 'none';
        app.opened_meniu_clase = true;
    }else{
        document.getElementById("main").style.marginLeft = "100%";
        document.getElementById("side_bar").style.width = "100%";
        document.getElementById("side_bar").style.display = "block";
        document.getElementById("openNav").style.display = 'none';
        app.opened_meniu_clase = true;
        $("body").addClass("hide-scroll");
    }
}

function close_nav() {
    document.getElementById("main").style.marginLeft = "0";
    document.getElementById("side_bar").style.display = "none";
    document.getElementById("openNav").style.display = "inline-block";
    setTimeout(() => {
        app.opened_meniu_clase = false;
        $("body").removeClass("hide-scroll");
    }, 240);
}

function resize_page(){
    if(getWidth() < 1000 && app.opened_meniu_clase == true){
        close_nav();
    }else if(getWidth() >= 1000){
        open_nav();
    }
    
}

function getWidth() {
    return $(window).width();
}
  
function getHeight() {
    return $(window).height();
}


$( document ).ready(function() {
    setTimeout(() => {
        resize_page();
    }, 300);
});
