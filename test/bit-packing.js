//https://gist.github.com/SteGriff/a546cb4056c0c3ae501f8da176320f38

const { atob, btoa } = require('abab');
function pack(bytes) {
    var chars = [];
    for(var i = 0, n = bytes.length; i < n;) {
        chars.push(((bytes[i++] & 0xff) << 8) | (bytes[i++] & 0xff));
    }
    return String.fromCharCode.apply(null, chars);
}

function unpack(str) {
    var bytes = [];
    for(var i = 0, n = str.length; i < n; i++) {
        var char = str.charCodeAt(i);
        bytes.push(char >>> 8, char & 0xFF);
    }
    return bytes;
}

const intValues = [43,16,163,14,248,184,59,227,243,146,7,218,100,90,204,129,118,86,28,235,131,114,87,60,197,223,168,61,212,101,233,203];
const hexValues = [0x2B,0x10,0xA3,0x0E,0xF8,0xB8,0x3B,0xE3,0xF3,0x92,0x07,0xDA,0x64,0x5A,0xCC,0x81,0x76,0x56,0x1C,0xEB,0x83,0x72,0x57,0x3C,0xC5,0xDF,0xA8,0x3D,0xD4,0x65,0xE9,0xCB];

const intArray = new Uint8Array(intValues);
const hexArray = new Uint8Array(hexValues);

// intValues.join() has the same result as intArray.toString() FYI
const intArrayString = intArray.toString();
console.log("intArrayString:", intArrayString);
console.log("intArrayString - Length: ", intArrayString.length);
console.log();

// const hexArrayString = hexArray.toString();
// console.log("hexArrayString:", hexArrayString);
// console.log("hexArrayString - Length: ", hexArrayString.length);

const intArrayBase64 = btoa(intArray);
console.log("intArrayBase64:", intArrayBase64);
console.log("intArrayBase64 - Length: ", intArrayBase64.length);
console.log();

// const hexArrayBase64 = btoa(hexArray);
// console.log("hexArrayBase64:", hexArrayBase64);
// console.log("hexArrayBase64 - Length: ", hexArrayBase64.length);

const packIntArray = pack(intArray);
console.log("packIntArray:", packIntArray);
console.log("packIntArray - Length: ", packIntArray.length);
console.log();

const unpackIntArrayString = unpack(packIntArray).toString();
console.log("unpackIntArrayString:", unpackIntArrayString);
console.log("unpackIntArrayString - Length: ", unpackIntArrayString.length);
console.log();

//43,16,163,14,248,184,59,227,243,146,7,218,100,90,204,129,118,86,28,235,131,114,87,60,197,223,168,61,212,101,233,203
//0x2B,0x10,0xA3,0x0E,0xF8,0xB8,0x3B,0xE3,0xF3,0x92,0x07,0xDA,0x64,0x5A,0xCC,0x81,0x76,0x56,0x1C,0xEB,0x83,0x72,0x57,0x3C,0xC5,0xDF,0xA8,0x3D,0xD4,0x65,0xE9,0xCB
