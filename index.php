<!DOCTYPE html>
<html>

<head>
    <meta charset="UTF-8">
    <title>Explorer v2.0</title>
    <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.6.3/css/all.css" integrity="sha384-UHRtZLI+pbxtHCWp1t77Bi1L4ZtiqrqD80Kn4Z8NTSRyMA2Fd33n5dQ8lWUE00s/" crossorigin="anonymous">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.16.0/umd/popper.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</head>

<body>
    <?php

    ############## Paramètres ##############
    $colonne = 6; # 1, 2, 3, 4, 6, 12
    $url = "http://localhost/";
    $base = "./";
    $repertoire_courrant = "";
    $repertoire = "./";
    ############## Paramètres ##############

    if (isset($_GET['dir'])) {
        $repertoire_courrant = $_GET['dir'] . "/";
        $repertoire = $repertoire . "/" . $_GET['dir'];
    }

    $files = scandir($repertoire);

    if ($colonne == 1) {
        $col = "12";
    } else {
        $col = 12 / $colonne;
    }

    #traitement
    if ($_POST) {
        #Création du répertoire
        if (isset($_POST['creer_dossier']) && isset($_POST['nom_dossier'])) {
            if (!empty($_POST['nom_dossier'])) {
                if (is_dir($repertoire . "/" . $_POST['nom_dossier'])) {
                    die('Le repertoire existe déjà...');
                } else {
                    if (!mkdir($repertoire . "/" . $_POST['nom_dossier'], 0777, true)) {
                        die('Echec lors de la création du répertoire...');
                    }
                    header('Refresh:1');
                }
            }
        }
        #upload fichier
        if (isset($_POST['upload']) && isset($_FILES)) {
            if (isset($_FILES['fichier'])) {
                if (!upload($base . "/" . $repertoire_courrant)) {
                    die('Erreur...');
                }
                header('Refresh:1');
            }
        }
        #suppression fichier
        if (isset($_POST['supprimer_fichier'])) {
            if (!unlink($base . "/" . $_POST['supprimer_fichier'])) {
                die('Erreur lors de la suppression du fichier ...');
            }
            header('Refresh:1');
        }
        #suppression dossier
        if (isset($_POST['supprimer_dossier'])) {
            if (!SupprimerDossier($base . "/" . $_POST['supprimer_dossier'])) {
                die('Erreur lors de la suppression du dossier ...');
            }
            header('Refresh:1');
        }
    }

    echo "<form action='' method='post' enctype='multipart/form-data'>";
    echo "<div class='container mt-4'>";
    echo "<div class='row'>";
    echo "<div class='col-12'>";
    echo "<input type='file' name='fichier' value='' class='btn btn-dark'>";
    echo "<button type='submit' name='upload' class='btn btn-dark ml-1' value='upload'>";
    echo "<i class='fas fa-upload'></i> ";
    echo "Upload";
    echo "</button>";
    echo "<button type='submit' name='creer_dossier' value='creer_dossier' class='btn btn-dark float-right ml-1'>";
    echo "<i class='fas fa-plus'></i>";
    echo " <i class='fas fa-folder'></i>";
    echo "</button>";
    echo "<input type='text' name='nom_dossier' class='form-control float-right w-auto' placeholder='nom du dossier'>";
    echo "<hr>";
    echo "</div>";
    echo "<div class='col-12'>";
    echo "<ol class='breadcrumb'>";
    $path = "";
    $dirs = explode('/', $repertoire);
    echo "<a href='" . $url . "' class='mt-1'>";
    echo "<i class='fas fa-home fa-lg'></i>";
    echo "</a>";

    foreach ($dirs as $dir) {
        if ($base == "./" . $dir . "/") {
            $dirLink = "";
        } else {
            if (!empty($dir)) {
                if ($dir != '.') {
                    $path = $path . "/" . $dir;
                    $dirLink = $path;
                }
            }
        }

        if (!empty($dir)) {
            if ($dir != '.') {
                echo "&nbsp;<i class='fas fa-chevron-right mt-1 fa-lg text-secondary mr-2 ml-2'></i>&nbsp;";
                echo "<a href='?dir=" . $dirLink . "'>";
                echo $dir;
                echo "</a>";
            }
        }
    }
    echo "</ol>";
    echo "</div>";

    $dossiers = "";
    $fichiers = "";
    $nb_items = 0;
    foreach ($files as $file) {
        if ($file == '.' || $file == '..') continue;
        if (is_file($repertoire . "/" . $file)) {
            $url_fichier = utf8_encode($url . $repertoire . "/" . $file);
            $extension_fichier = substr($file, (strlen($file) - 4), strlen($file));
            $extension_fichier = str_replace('.', '', $extension_fichier);
            $icone = extFA($extension_fichier);
            $fichiers .= "<div class='col-12 col-lg-" . $col . " mt-4'>";
            $fichiers .= "<div class='card h-100' style='background:#F9F9F9;box-shadow:0 1px 5px #ddd;'>";
            $fichiers .= "<div class='card-body text-center pt-1 pr-1 pl-1 pb-1' >";
            $fichiers .= "<button type='submit' name='supprimer_fichier' value='" . $repertoire_courrant . $file . "' class='btn btn-sm btn-light float-right rounded-0'>";
            $fichiers .= "<i class='fas fa-trash'></i>";
            $fichiers .= "</button>";
            if ($icone == 'image') {
                $fichiers .= "<span style='background-image:url(\"" . $url_fichier . "\");background-size:cover;width:100%;height:100%;display:block'></span>";
            } else {
                $fichiers .= "<h1 class='text-center mt-4'><i class='fas fa-" . $icone . " mt-2 fa-lg'></i></h1>";
            }
            $fichiers .= "</div>";

            if (strlen($file) > 13) {
                $file = substr($file, 0, 13) . " ...";
            }
            $fichiers .= "<div class='card-footer p-2' style='border-top:0;background:transparent'>";
            $fichiers .= "<span class='badge badge-info float-right'>" . $extension_fichier . "</span><br>";
            $fichiers .= "<a href='" . $url_fichier . "' class='d-block' target='_blank'>" . $file . "</a>";
            $fichiers .= "</div>";
            $fichiers .= "</div>";
            $fichiers .= "</div>";
        } else {
            $s_dir = "";
            $s_file = "";
            $directory = $base . $repertoire_courrant . $file . "/";
            $countFiles = count(glob($directory . "*.*"));
            $scanDirs = scandir($directory);
            $countItems = count($scanDirs) - 2;
            $countDirs = $countItems - $countFiles;
            $dossiers .= "<div class='col-12 col-lg-" . $col . " mt-4'>";
            $dossiers .= "<div class='card h-100' style='background:#F3D674;box-shadow:0 1px 5px #ddd;'>";
            $dossiers .= "<div class='card-body text-center pt-1 pr-1 pl-1 pb-1'>";
            $dossiers .= "<button type='submit' name='supprimer_dossier' value='" . $repertoire_courrant . $file . "' class='btn btn-sm float-right'>";
            $dossiers .= "<i class='fas fa-trash'></i>";
            $dossiers .= "</button>";
            $dossiers .= "<h1 class='text-center mt-4'><i class='fas fa-folder-open fa-lg mt-2'></i></h1>";
            $dossiers .= "<span class='badge badge-dark float-right mt-4 ml-2'>" . $countFiles . " <i class='fas fa-file-alt'></i></span>";
            $dossiers .= "<span class='badge badge-dark float-right mt-4'>" . $countDirs . " <i class='fas fa-folder-open'></i></span>";
            $dossiers .= "</div>";
            $file_texte = $file;
            if (strlen($file) > 13) {
                $file_texte = substr($file, 0, 13) . " ...";
            }
            $dossiers .= "<div class='card-footer p-2'><a href='?dir=" . $repertoire_courrant . $file . "'>" . $file_texte . "</a></div>";
            $dossiers .= "</div>";
            $dossiers .= "</div>";
        }
        $nb_items++;
    }

    echo $dossiers;
    echo $fichiers;

    if ($nb_items == 0) {
        echo "<div class='col-12'>";
        echo "<div class='alert alert-warning text-center'>";
        echo "Dossier vide";
        echo "</div>";
        echo "</div>";
    }

    echo "</div>";
    echo "</div>";
    echo "</form>";
    ?>

</body>

</html>

<?php

function SupprimerDossier($strDirectory)
{
    $handle = opendir($strDirectory);

    while (false !== ($entry = readdir($handle))) {
        if ($entry != '.' && $entry != '..') {
            if (is_dir($strDirectory . '/' . $entry)) {
                SupprimerDossier($strDirectory . '/' . $entry);
            } elseif (is_file($strDirectory . '/' . $entry)) {
                if (!unlink($strDirectory . '/' . $entry)) {
                    return false;
                }
            }
        }
    }
    rmdir($strDirectory . '/' . $entry);
    closedir($handle);
    return true;
}

function extFA($extension_fichier)
{
    switch ($extension_fichier) {
        case 'jpg':
            return "image";
            break;
        case 'jpeg':
            return "image";
            break;
        case 'png':
            return "image";
            break;
        case 'gif':
            return "image";
            break;
        case 'txt':
            return "file-alt";
            break;
        case 'ini':
            return "file-code";
            break;
        case 'zip':
            return "file-archive";
            break;
        case 'rar':
            return "file-archive";
            break;
        case '7z':
            return "file-archive";
            break;
        case 'pdf':
            return "file-pdf text-danger";
            break;
        case 'doc':
            return "file-word text-primary";
            break;
        case 'docx':
            return "file-word text-primary";
            break;
        case 'xsl':
            return "file-excel text-success";
            break;
        case 'xlsx':
            return "file-excel text-success";
            break;
        case 'csv':
            return "file-csv text-success";
            break;
        case 'ppt':
            return "file-powerpoint text-danger";
            break;
        case 'pptx':
            return "file-powerpoint text-danger";
            break;
        case 'msg':
            return "envelope-open-text";
            break;
        default:
            return "file";
    }
}

function upload($repertoire)
{
    $uploadfile = $repertoire . basename($_FILES['fichier']['name']);

    if (move_uploaded_file($_FILES['fichier']['tmp_name'], $uploadfile)) {
        return true;
    } else {
        return false;
    }
}

?>