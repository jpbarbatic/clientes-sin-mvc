<?php
function checkUserPassword($usuario, $password)
{
    require_once('db.php');
    $q=$db->prepare("SELECT * FROM usuarios WHERE nombre = ?");
    $q->execute([$usuario]);
    $res=$q->fetch();

    if($res['password']==$password)
    {
        return true;
    }
    return false;
}
