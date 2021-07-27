// © 2020 Udrescu Alexandru All Rights Reserved

var app = new Vue({
    el: '#login',
    data: {
        eroare:false
    },
    methods: {
        login(){
            var date = $("#login_form").serializeArray();
            var date = $("#login_form").serializeArray();
            console.log(date);
            $.ajax({
                type:"POST",
                crossOrigin: true,
                url:get_domeniu() + "/methods/gets.php",
                data:{
                    "action" : "login",
                    'crsf':$('#crsf').val(),
                    'login_username' : date[0].value,
                    'login_password' : date[1].value
                },
                success:function (data)
                {
                    data = JSON.parse(data);
                    if(data.response == 1){
                        window.location.href = "index.php";
                    }else{
                        notif("error",data.mesaj);
                    }
                }
            })
        }
    }
})

