
<?PHP  
include("mysqlcnn.php");
error_reporting(0); 
ob_start(); 

        
if(isset($_POST['guncelle']))
{   
    // test kitapçıklarını günceleme 

    $cevaplar="";

    $sinav_no=$_POST["sinavno"];

    $sql = "select test_adi,cevaplar from test where sinav_no ='".$sinav_no."'";

    $result = $conn->query($sql);

    if ($result->num_rows > 0) { 
        while($row = $result->fetch_assoc()) {
            foreach($_POST[$row["test_adi"]] as $i) {
                
                $cevaplar .= strtoupper($i);
            }
            
            $kod = 'update test set cevaplar="'.$cevaplar.'" where test_adi="'.$row["test_adi"].'" and sinav_no="'.$sinav_no.'"';
            $isle= $conn->prepare($kod);
            $isle->execute();
            $cevaplar="";

        }
    }
    
    $url= "sinavayar.php?sinavno=$sinav_no";
    header("Location:$url");
    ob_end_flush();
}

?>

<!DOCTYPE html>
<html lang="tr">
<head>
    <meta charset="UTF-8">
    <meta name="vieport" content="width=device=width,initial=scale=1,shrick=to=fit=no">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">


    <title>Optik Sonuclari</title>

    <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.8.1/css/all.css" integrity="sha384-50oBUHEmvpQ+1lW4y57PTFmhCaXp0ML5d60M1M7uH2+nqUivzIebhndOJK28anvf" crossorigin="anonymous">

    <link href="https://fonts.googleapis.com/css?family=Slabo+27px&display=swap&subset=latin-ext" rel="stylesheet">

    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/css/bootstrap.min.css" integrity="sha384-Vkoo8x4CGsO3+Hhxv8T/Q5PaXtkKtu6ug5TOeNV6gBiFeWPGFN9MuhOf23Q9Ifjh" crossorigin="anonymous">
    
    <link rel="stylesheet" href="css/style.css">

</head>

<style> 
.uzunluk {
  width: 3%;
  padding-left:6px;
  margin:6px 6px 6px 0px;
  border-radius:4px;
  font-style:italic;
  border-color:#5db7de;

}
.cevapEkle{
    background-color:#5db7de;
    color:white;
    border-radius:0 5px 5px 0;
    text-align:center;
    line-height:1.2;
    padding:1px;
    
}

</style>
<body>

<!-- Creator : Ahmet Tayyip Mujde -->

<!-- nav menü-->
<nav class="navbar navbar-expand-md navbar-dark bg-dark">
    <div class="container">
    <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbar10">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="navbar-collapse collapse" id="navbar10">
        <ul class="navbar-nav nav-fill">
                <li class="nav-item active">
                    <a class="navbar-brand text-center" href="index.php"> <i class="fas fa-file-download">&nbsp;&nbsp;</i>OPTIK OKUT  <span class="sr-only">(current)</span></a>
                </li>
                
                <li class="nav-item">
                    <a href="kodgirisi.php?sinavno=<?php echo $_GET["sinavno"]; ?>" class="nav-link">ANASAYFA</a>
                </li>

                <li class="nav-item">
                    <a href="sinavayar.php?sinavno=<?php echo $_GET["sinavno"]; ?>" class="nav-link">SINAVI AYARLA</a>
                </li>
                
                <li class="nav-item">
                <a href="ogrenciayar.php?sinavno=<?php echo $_GET["sinavno"]; ?>" class="nav-link">OGRENCI AYARLA</a>
                </li>
            </ul>
        </div>
    </div>
</nav>


<div class="container">
    <div class="row">
        <div class="col-md-12">
        <form method="post" name="" id="form1">
            <?php

                $sinav_no=$_GET["sinavno"];

                echo '<input name="sinavno" type="hidden" value="'.$sinav_no.'">';

                $sql = "select ROW_NUMBER() OVER(ORDER BY test_adi ASC) as satir,test_adi,cevaplar from test where sinav_no ='".$sinav_no."'";

                $result = $conn->query($sql);

                //KİTAPCİK(Test) İSLEMLERİ YAZDIRMA

                if ($result->num_rows > 0) { ?>
                
                <table class="table table-striped">
                    <thead>
                        <tr>
                        <th scope="col">#</th>
                        <th scope="col">GRUP ADI </th>
                        <th scope="col">TEST CEVAPLARI</th>
                        <th scope="col">CEVAP EKLE</th>
                        </tr>
                    </thead>
                        
                    <tbody>
                        <tr>
                    <?php
                    //kod girisi ile test verilerini Yazdırma
                            
                        while($row = $result->fetch_assoc()) {
                            
                            echo '<td scope="row">'.$row["satir"].'</td>';
                            echo '<td>'.$row["test_adi"].'</td>';
                            echo '<td>';
                            for($i=0; $i<strlen($row["cevaplar"]); $i++)
                            {   
                                echo ($i+1).'-<input class="uzunluk" type="text" name="'.$row["test_adi"].'[]" value="'.$row["cevaplar"][$i].'" maxlength="1">';
                            }
                            echo '<div id="alan'.$row["test_adi"].'"/></td>';
                            echo '<td>
                                    <div class="d-flex justify-content-start">
                                    <input style="width:80px;" type="text" id="member'.$row["test_adi"].'" name="member'.$row["test_adi"].'" value="">
                                    <a href="#" class="cevapEkle" id="cevapekle'.$row["test_adi"].'" onclick="ekle'.$row["test_adi"].'()">Cevap Ekle</a>
                                    </div>
                                  </td></tr>';
                            /**java script işlemleri */

                            echo '<script >
                            function ekle'.$row["test_adi"].'(){
                                      var number = document.getElementById("member'.$row["test_adi"].'").value;
                                      var alan = document.getElementById("alan'.$row["test_adi"].'");
                                      while (alan.hasChildNodes()) {
                                          alan.removeChild(alan.lastChild);
                                      }

                                      for (i='.strlen($row["cevaplar"]).';i<'.strlen($row["cevaplar"]).'+Number(number);i++){
                                          alan.appendChild(document.createTextNode((i+1)));
                                          var input = document.createElement("input");
                                          input.type = "text";
                                          input.name ="'.$row["test_adi"].'[]";
                                          input.className="uzunluk";
                                          input.style="width:3%";
                                          input.maxLength=1;
                                          alan.appendChild(input);
                                      }
                                  }
                          </script>';
                        }  
                    }
                    ?>
                    
            </tbody> 
        </table>
        <div class="d-flex justify-content-center">
            <button type="submit" form="form1" name="guncelle" class="btn btn-primary btn-lg">KAYDET</button>
        </div>
        </form>
  </div>
</div>

