<?php include './components/config.php' ?>
<?php include './api/checkSession.php' ?>
<?php include './components/include.php' ?>

<!-- Utente già iscritto all'evento che vuole solo vedere la live -->

<body class="blue darken-4">
    <div class="container">
        <section class="section section-login row">
            <div class="col m6 offset-m3">
                <div class="card">
                    <div class="card-content center-align">
                        <img style="width:100%;max-width:160px" src="https://www.assodpo.it/wp-content/uploads/2019/10/Asso-DPO-Associazione-Data-Protection-Officer.png" alt="" srcset="">

                            <section class="section login">
                                <h5 class="blue-text text-darken-4">Login</h5>
                                <div class="row">
                                    <div class="col s12 input-field">
                                        <input type="email" name="" id="username" class="validate">
                                        <label for="username">Email</label>
                                    </div>
                                    <div class="col s12 input-field">
                                        <input type="password" name="" id="password" class="validate">
                                        <label for="password">Password</label>
                                    </div>
                                    <div class="col s12">
                                        <a id="login" class="btn-large waves-effect waves-dark blue darken-4 ">Accedi</a>
                                    </div>
                                </div>
                            </section>

                            <p>Password dimenticata? <a href="./recupera.php" class="blue-text text-darken-4 ">Recuperala</a> </p>

                        </div>
                    </div>
                </div>
            </div>
        </section>
    </div>
</body>

<script>

    $('#login').on('click', () => {
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

        if(invalidInput == 0){
            
            const url = `./api/login.php`;
            const fd = new FormData()

            fd.append('username', $('#username').val())
            fd.append('password', $('#password').val())

            fetch(url, {
                method : 'POST',
                body: fd
            })
            .then(res => res.json())
            .then(data => {
                
                if(data.errors == 0){
                    window.location = `./controller.php`
                }else{
                    switch(data.error_code){
                        case 'notfound': 
                            M.toast({html: 'Email o password errati.'})
                        break;
                        case 'notsubbed': 
                            M.toast({html: 'Utente non iscritto all\' evento.'})
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


    })

    $(document).keypress(function(event){
        var keycode = (event.keyCode ? event.keyCode : event.which);
        if(keycode == '13'){
            $('#login').click();
        }
    });

</script>
