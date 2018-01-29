<?php
/*
	$dossier : dossier pour explorer les repertoire, varialbe $_GET['dossier']
	$dossierRetour : retour d'un niveau selon la position dans l'explorer
	$explorer : array() du dossier exploré
*/
	function tableauExplorer(){
		$dossier = "";
		$dossierRetour = "";
		if(isset($_GET['dossier'])) $dossier = $_GET['dossier'];
		$retour = explode('/',$dossier);
		$nb = count($retour);
		for($i=0;$i<$nb-1;$i++){
			if($i == $nb-2){
				$slash = "";
			}else{
				$slash = "/";
			}
			$dossierRetour = $dossierRetour.$retour[$i].$slash;
		}
		$explorer = scandir("./".$dossier);
		echo "<table class='table table-dark' id='tableau'>";
		echo "<tr>";
		echo '<td>';
		echo '<input id="checkall" name="checkall" onclick="return cocherOuDecocherTout(this)" value="1" type="checkbox">';
		echo '<label for="checkall"><span></span></label>';
		echo '</td>';
		echo "<td  class='zoneClick' colspan='2'><a href='?dossier=$dossierRetour' style='color:white'><b>...</b></a></td>";
		sort($explorer);
		$count = 0;
		foreach($explorer as $objet):
			if($objet == '.' || $objet == '..' || $objet == 'index.php' || $objet == 'app.explorer'){continue;}
			echo "<tr>";
			echo "<td>";
			echo '<input id="'.$count.'" name="id[]" value=".'.$dossier.'/'.$objet.'" type="checkbox">';
			echo '<label for="'.$count.'"><span></span></label>';
			echo "</td>";
			if(is_dir('./'.$dossier."/".$objet)){
				echo "<td class='dossier zoneClick'><a href='?dossier=$dossier/$objet'>";
				echo "<i class='fa fa-folder' aria-hidden='true'></i>&nbsp;&nbsp;";
				echo $objet;
				echo "</a></td>";
				echo "<td class='dossier'>";
				echo "<p>";
				echo taille('./'.$dossier."/".$objet);
				echo "</p>";
				echo "</td>";
			}else{
				echo "<td class='fichier zoneClick'>";
				echo "<a href='#'></a>";
				echo "<i class='fa fa-file' aria-hidden='true'></i>&nbsp;&nbsp;";
				echo $objet;
				echo "</td>";
				echo "<td class='fichier'>";
				echo taille('./'.$dossier."/".$objet);
				echo "</td>";
			}
			echo "</tr>";
			$count++;
		endforeach;
		echo "</table>";
	}

	function nouveauDossier(){
		if(isset($_POST['ajouterDossier'])){
			if(isset($_POST['dossier'])){
				if($_POST['dossier'] == ""){
					return "";
				}
				echo '<div id="notif-block">';
				if(isset($_GET['dossier'])){

					$cheminDossier = './'.$_GET['dossier'].'/'.$_POST['dossier'];
					if(file_exists($cheminDossier)){
						echo '<div id="notif" class="notif" style="border-left:1em solid #c82333">';
					        echo '<p style="color:#FFF;padding: 1em">Le dossier existe déjà!';
					        echo '</p>';
					    echo "</div>";
					}else{
						if(mkdir($cheminDossier)){
							echo '<div id="notif" class="notif" style="border-left:1em solid #c82333">';
						        echo '<p style="color:#FFF;padding: 1em">Le dossier à bien été créé';
						        echo '</p>';
						    echo "</div>";
						}
					}
				}else{
					$cheminDossier = './'.$_POST['dossier'];
					if(file_exists($cheminDossier)){
						echo '<div id="notif" class="notif" style="border-left:1em solid #c82333">';
					        echo '<p style="color:#FFF;padding: 1em">Le dossier existe déjà!';
					        echo '</p>';
					    echo "</div>";
					}else{
						if(mkdir($cheminDossier)){
							echo '<div id="notif" class="notif" style="border-left:5px solid #dc3545">';
						        echo '<p style="color:#FFF;padding: 1em">Le dossier à bien été créé';
						        echo '</p>';
						    echo "</div>";
						}
					}
				}
				echo "</div>";
			}
		}
	}

	function supprimer(){
		if(isset($_POST['supprimer'])){
			if(isset($_POST['id'])){
				foreach($_POST['id'] as $strDirectory):
					if(!is_dir($strDirectory)){
						unlink($strDirectory);
					}else{
						$handle = opendir($strDirectory);
						while(false !== ($entry = readdir($handle))){
							if($entry != '.' && $entry != '..'){
								if(is_dir($strDirectory.'/'.$entry)){
									rmAllDir($strDirectory.'/'.$entry);
								}
								elseif(is_file($strDirectory.'/'.$entry)){
									unlink($strDirectory.'/'.$entry);
								}
							}
						}
						rmdir($strDirectory.'/'.$entry);
						closedir($handle);
					}  
				endforeach;
			}
		}
	}

	function rmAllDir($strDirectory){
		$handle = opendir($strDirectory);
		while(false !== ($entry = readdir($handle))){
			if($entry != '.' && $entry != '..'){
				if(is_dir($strDirectory.'/'.$entry)){
					rmAllDir($strDirectory.'/'.$entry);
				}
				elseif(is_file($strDirectory.'/'.$entry)){
					unlink($strDirectory.'/'.$entry);
				}
			}
		}
		rmdir($strDirectory.'/'.$entry);
		closedir($handle);
	}

	function uploadFile(){
		$file = "";
		$dossier = "";
		if(isset($_POST['upload'])){
			if(isset($_FILES['donnees'])){
				$file = $_FILES['donnees'];
				if(isset($_GET['dossier'])) $dossier = $_GET['dossier'];
		    $path = "./".$dossier."/"; // Upload directory
		    $count = 0;

		    if(isset($_POST) and $_SERVER['REQUEST_METHOD'] == "POST"){
		            // Loop $_FILES to exeicute all files
		    	foreach ($file['name'] as $f => $name) { 
		    		if(file_exists($path.$name)) {
		    			$message[] = "L'image $name existe déjà!";
		                            continue; // Skip invalid file formats
		                        }   
		                        if ($file['error'][$f] == 4) {
		                    continue; // Skip file if any error found
		                }	       
		                if ($file['error'][$f] == 0) {	           
		                	if(file_exists($path.$name)) {
		                		$message[] = "L'image $name existe déjà!";
		                                    continue; // Skip invalid file formats
		                    }else{ // No error found! Move uploaded files 
		                    	if(move_uploaded_file($file["tmp_name"][$f], $path.$name)){
		                            $count++; // Number of successfully uploaded file
		                        }
		                    }
		                }
		            }
		        }
		    }
		}
    //print_r($message);
	}

	function taille($fichier){
		global $size_unit;
	// Lecture de la taille du fichier
		$taille = filesize($fichier);
	// Conversion en Go, Mo, Ko
		if ($taille >= 1073741824) 
			{ $taille = round($taille / 1073741824 * 100) / 100 . " Go"; }
		elseif ($taille >= 1048576) 
			{ $taille = round($taille / 1048576 * 100) / 100 . " Mo"; }
		elseif ($taille >= 1024) 
			{ $taille = round($taille / 1024 * 100) / 100 . " Ko"; }
		else
			{ $taille = $taille . " o"; } 
		if($taille==0) {$taille="-";}
		return $taille;
	}

	?>