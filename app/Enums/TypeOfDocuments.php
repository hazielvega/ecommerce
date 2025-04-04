<?php
 
// UTILIZO ESTE ENUM PARA INDICAR EL TIPO DE DOCUMENTO DEL CLIENTE
namespace App\Enums;

enum TypeOfDocuments:int
{
    case DNI = 1;
    case CUIL = 2;
    case CUIT = 3;
}