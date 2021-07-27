// © 2020 Udrescu Alexandru All Rights Reserved

var app = new Vue({
    el: '#app',
    data: {
        clase: [],
        materii : [],
        elevi_not_inclasa : [],
        elevi_all : [],
        tot_clase:[],
        loguri:[],
        show:false,
        eroare:false,
        modifica_cont:false,
        elev_sel:-1,
        tip : 0,
        opened_meniu_clase:true
    },
    methods: {
        update_eroare(eroare,tip){
            this.tip = tip;
            this.eroare = eroare;
        },
        toate_clasele(data){
            this.tot_clase = data;
        },
        update_clase(data){
            this.clase = data;
            for(var i in this.clase){
                this.clase[i].nume = this.clase[i].nume.toUpperCase();
            }
        },
        update_eln(elevi){
            elevi.sort((a, b) => a.first_name.localeCompare(b.first_name))
            this.elevi_not_inclasa = elevi;
        },
        update_all_el(elevi){
            this.elevi_all = elevi;
        },
        update_loguri(loguri){
            this.loguri = loguri;
        },
        add_clasa_js(){
            var date = $("#form_add_clasa").serializeArray();
            $.ajax({
                type:"POST",
                crossOrigin: true,
                url:"http://alex-mihai.ro/catalog/methods/posts_a.php",
                data:{
                    "action" : "add_class",
                    'crsf':$('#crsf').val(),
                    'nume_clasa' : date[0].value
                },
                success:function (data)
                {
                    data = JSON.parse(data);
                    if(data.response == 1){
                        notif("succes",data.mesaj);
                        load_clase();
                    }else{
                        notif("error",data.mesaj);
                    }
                }
            })
        },
        add_cont_js(){
            var date = $("#form_add_cont").serializeArray();
            $.ajax({
                type:"POST",
                crossOrigin: true,
                url:"http://alex-mihai.ro/catalog/methods/posts_a.php",
                data:{
                    "action" : "create_acc",
                    'crsf':$('#crsf').val(),
                    'first_name' : date[0].value,
                    'last_name' : date[1].value,
                    'email': date[2].value,
                    'grup' : date[3].value
                },
                success:function (data)
                {
                    data = JSON.parse(data);
                    if(data.response == 1){
                        notif("success",data.mesaj);
                    }else{
                        notif("error",data.mesaj);
                    }
                }
            })
        },
        delete_clasa(index){
            $.ajax({
                type:"POST",
                crossOrigin: true,
                url:"http://alex-mihai.ro/catalog/methods/posts_a.php",
                data:{
                    "action" : "del_class",
                    'crsf':$('#crsf').val(),
                    "id_clasa" : index
                },
                success:function (data)
                {
                    data = JSON.parse(data);
                    if(data.response == 1){
                        notif("succes",data.mesaj);
                        load_clase();
                    }else{
                        notif("error",data.mesaj);
                    }
                }
            })
        },
        delete_cont(index){
            $.ajax({
                type:"POST",
                crossOrigin: true,
                url:"http://alex-mihai.ro/catalog/methods/posts_a.php",
                data:{
                    "action" : "del_acc",
                    'crsf':$('#crsf').val(),
                    "id_elev" : this.elevi_all[index].user_id
                },
                success:function (data)
                {
                    data = JSON.parse(data);
                    if(data.response == 1){
                        notif("succes",data.mesaj);
                        app.get_all_el();
                    }else{
                        notif("error",data.mesaj);
                    }
                }
            })
        },
        add_inclasa(index,event){
            if(!document.getElementById(event.target.id).disabled){
                $.ajax({
                    type:"POST",
                    crossOrigin: true,
                    url:"http://alex-mihai.ro/catalog/methods/posts_a.php",
                    data:{
                        "action" : "add_in_class",
                        'crsf':$('#crsf').val(),
                        "id_elev" : this.elevi_not_inclasa[index].user_id,
                        "id_clasa" : document.getElementById("lista_clasa").value
                    },
                    success:function (data)
                    {
                        data = JSON.parse(data);
                        if(data.response == 1){
                            document.getElementById(event.target.id).classList.remove("selected_class");
                            document.getElementById(event.target.id).classList.add("sel_verde");
                            document.getElementById(event.target.id).innerHTML = "Adaugat";
                            document.getElementById(event.target.id).disabled = true;
                            notif("succes",data.mesaj);
                        }else{
                            notif("error",data.mesaj);
                        }
                    }
                })
            }
        },
        get_all_el(){
            $.ajax({
                type:"POST",
                crossOrigin: true,
                url:"http://alex-mihai.ro/catalog/methods/gets_a.php",
                data:{
                    "action" : "get_all_el",
                    'crsf':$('#crsf').val()
                },
                success:function (data)
                {
                    app.update_all_el(JSON.parse(data));
                }
            })
        },
        get_loguri(){
            $.ajax({
                type:"POST",
                crossOrigin: true,
                url:"http://alex-mihai.ro/catalog/methods/gets_a.php",
                data:{
                    "action" : "get_logs",
                    'crsf':$('#crsf').val()
                },
                success:function (data)
                {
                    app.update_loguri(JSON.parse(data));
                }
            })
        },
        get_el_not(){
            get_not_in_cls();
        },
        remake(){
            this.elevi_not_inclasa = [];
            get_not_in_cls();
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
    $.ajax({
        type:"POST",
        crossOrigin: true,
        url:"http://alex-mihai.ro/catalog/methods/gets.php",
        data:{
            "action" : "get_classes",
            'crsf':$('#crsf').val()
        },
        success:function (data)
        {
            data = JSON.parse(data); 
            app.update_clase(data); 
        }
    })
    $.ajax({
        type:"POST",
        crossOrigin: true,
        url:"http://alex-mihai.ro/catalog/methods/gets_a.php",
        data:{
            "action" : "get_all_classes",
            'crsf':$('#crsf').val()
        },
        success:function (data)
        {
            app.toate_clasele(JSON.parse(data));
        }
    })
}

load_clase();

function get_not_in_cls(){
    $.ajax({
        type:"POST",
        crossOrigin: true,
        url:"http://alex-mihai.ro/catalog/methods/gets_a.php",
        data:{
            "action" : "get_not_in_class",
            'crsf':$('#crsf').val()
        },
        success:function (data)
        {
            app.update_eln(JSON.parse(data));
        }
    })
}


function open_nav() {
    if(getWidth() > 1000){
        document.getElementById("main").style.marginLeft = "230px";
        document.getElementById("side_bar").style.width = "230px";
        document.getElementById("side_bar").style.display = "block";
        document.getElementById("openNav").style.display = 'none';
        app.opened_meniu_clase = true
    }else{
        document.getElementById("main").style.marginLeft = "100%";
        document.getElementById("side_bar").style.width = "100%";
        document.getElementById("side_bar").style.display = "block";
        document.getElementById("openNav").style.display = 'none';
        app.opened_meniu_clase = true
    }
}

function close_nav() {
    document.getElementById("main").style.marginLeft = "0";
    document.getElementById("side_bar").style.display = "none";
    document.getElementById("openNav").style.display = "inline-block";
    app.opened_meniu_clase = false;
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
