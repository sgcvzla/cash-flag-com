/*
// Nota: la siguiente línea debe ir en el html que llama a esta librería
// <script src="https://unpkg.com/@aeternity/aepp-sdk@7.3.1/dist/aepp-sdk.browser-script.js"></script>
//////////////////////////////////////////////////////////////////////////////////////////////////////////////
*/


// LR- Access to a Wallet / Acceder a la Wallet con su llave privada
// 
// Parámetros:
// -----------
// secretKey: LLave privada de la cuenta que se desea abrir.
// 
// Retorna:
// --------
// objeto: Objeto con dos valores: publicKey (llave pública o dirección) y privateKey (llave privada).
// 
function abrirCuentaConLlavePrivada(secretKey) {
   // LR - Convert secretKey to an ArrayBuffer / Convertir la calve privada en un ArrayBuffer
   const hexBuffer = Ae.Crypto.hexStringToByte(secretKey);

   // LR - Get to an object with secret key & private key in format UInt8Array / 
   // OBtener un objeto con la clave secreta y llave privada en formato UInt8Array
   const keyPair   = Ae.Crypto.generateKeyPairFromSecret(hexBuffer)

   // LR - Get public Key / Obtener clave pública
   const pKey = Ae.Crypto.aeEncodeKey(keyPair.publicKey);

   // LR - Return an object with public & secret key / devuelve un objeto con claves pública y privada
   return {
      publicKey: pKey,
      privateKey: secretKey
   };
}


// LR- Generate a Wallet / Generar una Wallet
// 
// Parámetros:
// -----------
// ninguno.
// 
// Retorna:
// --------
// objeto: Objeto con dos valores: publicKey (llave pública o dirección) y privateKey (llave privada).
// 
async function crearCuenta() {
   // LR - Generate keyPair and assign to secretKey-publicKey / Genera keyPair y asigna valores a secretKey-publicKey
   let { secretKey, publicKey } = Ae.Crypto.generateKeyPair(true);

   // LR - Decode vars to human-readable / decodifica variables para que puedan ser leidas
   let decodedPublicKey=Ae.Crypto.aeEncodeKey(publicKey);
   let decodedSecretKey=buffer.Buffer.from(secretKey).toString('hex');
 
   return {
      publicKey: decodedPublicKey,
      privateKey:decodedSecretKey
   };
}


// LR - Create instance of dthe SDK associate to wallet / Crear instancia del SDK asociada a la wallet
// 
// Parámetros:
// -----------
// secretKey: LLave privada de la cuenta a la que se va a conectar.
// publicKey: LLave pública o dirección de la cuenta a la que se va a conectar.
// 
// Retorna:
// --------
// objeto Instancia: Objeto con los parámetros de la conexión a la cuenta.
// 
async function instanciaSdk(secretKey,publicKey) {
   // LR - Node name / Nombre del nodo
   const NODE_URL = 'https://sdk-mainnet.aepps.com';

   // LR - Account associated to wallet / Cuenta asociada a la wallet
   const ACCOUNT  =  Ae.MemoryAccount({
                        keypair: {
                           secretKey: secretKey,
                           publicKey: publicKey
                        }
                     });

   // LR - Create node instance / Crear una instancia del nodo
   const nodeInstance = await Ae.Node({ url: NODE_URL })

   // LR - Create the instance of the SDK associated to account / Crear instancia del SDK asociada a la cuenta
   const sdkInstance  = await Ae.Universal({
                                 compilerUrl: 'https://compiler.aepps.com',
                                 nodes: [ { name: 'mainnet', instance: nodeInstance } ],
                                 accounts: [ ACCOUNT ]
                              });

   // Return instance / Devuelve instancia
   return sdkInstance;
}


// LR - Get account's balance / Obtener el balance de la cuenta
// 
// Parámetros:
// -----------
// publicKey: LLave pública o dirección de la cuenta a la que se va a conectar.
// objeto Instancia: Objeto con los parámetros de la conexión a la cuenta.
// 
// Retorna:
// --------
// monto: Balance de la cuenta.
//        En caso de error, retorna cero e imprime en la cónsola el mensaje de error
// 
async function obtenerBalance(publicKey,sdkInstance) {
   try {
      // LR -  Get balance / Obtener balance
      let balance = await sdkInstance.balance(publicKey);

      balance = balance / 1000000000000000000;

      // LR -  Return balance / Devuelve balance
      return balance;
   } catch(err) {
      console.error(err);
      return 0;
   }   
}


// LR - Transfer tokens / Transferir tokens
// 
// Parámetros:
// -----------
// publicAddress: LLave pública o dirección de la cuenta destino (a la que se va a enviar la transacción).
// monto: Monto de la transacción.
// objeto Instancia: Objeto con los parámetros de la conexión a la cuenta remitente.
// 
// Retorna:
// --------
// objeto JSON que contiene:
// Hash de la transacción
// Monto
// Fee
// Dirección del destinatario
// Dirección del remitente
// 
async function enviarTransaccion(publicAddress,amount,sdkInstance){
   // LR -  Send amount, address & denomination / Envía monto, dirección y denominación
   let objRetorno = await sdkInstance.spend(amount, publicAddress, { denomination: 'ae' });

   // LR - Build JSON to return / Construir JSON para devolver
   jsonRetorno = JSON.parse('{"hash": "'+objRetorno.hash+'","amount": '+objRetorno.tx.amount+',"fee": '+objRetorno.tx.fee+',"recipient": "'+objRetorno.tx.recipientId+'","sender": "'+objRetorno.tx.senderId+'"}');

   // LR - Return JSON / Devolver JSON
   return jsonRetorno;
}
