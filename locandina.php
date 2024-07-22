
<?php include './components/config.php' ?>
<?php include './api/isUserLogged.php' ?>
<?php include './components/include.php' ?>

<?php 
    $crsnoq = $db->quote($event_code);
    $live = $db->query("SELECT * FROM live_streams,courses_live WHERE courses_live.crsno = $crsnoq AND live_streams.id = courses_live.live_id")->fetch();
    $appoday = DateTime::createFromFormat('Y-m-d',$live['appointment_day']);

?>

<body class="blue darken-4">
    <div class="container">
        <section class="section section-player row">
            <div class="col s12">
                <div class="card ">
                    <div class="card-content center-align">
                        <img style="width:100%;max-width:240px" src="https://www.assodpo.it/wp-content/uploads/2019/10/Asso-DPO-Associazione-Data-Protection-Officer.png" alt="" srcset="">                            
                        <p style="font-size: 3em">
                            La diretta si terrà in data <?php echo $appoday->format('d/m/Y'); ?> <br> dalle <?php echo substr($live['appointment_start_at'],0,-3); ?> alle <?php echo substr($live['appointment_end_at'],0,-3); ?>
                        </p>
                        <a href="./player.php" class="btn-large waves-effect waves-light blue darken-4 tooltipped" data-position="bottom" data-tooltip="Clica su accedi all'orario indicato">Accedi alla diretta</a>
                    </div>
                </div>
            </div>
        </section>

        <div class="fixed-action-btn">
            <a class="btn-floating btn-large red" id="logout">
                <i class="fas fa-power-off"></i>
            </a>
        </div>
        
    </div>
</body>

<script>

    $('#logout').on('click', () => {
        fetch('./api/logout.php')
        .then(res => res.text())
        .then(data => {
            if(data == "success"){
                window.location = `./index.php`
            }else{
                console.log(data)
            }
        })
        .catch(e => console.log(e))
    })

</script>
