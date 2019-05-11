<?php

if(!isset($_POST['producto'], $_POST['precio'])){
    exit('Hubo un error');
}

    use PayPal\Api\Payer;   //importando la clase Payer
    use PayPal\Api\Item; 
    use PayPal\Api\ItemList; 
    use PayPal\Api\Details;
    use PayPal\Api\Amount;
    use PayPal\Api\Transaction;
    use PayPal\Api\RedirectUrls;
    use PayPal\Api\Payment;
    require 'config.php';

$producto=htmlspecialchars($_POST['producto']);
$precio=htmlspecialchars($_POST['precio']);
$precio=(float) $precio;
$envio=0;
$total=$precio+$envio;

$compra= new Payer();  //instanciar venta
$compra->setPaymentMethod('paypal'); //seleccionar el tipo de pago (paypal, credit_card, bank);

$articulo=new Item();       //instanciar producto a vender
$articulo->setName($producto)    //nombre del producti
        ->setCurrency('USD')     //Tipo de moneda
        ->setQuantity(1)           //Cantidad
        ->setPrice($precio);

$listaArticulos= new ItemList();
$listaArticulos->setItems(array($articulo)); //recibe un arreglo con todos los articulos 

$detalles= new Details();
$detalles->setShipping($envio) //envio, es requerido agregarlo
            ->setSubtotal($precio);

$cantidad=new Amount();  //cantidad que se va a pagar
$cantidad->setCurrency('USD')
        ->setTotal($precio)       //la suma de todos los subtotales, debe coincidir para que no de error
        ->setDetails($detalles);

$transaccion=new Transaction();
$transaccion->setAmount($cantidad)
            ->setItemList($listaArticulos)
            ->setDescription('Pago')     //lo que aparecera a la hora de pagar
            ->setInvoiceNumber(uniqid());        //Quien lo pago, identificador de pago, podria ser el id del cliente de la BD

$redireccionar=new RedirectUrls();  //a donde nos llevará paypal al finalizar o cancelar la compra
$redireccionar->setReturnUrl(URL_SITIO.'/pago_finalizado.php?exito=true')    //si se aprueba el pago
            ->setCancelUrl(URL_SITIO.'/pago_finalizado.php?exito=false');   //si el usuario cancela el pago

    //Enviar todo a Paypal

$pago=new Payment();
$pago->setIntent("sale")      //intento de venta
    ->setPayer($compra)
    ->setRedirectUrls($redireccionar)
    ->setTransactions(array($transaccion));

    try{
        $pago->create($apiContext);
    }catch(Paypal\Exception\PayPalConnectionException $pce){
        print_r(json_decode($pce->getData()));
        exit;
    }

$aprobado=$pago->getApprovalLink();
header("Location: {$aprobado}");
?>