<?php include './components/config.php' ?>
<?php include './api/checkSession.php' ?>
<?php include './components/include.php' ?>

<?php 
    $crsnoq = $db->quote($event_code);
    $live = $db->query("SELECT * FROM live_streams,courses_live WHERE courses_live.crsno = $crsnoq AND live_streams.id = courses_live.live_id")->fetch();
    $appoday = DateTime::createFromFormat('Y-m-d',$live['appointment_day']);

?>

<body class="blue darken-4">
    <div class="container">
        <section class="section section-login row">
            <div class="col m8 offset-m2">
                <div class="card">
                    <div class="card-content center-align" style="display:flex; flex-direction: column; justify-content: center;">
                          
                            <img class="img-responsive" src="./assets/img/logo.webp" alt="" >


                            <section class="section chooser hide">

                                <div class="row">
                                

                                    
                                    <div class="col s12">
                                        <h5>Hai già un account?</h5>
                                    </div>
                                    <div class="col s12">
                                        <a style="width:80%" href="./iscriviti.php" class="btn waves-effect waves-dark blue darken-4">Iscriviti all'evento</a>
                                    </div>

                                    <div class="col s12">
                                        <h5>Non hai ancora un'account?</h5>
                                    </div>
                                    <div class="col s12">
                                        <a style="width:80%" href="./registrati.php" class="btn waves-effect waves-dark blue darken-4">Registrati</a>
                                    </div>

                                    <div class="col s12">
                                        <h5>Sei già iscritto all'evento?</h5>
                                    </div>
                                    <div class="col s12">
                                        <a style="width:80%" href="./login.php" class="btn waves-effect waves-dark blue darken-4">Accedi</a>
                                    </div>
                                   

                                </div>

                            
                            </section>

                            <section class="section chooser">

                                <div class="row">
                                

                                    
                                    <!-- <div class="col s12">
                                        <h5>Hai già un account?</h5>
                                    </div>
                                    <div class="col s12">
                                        <a style="width:80%" href="./iscriviti.php" class="btn waves-effect waves-dark blue darken-4">Iscriviti all'evento</a>
                                    </div> -->

                                    <div class="col s12">
                                        <a style="width:80%" href="./registrati.php" class="btn waves-effect waves-dark blue darken-4">Iscriviti all'evento</a>
                                    </div>

                                    <div class="col s12">
                                        <h5>Sei già iscritto all'evento?</h5>
                                    </div>
                                    <div class="col s12">
                                        <a style="width:80%" href="./login.php" class="btn waves-effect waves-dark blue darken-4">Accedi</a>
                                    </div>
                                   

                                </div>

                            
                            </section>

                            <p>Password dimenticata? <a href="./recupera.php" class="blue-text text-darken-4 ">Recuperala</a> </p>
                            <!-- <p>Non hai un'account? <a href="./registrati.php" class="blue-text text-darken-4 ">Registrati</a> </p> -->


                        </div>
                    </div>
                </div>
            </div>
        </section>
    </div>
</body>

