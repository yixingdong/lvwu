$(function(){
    var address = $('input[name=uri]').val();
    function sendMsg(){
        $.ajax({
            url: address+'/communicate/phone_code',
            data: {
                'phone':$('input[name=phone]').val(),
                '_token':$('input[name=_token]').val(),
                'todo': $('input[name=todo]').val()
            },
            success: function(data){
                alert(data.info);
            }
        });
    }
    function getPhoneCode(){
        $.ajax({
            url: address+'/tool/cpt_check',
            type: "post",
            data: {
                'cpt':$('input[name=cpt]').val(),
                '_token':$('input[name=_token]').val()
            },
            success: function(data){
                if(data.code == 200){
                    sendMsg();
                }else{
                    alert(data.info);
                }
            }
        });
    }

   $("#get_code").click(function(){
       getPhoneCode();
    });    
});