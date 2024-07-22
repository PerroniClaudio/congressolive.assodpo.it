
<?php include './components/include.php'; ?>
<?php include './components/config.php' ?>
<?php 

$password_code = $db->quote($_GET['pid']);

try{
    $user_check = $db->query("SELECT uid,password_code_date FROM users WHERE password_code = $password_code");

    if($user_check->rowCount() == 1){

        $user = $user_check->fetch();

        $pw_code_date = DateTime::createFromFormat("Y-m-d H:i:s",$user['password_code_date']);
        $now = new DateTime();
        $diff = $now->getTimestamp() - $pw_code_date->getTimestamp();

        if($diff > 3600){
            ?>
                <script>
                    window.location =  './index.php'
                </script>
            <?php
        }

    }else{
        ?>
            <script>
                window.location =  './index.php'
            </script>
        <?php
    }

}catch(Exception $e){
    echo $e->getMessage();
}


?>


<body class="blue darken-4">
    <div class="container">
        <section class="section section-login row">
            <div class="col m8 offset-m2">
                <div class="card">
                    <div class="card-content center-align">
                        <img style="width:100%;max-width:240px" src="https://www.assodpo.it/wp-content/uploads/2019/10/Asso-DPO-Associazione-Data-Protection-Officer.png" alt="" srcset="">
                        <h5 class="blue-text text-darken-4">Nuova password</h5>
                        <div class="row">
                            <div class="col s12 m6 input-field " >
                                <input type="password" name="" class="tooltipped" id="password" data-position="bottom" data-tooltip="
                                    Una password deve contenere almeno: <br>
                                    - Una maiuscola <br>
                                    - Una minuscola <br>
                                    - Un numero <br>
                                    - Un carattere speciale <br>
                                    E deve essere lunga almeno 8 caratteri.
                                    ">
                                    <label for="password">Password</label>
                                    <span class="helper-text" id="password-ht"></span>
                            </div>
                            <div class="col s12 m6 input-field">
                                <input type="password" name="" id="repeat-password">
                                <label for="repeat-password">Ripeti password</label>
                                <span class="helper-text" id="repeat-password-ht"></span>
                            </div>
                            <div class="col s12">
                                <a id="send" class="btn-large waves-effect waves-light blue darken-4">Imposta nuova password</a>
                            </div>
                        </div>
                        <p><a href="./index.php" class="blue-text text-darken-4">Torna alla home</a> </p>

                    </div>
                </div>
            </div>
        </section>
    </div>
</body>

<script>
    /**
    validatePassword()
    Verifrica che la password rispetti questi criteri, almeno:
    - Una maiuscola
    - Una minuscola
    - Un numero
    - Un carattere speciale

    @str : String - La password da controllare
     */

    function validatePassword(str) { 
        return (str.match(/[a-z]/g) && str.match(/[A-Z]/g) && str.match(/[0-9]/g) && str.match( /[^a-zA-Z\d]/g) && str.length >= 8) 
    } 

    $('#send').on('click', () =>{
        
        $('#send').addClass('hide')
        let invalidInput = 0;
        
        // Controlla con un ciclo che nessun campo sia stato lasciato vuoto.

        $('input').each(function() {
            if($.trim($(this).val()) == ''){
                $(this).addClass('invalid')
                invalidInput ++
            }else{
                $(this).removeClass('invalid')
            }
        });

        if(!validatePassword($('#password').val())){
            $('#password').addClass('invalid')
            $('#password-ht').attr('data-error','La password non rispetta i criteri')
            invalidInput ++ 
        }else{
            $('#password').removeClass('invalid')
        }

        if($('#password').val() != $('#repeat-password').val()){
            $('#repeat-password').addClass('invalid')
            $('#repeat-password-ht').attr('data-error','Le due password non coincidono')
            invalidInput ++ 
        }else{
            $('#repeat-password').removeClass('invalid')
        }

        if(invalidInput == 0){
            const url = `./api/updatePassword.php`
            const fd = new FormData()

            fd.append('password',$('#password').val())
            fd.append('password_code',`<?php echo $_GET['pid'] ?>`)

            fetch(url, {
                method : 'POST',
                body: fd
            })
            .then(res => res.json())
            .then(data => {
                if(data.errors == 0){
                    window.location = './index.php'
                }else{
                    switch(data.error_code){
                        case 'expired': 
                            M.toast({html: 'Il link fornito è scaduto. È necessario chiedere nuovamente il recupero della password.'})
                        break;
                        case 'notfound': 
                            M.toast({html: 'Il link fornito non è valido. È necessario chiedere nuovamente il recupero della password.'})
                        break;
                        case 'generic': 
                            M.toast({html: 'Si è verificato un errore, riprova in seguito.'})
                            console.log(data.error_message)
                        break;
                        default: break;
                    }
                }
            })
            .catch(e => console.log(e))

        }
            
        $('#send').removeClass('hide')
        
    });


</script>