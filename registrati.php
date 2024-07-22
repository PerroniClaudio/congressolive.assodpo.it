<?php include './components/include.php' ?>

<!-- Utente che non ha mai visto nessuna live -->

<body class="blue darken-4">
    <div class="container">
        <section class="section section-login row">
            <div class="col m8 offset-m2">
                <div class="card">
                    <div class="card-content center-align">
                            <img style="width:100%;max-width:160px" src="https://www.assodpo.it/wp-content/uploads/2019/10/Asso-DPO-Associazione-Data-Protection-Officer.png" alt="" srcset="">
                            <h5 class="blue-text text-darken-4 hide">Registrazione</h5>
                            <h5 class="blue-text text-darken-4">Iscriviti all'evento</h5>
                            <div class="row">
                                <div class="col s12 m6 input-field">
                                    <input type="email" name="" id="username">
                                    <label for="username">Email</label>
                                    <span class="helper-text" id="username-ht"></span>
                                </div>
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
                                    <input type="text" name="" id="name">
                                    <label for="name">Nome</label>
                                    <span class="helper-text" id="name-ht"></span>
                                </div>
                                <div class="col s12 m6 input-field">
                                    <input type="text" name="" id="surname">
                                    <label for="surname">Cognome</label>
                                    <span class="helper-text" id="surname-ht"></span>
                                </div>
                                <div class="col s12 input-field">
                                    <input type="text" name="" id="assodpo">
                                    <label for="assodpo">Tessera ASSODPO/Codice iscrizione</label>
                                    <span class="helper-text" id="assodpo-ht"></span>
                                </div>
                                <div class="col s12">
                                    <small class="grey-text">
                                        <i>
                                            Ai sensi e per gli effetti degli artt. 6, 13, 15 e ss. del Regolamento UE 2016/679 GDPR, cliccando su “Registrati”, 
                                            dichiaro di aver preso visione dell'<a href="./assets/docs/Privacy policy EVENTI LIVE ASSO DPO - 030424.pdf" target="_blank">informativa</a>
                                            per il trattamento dei dati personali dell’Associazione Data Protection Officer | ASSO DPO, per la finalità di iscrizione agli EVENTI LIVE ASSO DPO in modalità a distanza (live streaming / webinar).
                                        </i>
                                    </small> 
                                </div>

                                <div class="col s12">
                                    <br>
                                </div>
                                <div class="col s12">
                                    <a id="registrati" class="btn-large waves-effect waves-light blue darken-4">Registrati</a>
                                </div>
                            </div>
                            <p>Hai già un'account? <a href="./index.php" class="blue-text text-darken-4">Effettua l'accesso</a> </p>
                        </div>
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

    $('#registrati').on('click', () => {

        $('#registrati').addClass('hide')

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
        
        if(!validatePassword($('#password').val())){
            $('#password').addClass('invalid')
            $('#password-ht').attr('data-error','La password non rispetta i criteri')
            invalidInput ++ 
        }else{
            $('#password').removeClass('invalid')
        }

        // if(($('input[name="finalitai"]:checked').val() != "acconsento")&&($('input[name="finalitai"]:checked').val() != "non acconsento")){
        //     invalidInput ++ 
        //     $("#radioError").html("Scegli almeno una delle due opzioni")
        // }else{
        //     $("#radioError").html("")
        // }

        if(invalidInput == 0){

            const url = `./api/registerUser.php`
            const fd = new FormData()

            fd.append('username',$('#username').val())
            fd.append('name',$('#name').val())
            fd.append('surname',$('#surname').val())
            fd.append('password',$('#password').val())
            fd.append('assodpo',$('#assodpo').val())
            // fd.append('finalitai', $('input[name="finalitai"]:checked').val())

            fetch(url, {
                method : 'POST',
                body: fd
            })
            .then(res => res.json())
            .then(data => {
                console.log(data)
                if(data.errors == 0){
                    window.location = './login.php'
                }else{
                    switch(data.error_code){
                        case 'email': 
                            M.toast({html: 'È già presente un utente con questo indirizzo email.'})
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

        $('#registrati').removeClass('hide')
        

    })

</script>