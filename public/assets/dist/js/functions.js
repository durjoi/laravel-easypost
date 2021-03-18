function encryptData (string) {
    return CryptoJS.AES.encrypt(string, 'tronicspay secret key');
}

function decryptData (string) {
    var bytes = CryptoJS.AES.decrypt(string.toString(), 'tronicspay secret key');
    return bytes.toString(CryptoJS.enc.Utf8);
}


function ReplaceNumberWithCommas(yourNumber) {
    //Seperates the components of the number
    var components = yourNumber.toString().split(".");
    //Comma-fies the first part
    components [0] = components [0].replace(/\B(?=(\d{3})+(?!\d))/g, ",");
    //Combines the two sections
    return components.join(".");
}