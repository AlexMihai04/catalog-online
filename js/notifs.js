function notif(type,mess){
    if(type == "error"){
        iziToast.error({
            class:"notificare_error",
            timeout:4000,
            transitionIn: 'fadeInDown',
            transitionOut: 'fadeOutDown',
            closeOnClick:true,
            backgroundColor:'white',
            messageColor:'white',
            icon:'far fa-times-circle',
            iconColor: '#F2CC8F',
            progressBarColor:'#F2CC8F',
            message: mess,
        });
    }else{
        iziToast.success({
            class:"notificare_success",
            timeout:4000,
            transitionIn: 'fadeInDown',
            transitionOut: 'fadeOutDown',
            closeOnClick:true,
            backgroundColor:'white',
            messageColor:'white',
            icon:'fas fa-check-circle',
            iconColor: '#F2CC8F',
            progressBarColor:'#F2CC8F',
            message: mess,
        });
    }
}