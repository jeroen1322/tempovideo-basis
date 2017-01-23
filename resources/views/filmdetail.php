<div class="row">
    <div class="col-md-10 col-md-offset-1 details">
<?php
if(!empty($_SESSION['login'])){
  $klantId = $_SESSION['login'][0];
  $klantNaam = $_SESSION['login'][1];
  $klantRolId = $_SESSION['login'][2];
  function isEigenaar($klantRolId){
    if($klantRolId === 4){
      return true;
    }else{
      return false;
    }
  }
}
$film = $this->filmNaam;

//Pak de foto van de film
$stmt = DB::conn()->prepare("SELECT id, titel, acteur, omschr, genre, img FROM Film WHERE titel=?");
$stmt->bind_param("s", $film);
$stmt->execute();

$stmt->bind_result($id, $titel, $acteur, $omschr, $genre, $img);
$stmt->fetch();
$stmt->close();


$exm_stmt = DB::conn()->prepare("SELECT id FROM `Exemplaar` WHERE filmid=? AND statusid=1");
$exm_stmt->bind_param("i", $id);
$exm_stmt->execute();
$exm_stmt->bind_result($exemplaar_id);
$beschikbaar = array();
while($exm_stmt->fetch()){
    $beschikbaar[] = $exemplaar_id;
}
$exm_stmt->close();
$count = count($beschikbaar);

$cover = "/cover/" . $img;
$titel = str_replace('_', ' ', $titel);
$titel = strtoupper($titel);

if(!empty($_GET['action'])){
  if($_GET['action'] == 'add'){
    $_SESSION['cart_item'] = array();
    $_SESSION['cart_item']['id'] = $_GET['code'];
    $product_cart_id = $_SESSION['cart_item']['id'];
    // echo $product_cart_id;

    //VOEG TO AAN `ORDER`
    $order_id = rand(1, 2100);
    $bedrag = 7.50;
    $klant = $_SESSION['login']['0'];
    $besteld = 0;
    $cart_stmt = DB::conn()->prepare("INSERT INTO `Order` (id, klantid, bedrag, besteld) VALUES (?, ?, ?, ?)");
    $cart_stmt->bind_param("iidi", $order_id, $klant, $bedrag, $besteld );
    $cart_stmt->execute();
    $cart_stmt->close();

    //VOEG TOE AAN `ORDERREGEL`
    $exm_stmt = DB::conn()->prepare("SELECT id FROM `Exemplaar` WHERE filmid=? AND statusid=1");
    $exm_stmt->bind_param("i", $id);
    $exm_stmt->execute();
    $exm_stmt->bind_result($exemplaar_id);
    $exm_stmt->fetch();
    $exm_stmt->close();

    $exm_stmt = DB::conn()->prepare("UPDATE `Exemplaar` SET statusid=2 WHERE id=?");
    $exm_stmt->bind_param("i", $exemplaar_id);
    $exm_stmt->execute();
    $exm_stmt->close();

    $or_stmt = DB::conn()->prepare("INSERT INTO `Orderregel` (exemplaarid, orderid) VALUES (?, ?)");
    $or_stmt->bind_param("ii", $exemplaar_id, $order_id);
    $or_stmt->execute();
    $or_stmt->close();
    $e = str_replace(' ', '_', $titel);
    header("Refresh:0; url=/film/" . $e);

  }elseif(!empty($_GET['action'])){
    if($_GET['action'] == 'edit'){
      $code = $_GET['code'];
      $edit = true;
    }else{
      $edit = false;
    }
  }
}



if(!empty($id)){

?>
      <a class="btn btn-success terug_button" href="/film/aanbod">
        <li class="fa fa-arrow-left filmaanbod-terug"></li>Filmaanbod
      </a>
        <div class="filmDetails">
          <div class="panel panel-default">
            <div class="panel-body">
              <?php
              if(!empty($_GET['action'])){
                  if($_GET['action'] == 'save'){

                    $code = $id;
                    $nieuweTitel = $_POST['titel'];
                    $nieuweOmschr = $_POST['omschr'];
                    $nieuweActeur = $_POST['acteur'];
                    $nieuweGenre = $_POST['genre'];

                    // //Gegevens invoeren in Film tabel
                    $stmt = DB::conn()->prepare("UPDATE `Film` SET `titel`=?, `omschr`=?, `acteur`=?, `genre`=? WHERE id=?");
                    $stmt->bind_param("sssss", $nieuweTitel, $nieuweOmschr, $nieuweActeur, $nieuweGenre, $code);
                    $stmt->execute();
                    $stmt->close();
                    $reloadTitel = strtolower($nieuweTitel);
                    header("Refresh:0; url=/film/$reloadTitel");
                  }
              }
              ?>
              <img src="<?php echo $cover ?>" class="img-responsive cover"/>
              <?php
              if($edit == true && $code == $id){
                ?>
                <div class="edit_film">
                    <form method="post" action="?action=save&code=<?php echo $id ?>">
                      <h1><b><input type="text" class="form-control" autocomplete="off" value="<?php echo $titel ?>" name="titel"></b></h1>
                    <h3>Omschrijving</h3>
                    <input type="text" class="form-control" autocomplete="off" value="<?php echo $omschr ?>" name="omschr">
                    <h3>Acteurs</h3>
                    <input type="text" class="form-control" autocomplete="off" value="<?php echo $acteur ?>" name="acteur">
                    <h3>Genre</h3>
                    <input type="text" class="form-control" autocomplete="off" value="<?php echo $genre ?>" name="genre">
                    <button type="submit" class="btn btn-success">
                        <i class="fa fa-floppy-o" aria-hidden="true"></i>
                    </button>
                  </form>
                </div>
                <?php
              }else{
                if(!empty($_SESSION['login'])){
                  if(isEigenaar($klantRolId)){
                  ?>
                  <div class="filmDetail_right">
                    <form method="post" action="?action=edit&code=<?php echo $id ?>">
                      <button type="submit" class="btn btn-success">
                          <i class="fa fa-pencil" aria-hidden="true"></i>
                      </button>
                    </form>
                  </div>
                  <?php
                  }
                }
              ?>
              <h1><b><?php echo $titel ?></b></h1>
              <h3>Omschrijving</h3>
              <p><?php echo $omschr ?></p>
              <h3>Acteurs</h3>
              <p><?php echo $acteur ?></p>
              <h3>Genre</h3>
              <p><?php echo $genre ?></p>
              <?php
              $dis = false;
              if($count >=4){
                ?>
                <p class='green_count'><i>NOG BESCHIKBAAR: <?php echo $count ?> </i></p>
                <?php
              }elseif($count <= 4 && $count > 1){
                ?>
                <p class='orange_count'><i>NOG BESCHIKBAAR: <?php echo $count ?></li></p>
                <?php
              }elseif($count >=1){
                ?>
                <p class='red_count'><i>NOG BESCHIKBAAR: <?php echo $count ?></li></p>
                <?php
              }elseif($count == 0){
                $dis = true;
                ?>
                <p class='red_count'><i>NOG BESCHIKBAAR: <?php echo $count ?></li></p>
                  <?php
              }
              ?>
              <h3><b>Prijs</b></h3>
              <p><b>€7,50</b></p>
              <form method="post" action="?action=add&code=<?php echo $id ?>">
                <?php
                if($dis){
                  ?>
                  <input type="submit" class="btn btn-success bestel" value="Bestel" disabled>
                  <?php
                }elseif(empty($_SESSION['login'])){
                  ?>
                  <input type="submit" class="btn btn-success bestel" value="Bestel" disabled><br><br><br>
                  <h5>U moet ingelogd zijn om te kunnen bestellen</h5>
                  <?php
                }else{
                  ?>
                  <input type="submit" class="btn btn-success bestel" value="Bestel">
                </form>
                  <?php
                }
                ?>
              </form>
              <?php
              }
              ?>

            </div>
          </div>
      </div>
    </div>
</div>
<?php

}else{
  echo "404 - FILM NIET GEVONDEN";
}
DB::conn()->close();
