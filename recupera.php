<?php include './components/include.php' ?>

<body class="blue darken-4">
    <div class="container">
        <section class="section section-login row">
            <div class="col m8 offset-m2">
                <div class="card">
                    <div class="card-content center-align">
                        <img style="width:100%;max-width:240px" src="https://www.assodpo.it/wp-content/uploads/2019/10/Asso-DPO-Associazione-Data-Protection-Officer.png" alt="" srcset="">
                        <h5 class="blue-text text-darken-4">Recupera password</h5>
                        <div class="row">
                            <div class="col s12 input-field">
                                <input type="email" name="" id="username">
                                <label for="username">Email</label>
                                <span class="helper-text" id="username-ht"></span>
                            </div>
                            <div class="col s12">
                                <a id="recupera" class="btn-large waves-effect waves-light blue darken-4">Recupera</a>
                            </div>
                        </div>
                        <p><a href="./index.php" class="blue-text text-darken-4">Torna indietro</a> </p>

                    </div>
                </div>
            </div>
        </section>
    </div>
</body>

<script>
    /**
    validateEmail()
    Verifica con una regexp che il formato della mail sia corretto.
    @email : String - La mail da controllare
    */

    function validateEmail(email) {
        const re = /^(([^<>()\[\]\\.,;:\s@"]+(\.[^<>()\[\]\\.,;:\s@"]+)*)|(".+"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))$/;
        return re.test(String(email).toLowerCase());
    }

    $('#recupera').on('click', () => {
        $('#recupera').addClass('hide')
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

        if(!validateEmail($('#username').val())){
            $('#username').addClass('invalid')
            $('#username-ht').attr('data-error','Inserisci una mail valida')
            invalidInput ++ 
        }else{
            $('#username').removeClass('invalid')
        }

        if(invalidInput == 0){

            const url = `./api/passwordRecover.php`
            const fd = new FormData();

            fd.append('username',$('#username').val())

            fetch(url, {
                method : 'POST',
                body: fd
            })
            .then(res => res.json())
            .then(data => {
                if(data.errors == 0){
                    M.toast({html: 'È stata inviata una mail all\'indirizzo fornito contenente le istruzioni per poter impostare una nuova password.'})
                }else{
                    switch(data.error_code){
                        case "notfound":
                            M.toast({html: 'La mail fornita non appartiene a nessun utente.'})
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

        $('#recupera').removeClass('hide')


    })

</script>