function validaSenhaSubmit(){
    var password = document.getElementById("senha").value;
    var passwordConfirm = document.getElementById("senha-confirm").value;

    if(password != passwordConfirm){
        document.getElementById("validacao-negada-submit").innerHTML = "Erro: Senhas não correspondentes!"
        return false;
    }else{
        return true;
    }
}